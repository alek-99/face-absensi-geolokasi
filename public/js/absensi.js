(function () {
    'use strict';

    const cfg = window.ABSENSI_CONFIG;
    const FLASH_KEY = 'absensi_flash';

    const els = {
        namaGuru: document.getElementById('nama-guru'),
        tanggal: document.getElementById('tanggal-hari-ini'),

        ringkasanLoading: document.getElementById('ringkasan-loading'),
        ringkasanContent: document.getElementById('ringkasan-content'),
        infoJadwal: document.getElementById('info-jadwal'),
        infoJamMasuk: document.getElementById('info-jam-masuk'),
        infoJamKeluar: document.getElementById('info-jam-keluar'),
        statusBanner: document.getElementById('status-banner'),
        aksiArea: document.getElementById('aksi-area'),

        modal: document.getElementById('camera-modal'),
        modalTitle: document.getElementById('modal-title'),
        modalClose: document.getElementById('modal-close'),
        modalCameraView: document.getElementById('modal-camera-view'),
        modalSuccessView: document.getElementById('modal-success-view'),
        modalSuccessText: document.getElementById('modal-success-text'),
        modalError: document.getElementById('modal-error'),
        modalSubmit: document.getElementById('btn-submit-absen'),

        video: document.getElementById('video'),
        canvas: document.getElementById('canvas-capture'),
        cameraStatus: document.getElementById('camera-status'),
        gpsPill: document.getElementById('gps-pill'),
        gpsText: document.getElementById('gps-text'),

        toast: document.getElementById('toast'),
        toastBody: document.getElementById('toast-body'),
        toastIcon: document.getElementById('toast-icon'),
        toastMessage: document.getElementById('toast-message'),

        // Ringkasan jadwal (statistik hari ini)
        jadwalStatTotal: document.getElementById('jadwal-stat-total'),
        jadwalStatBadge: document.getElementById('jadwal-stat-badge'),

        // Modal izin
        modalIzin: document.getElementById('modal-izin'),
        btnBukaIzin: document.getElementById('btn-buka-izin'),
        btnTutupIzin: document.getElementById('close-modal-izin'),
        formIzin: document.getElementById('form-izin'),
        izinKeterangan: document.getElementById('izin-keterangan'),
        izinCounter: document.getElementById('counter-keterangan'),
        izinError: document.getElementById('izin-error'),
        btnKirimIzin: document.getElementById('btn-kirim-izin'),
    };

    let currentPosition = null;
    let geoWatchId = null;
    let modelsReady = false;
    let modelsPromise = null;
    let statusData = null;
    let wajahTerdaftar = null; // null = belum dicek, true/false = hasil pengecekan
    let aksiAktif = null; // 'masuk' | 'pulang' | null
    let mediaStream = null;
    let busy = false; // guards against double-submit while a request is in flight

    const DETECTOR_OPTIONS = () => new faceapi.TinyFaceDetectorOptions({
        inputSize: 416,
        scoreThreshold: 0.35,
    });

    init();

    async function init() {
        renderTanggal();
        showPendingFlash();
        loadFaceApiModels(); // background, non-blocking
        bindIzinForm();

        await fetchWajahStatus();
        await refreshStatus();

        els.modalClose.addEventListener('click', closeModal);
        els.modal.addEventListener('click', (e) => { if (e.target === els.modal) closeModal(); });
        els.modalSubmit.addEventListener('click', onSubmitButtonClick);

        document.addEventListener('keydown', (e) => {
            if (e.key !== 'Escape') return;
            if (!els.modal.classList.contains('hidden')) closeModal();
            if (!els.modalIzin.classList.contains('hidden')) closeModalIzin();
        });
    }

    function renderTanggal() {
        const now = new Date();
        els.tanggal.textContent = now.toLocaleDateString('id-ID', {
            weekday: 'long', day: 'numeric', month: 'long', year: 'numeric',
        });
    }

    // ================= Flash toast setelah reload =================
    function showPendingFlash() {
        try {
            const raw = sessionStorage.getItem(FLASH_KEY);
            if (!raw) return;
            sessionStorage.removeItem(FLASH_KEY);
            const flash = JSON.parse(raw);
            showToast(flash.type, flash.message);
        } catch (e) { /* abaikan */ }
    }

    function queueFlash(type, message) {
        try {
            sessionStorage.setItem(FLASH_KEY, JSON.stringify({ type, message }));
        } catch (e) { /* abaikan */ }
    }

    function showToast(type, message) {
        const styles = {
            success: { cls: 'bg-emerald-50 border-emerald-200 text-emerald-700', icon: 'fa-circle-check text-emerald-500' },
            error: { cls: 'bg-red-50 border-red-200 text-red-700', icon: 'fa-circle-exclamation text-red-500' },
            info: { cls: 'bg-emerald-50 border-emerald-200 text-emerald-700', icon: 'fa-circle-info text-emerald-500' },
        };
        const s = styles[type] || styles.info;
        els.toastBody.className = 'flex items-center gap-3 px-4 py-3 rounded-xl shadow-lg border text-sm font-medium ' + s.cls;
        els.toastIcon.className = 'fa-solid ' + s.icon;
        els.toastMessage.textContent = message;
        els.toast.classList.remove('hidden');
        els.toast.classList.remove('show');
        void els.toast.offsetWidth;
        els.toast.classList.add('show');

        clearTimeout(showToast._timer);
        showToast._timer = setTimeout(() => els.toast.classList.add('hidden'), 4000);
    }

    // ================= Face API models =================
    function loadFaceApiModels() {
        if (modelsReady) return Promise.resolve();
        if (modelsPromise) return modelsPromise;

        const base = cfg.modelsUrl;
        modelsPromise = Promise.all([
            faceapi.nets.tinyFaceDetector.loadFromUri(base),
            faceapi.nets.faceLandmark68Net.loadFromUri(base),
            faceapi.nets.faceRecognitionNet.loadFromUri(base),
        ]).then(() => {
            modelsReady = true;
        }).catch((err) => {
            modelsPromise = null; // allow retry
            console.error('Gagal memuat model face-api:', err);
            throw err;
        });

        return modelsPromise;
    }

    // ================= Status wajah =================
    async function fetchWajahStatus() {
        try {
            const res = await fetch(cfg.wajahStatusUrl, {
                headers: { Accept: 'application/json' },
                credentials: 'same-origin',
            });
            if (!res.ok) throw new Error('wajah status request failed');
            const json = await res.json();
            wajahTerdaftar = !!(json.data && json.data.terdaftar);
        } catch (err) {
            console.error(err);
            // Gagal cek -> jangan blokir tampilan; backend tetap memvalidasi saat submit absen
            wajahTerdaftar = true;
        }
    }

    function renderWajahBelumTerdaftar() {
        aksiAktif = null;
        els.statusBanner.className = 'rounded-2xl p-4 border flex items-start gap-3 bg-amber-50 border-amber-200 text-amber-800';
        els.statusBanner.innerHTML = `
            <i class="fa-solid fa-face-viewfinder text-lg mt-0.5"></i>
            <div>
                <p class="font-semibold">Wajah belum terdaftar</p>
                <p class="text-sm mt-0.5">Data wajah Anda belum ada di sistem, sehingga absen tidak bisa dilakukan.</p>
            </div>`;
        els.aksiArea.innerHTML = `
            <a href="${cfg.wajahHalamanUrl}" class="w-full py-3.5 px-4 rounded-xl bg-amber-500 text-white font-semibold shadow-sm hover:bg-amber-600 active:bg-amber-700 transition-all flex items-center justify-center gap-2">
                <i class="fa-solid fa-face-viewfinder"></i> Daftarkan wajah sekarang
            </a>`;
    }

    // ================= Status & ringkasan =================
    async function refreshStatus() {
        try {
            const res = await fetch(cfg.statusUrl, {
                headers: { Accept: 'application/json' },
                credentials: 'same-origin',
            });
            const json = await res.json();
            if (!res.ok) throw new Error(json.message || 'status request failed');
            statusData = json.data;
            renderStatus(statusData);
        } catch (err) {
            console.error(err);
            els.ringkasanLoading.innerHTML = `
                <i class="fa-solid fa-triangle-exclamation text-red-500"></i>
                <span class="text-xs font-medium text-red-600 text-center">Gagal memuat status absensi. <button id="btn-retry-status" class="underline font-semibold">Coba lagi</button></span>`;
            const retry = document.getElementById('btn-retry-status');
            if (retry) retry.addEventListener('click', () => {
                els.ringkasanLoading.innerHTML = `<i class="fa-solid fa-circle-notch animate-spin text-xl"></i><span class="text-xs font-medium">Memuat status absensi…</span>`;
                refreshStatus();
            });
        }
    }

    function formatJam(value) {
        if (!value) return '—';
        const match = String(value).match(/(\d{2}):(\d{2})/);
        return match ? `${match[1]}:${match[2]}` : value;
    }

    // Menghitung total menit mengajar dari sebuah entri jadwal (jam_masuk - jam_pulang)
    function hitungMenitJadwal(jamMasuk, jamPulang) {
        const parse = (v) => {
            if (!v) return null;
            const m = String(v).match(/(\d{2}):(\d{2})/);
            if (!m) return null;
            return parseInt(m[1], 10) * 60 + parseInt(m[2], 10);
        };
        const a = parse(jamMasuk);
        const b = parse(jamPulang);
        if (a === null || b === null || b < a) return 0;
        return b - a;
    }

    function formatDurasi(menit) {
        if (!menit || menit <= 0) return '—';
        const h = Math.floor(menit / 60);
        const m = menit % 60;
        if (h && m) return `${h} jam ${m} mnt`;
        if (h) return `${h} jam`;
        return `${m} mnt`;
    }

    function renderStatus(data) {
        els.namaGuru.textContent = data.guru?.nama || 'Guru';
        els.infoJamMasuk.textContent = formatJam(data.sekolah?.jam_masuk);
        els.infoJamKeluar.textContent = formatJam(data.sekolah?.jam_keluar);

        renderStatistikJadwal(data.jadwal);

        renderStatusBannerDanAksi(data);

        els.ringkasanLoading.classList.add('hidden');
        els.ringkasanContent.classList.remove('hidden');
    }

  // ================= Statistik jadwal hari ini =================
    function renderStatistikJadwal(jadwal) {
        const list = Array.isArray(jadwal) ? jadwal : [];
        const totalSesi = list.length;
        const totalMenit = list.reduce(
            (sum, j) => sum + hitungMenitJadwal(j.jam_masuk, j.jam_pulang), 0
        );
        const totalKelas = new Set(list.map((j) => j.nama_kelas).filter(Boolean)).size;

        if (els.jadwalStatBadge) {
            els.jadwalStatBadge.textContent = totalSesi > 0 ? `${totalSesi} sesi` : 'Libur mengajar';
            els.jadwalStatBadge.className = totalSesi > 0
                ? 'text-[11px] font-bold px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200'
                : 'text-[11px] font-bold px-2.5 py-1 rounded-full bg-stone-100 text-stone-500 border border-stone-200';
        }

        if (els.jadwalStatTotal) {
            if (totalSesi > 0) {
                els.jadwalStatTotal.innerHTML = `
                    <div class="grid grid-cols-3 divide-x divide-emerald-100 mb-3 rounded-xl bg-emerald-50/60 border border-emerald-100 overflow-hidden">
                        <div class="px-2 py-2 text-center">
                            <p class="text-base font-extrabold text-emerald-800 leading-none">${totalSesi}</p>
                            <p class="text-[9px] font-semibold text-emerald-600 uppercase tracking-wide mt-1">Sesi</p>
                        </div>
                        <div class="px-2 py-2 text-center">
                            <p class="text-base font-extrabold text-emerald-800 leading-none">${totalKelas}</p>
                            <p class="text-[9px] font-semibold text-emerald-600 uppercase tracking-wide mt-1">Kelas</p>
                        </div>
                        <div class="px-2 py-2 text-center">
                            <p class="text-base font-extrabold text-emerald-800 leading-none">${formatDurasi(totalMenit)}</p>
                            <p class="text-[9px] font-semibold text-emerald-600 uppercase tracking-wide mt-1">Durasi</p>
                        </div>
                    </div>`;
            } else {
                els.jadwalStatTotal.innerHTML = '';
            }
        }

        if (list.length > 0) {
            let html = '<ul class="space-y-1.5">';
            list.forEach((j) => {
                const mapel = escapeHtml(j.nama_mapel || '-');
                const kelas = escapeHtml(j.nama_kelas || '-');
                const ruangan = j.ruangan ? ` (${escapeHtml(j.ruangan)})` : '';
                
                html += `
                    <li class="flex items-center text-stone-600 text-xs py-1.5 border-b border-stone-100 last:border-0">
                        <span class="font-semibold truncate"><i class="fa-solid fa-book-open text-emerald-500 mr-1.5"></i>${mapel} (${kelas}${ruangan})</span>
                    </li>`;
            });
            html += '</ul>';
            els.infoJadwal.innerHTML = html;
        } else {
            els.infoJadwal.innerHTML = '<p class="text-xs text-stone-400 italic py-1">Tidak ada jadwal mengajar hari ini.</p>';
        }
    }

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    function renderStatusBannerDanAksi(data) {
        // Tidak ada jadwal mengajar hari ini
        if (!data.ada_jadwal_mengajar) {
            aksiAktif = null;
            els.statusBanner.className = 'rounded-2xl p-4 border flex items-start gap-3 bg-stone-50 border-stone-200 text-stone-500';
            els.statusBanner.innerHTML = `
                <i class="fa-solid fa-calendar-xmark text-lg mt-0.5"></i>
                <div>
                    <p class="font-semibold text-stone-600">Tidak ada jadwal mengajar</p>
                    <p class="text-sm mt-0.5">Anda tidak memiliki jadwal mengajar pada <strong>${escapeHtml(data.hari || 'hari ini')}</strong>, sehingga tidak perlu absen.</p>
                </div>`;
            els.aksiArea.innerHTML = '';
            return;
        }

        // Sudah mengajukan izin/sakit/cuti/tugas luar untuk hari ini
        if (data.sudah_ajukan_izin) {
            aksiAktif = null;
            const label = { izin: 'Izin', sakit: 'Sakit', cuti: 'Cuti', tugas_luar: 'Tugas luar' }[data.absensi?.status] || 'Ketidakhadiran';
            const persetujuan = data.status_pengajuan;
            const badge = persetujuan === 'disetujui'
                ? { cls: 'bg-emerald-50 border-emerald-200 text-emerald-700', icon: 'fa-circle-check', text: 'Disetujui' }
                : persetujuan === 'ditolak'
                    ? { cls: 'bg-red-50 border-red-200 text-red-700', icon: 'fa-circle-xmark', text: 'Ditolak' }
                    : { cls: 'bg-amber-50 border-amber-200 text-amber-800', icon: 'fa-hourglass-half', text: 'Menunggu persetujuan' };

            els.statusBanner.className = `rounded-2xl p-4 border flex items-start gap-3 ${badge.cls}`;
            els.statusBanner.innerHTML = `
                <i class="fa-solid ${badge.icon} text-lg mt-0.5"></i>
                <div>
                    <p class="font-semibold">${label} — ${badge.text}</p>
                    <p class="text-sm mt-0.5">${persetujuan === 'ditolak' ? 'Pengajuan Anda ditolak. Anda dapat mengajukan kembali.' : 'Pengajuan ketidakhadiran untuk hari ini sudah tercatat.'}</p>
                </div>`;
            els.aksiArea.innerHTML = persetujuan === 'ditolak' ? '' : `
                <div class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-stone-100 text-stone-500 border border-stone-200 text-sm font-semibold w-full justify-center">
                    <i class="fa-solid fa-lock"></i> Absen tidak diperlukan hari ini
                </div>`;
            return;
        }

        // Sudah absen masuk & pulang -> lengkap
        if (data.sudah_absen_masuk && data.sudah_absen_pulang) {
            aksiAktif = null;
            const jamMasuk = formatJam(data.absensi?.jam_masuk);
            const jamPulang = formatJam(data.absensi?.jam_pulang);
            els.statusBanner.className = 'rounded-2xl p-4 border flex items-start gap-3 bg-emerald-50 border-emerald-200 text-emerald-700';
            els.statusBanner.innerHTML = `
                <i class="fa-solid fa-circle-check text-lg mt-0.5"></i>
                <div>
                    <p class="font-semibold">Absensi hari ini sudah lengkap</p>
                    <p class="text-sm mt-0.5">Masuk pukul <strong>${jamMasuk}</strong> &middot; Pulang pukul <strong>${jamPulang}</strong>. Sampai jumpa besok!</p>
                </div>`;
            els.aksiArea.innerHTML = `
                <div class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-100 text-emerald-700 border border-emerald-300 text-sm font-semibold w-full justify-center">
                    <i class="fa-solid fa-badge-check"></i> Anda sudah absen hari ini
                </div>`;
            return;
        }

        // Belum absen masuk
        if (!data.sudah_absen_masuk) {
            if (!wajahTerdaftar) { renderWajahBelumTerdaftar(); return; }

            const bukaMasuk = data.time_windows?.buka_absen_masuk;
            if (!isWindowOpen(bukaMasuk)) {
                aksiAktif = null;
                els.statusBanner.className = 'rounded-2xl p-4 border flex items-start gap-3 bg-amber-50 border-amber-200 text-amber-800';
                els.statusBanner.innerHTML = `
                    <i class="fa-solid fa-hourglass-half text-lg mt-0.5"></i>
                    <div>
                        <p class="font-semibold">Absen masuk belum dibuka</p>
                        <p class="text-sm mt-0.5">Absen masuk akan dibuka pukul <strong>${formatJam(bukaMasuk)}</strong>.</p>
                    </div>`;
                els.aksiArea.innerHTML = `
                    <button disabled class="w-full py-3.5 px-4 rounded-xl bg-stone-200 text-stone-400 font-semibold text-center flex items-center justify-center gap-2 cursor-not-allowed">
                        <i class="fa-solid fa-lock"></i> Menunggu jam absen masuk
                    </button>`;
                return;
            }

            aksiAktif = 'masuk';
            els.statusBanner.className = 'rounded-2xl p-4 border flex items-start gap-3 bg-emerald-50 border-emerald-200 text-emerald-800';
            els.statusBanner.innerHTML = `
                <i class="fa-solid fa-right-to-bracket text-lg mt-0.5"></i>
                <div>
                    <p class="font-semibold">Anda belum absen masuk</p>
                    <p class="text-sm mt-0.5">Tekan tombol di bawah untuk membuka kamera dan merekam absen masuk.</p>
                </div>`;
            els.aksiArea.innerHTML = `
                <button id="btn-buka-modal" class="w-full py-3.5 px-4 rounded-xl bg-emerald-700 text-white font-semibold shadow-sm hover:bg-emerald-800 active:bg-emerald-900 transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-camera"></i> Absen masuk
                </button>`;
            bindBukaModal('masuk');
            return;
        }

        // Sudah absen masuk, belum absen pulang
        if (!wajahTerdaftar) { renderWajahBelumTerdaftar(); return; }

        const bukaPulang = data.time_windows?.buka_absen_pulang;
        const jamMasuk = formatJam(data.absensi?.jam_masuk);
        const statusMasuk = data.absensi?.status === 'terlambat' ? 'Terlambat' : 'Hadir';
        const statusColor = data.absensi?.status === 'terlambat' ? 'text-amber-700' : 'text-emerald-700';

        if (!isWindowOpen(bukaPulang)) {
            aksiAktif = null;
            els.statusBanner.className = 'rounded-2xl p-4 border flex items-start gap-3 bg-stone-50 border-stone-200 text-stone-600';
            els.statusBanner.innerHTML = `
                <i class="fa-solid fa-circle-check text-lg mt-0.5 ${statusColor}"></i>
                <div>
                    <p class="font-semibold ${statusColor}">Absen masuk tercatat (${statusMasuk})</p>
                    <p class="text-sm mt-0.5">Pukul <strong>${jamMasuk}</strong>. Absen pulang dibuka pukul <strong>${formatJam(bukaPulang)}</strong>.</p>
                </div>`;
            els.aksiArea.innerHTML = `
                <button disabled class="w-full py-3.5 px-4 rounded-xl bg-stone-200 text-stone-400 font-semibold text-center flex items-center justify-center gap-2 cursor-not-allowed">
                    <i class="fa-solid fa-lock"></i> Menunggu jam absen pulang
                </button>`;
            return;
        }

        aksiAktif = 'pulang';
        els.statusBanner.className = 'rounded-2xl p-4 border flex items-start gap-3 bg-emerald-50 border-emerald-200 text-emerald-800';
        els.statusBanner.innerHTML = `
            <i class="fa-solid fa-right-from-bracket text-lg mt-0.5"></i>
            <div>
                <p class="font-semibold">Anda belum absen pulang</p>
                <p class="text-sm mt-0.5">Absen masuk tercatat pukul <strong>${jamMasuk}</strong> (${statusMasuk}). Tekan tombol di bawah untuk absen pulang.</p>
            </div>`;
        els.aksiArea.innerHTML = `
            <button id="btn-buka-modal" class="w-full py-3.5 px-4 rounded-xl bg-emerald-700 text-white font-semibold shadow-sm hover:bg-emerald-800 active:bg-emerald-900 transition-all flex items-center justify-center gap-2">
                <i class="fa-solid fa-camera"></i> Absen pulang
            </button>`;
        bindBukaModal('pulang');
    }

    function isWindowOpen(hhmmss) {
        if (!hhmmss) return true;
        const now = new Date();
        const [h, m, s] = String(hhmmss).split(':').map(Number);
        const target = new Date(now.getFullYear(), now.getMonth(), now.getDate(), h || 0, m || 0, s || 0);
        return now >= target;
    }

    function bindBukaModal(aksi) {
        const btn = document.getElementById('btn-buka-modal');
        if (btn) btn.addEventListener('click', () => openModal(aksi));
    }

    // ================= Reusable submit-button state controller =================
    const SubmitButtonController = {
        el: null,
        states: {
            idle: { label: 'Kirim absen', icon: 'fa-paper-plane', disabled: false, cls: 'bg-emerald-700 hover:bg-emerald-800 active:bg-emerald-900 text-white' },
            detecting: { label: 'Mendeteksi wajah…', icon: 'fa-circle-notch fa-spin', disabled: true, cls: 'bg-emerald-400 text-white cursor-wait' },
            submitting: { label: 'Mengirim ke server…', icon: 'fa-circle-notch fa-spin', disabled: true, cls: 'bg-emerald-400 text-white cursor-wait' },
            error: { label: 'Coba kirim lagi', icon: 'fa-rotate-right', disabled: false, cls: 'bg-red-500 hover:bg-red-600 active:bg-red-700 text-white' },
            success: { label: 'Berhasil dikirim', icon: 'fa-check', disabled: true, cls: 'bg-emerald-500 text-white' },
        },
        init(el) { this.el = el; },
        setState(name, labelOverride) {
            const s = this.states[name];
            if (!s || !this.el) return;
            this.el.disabled = s.disabled;
            this.el.className = 'w-full py-3.5 px-4 rounded-xl font-semibold shadow-sm transition-all flex items-center justify-center gap-2 ' + s.cls;
            this.el.innerHTML = `<i class="fa-solid ${s.icon}"></i> ${labelOverride || s.label}`;
        },
    };
    SubmitButtonController.init(els.modalSubmit);

    // ================= Modal kamera =================
    function openModal(aksi) {
        aksiAktif = aksi;
        els.modalTitle.innerHTML = aksi === 'masuk'
            ? '<i class="fa-solid fa-camera text-emerald-600"></i> Absen masuk'
            : '<i class="fa-solid fa-camera text-emerald-600"></i> Absen pulang';

        resetModalView();
        els.modal.classList.remove('hidden');
        els.modal.classList.add('flex', 'show');
        document.body.classList.add('modal-open');

        startCamera();
        startGeolocation();
        loadFaceApiModels();
    }

    function resetModalView() {
        els.modalCameraView.classList.remove('hidden');
        els.modalSuccessView.classList.add('hidden');
        els.modalError.classList.add('hidden');
        els.modalError.textContent = '';
        busy = false;
        SubmitButtonController.setState('idle');
    }

    function closeModal() {
        els.modal.classList.add('hidden');
        els.modal.classList.remove('flex', 'show');
        document.body.classList.remove('modal-open');
        stopCamera();
        stopGeolocation();
    }

    async function startCamera() {
        updateCameraStatus('Menyiapkan kamera…', true);
        try {
            mediaStream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: 'user', width: { ideal: 640 }, height: { ideal: 640 } },
                audio: false,
            });
            els.video.srcObject = mediaStream;
            updateCameraStatus('Kamera aktif. Posisikan wajah lalu tekan "Kirim Absen".', false);
        } catch (err) {
            console.error(err);
            updateCameraStatus('Kamera tidak dapat diakses. Izinkan akses kamera lalu coba lagi.', false);
            showModalError('Akses kamera ditolak atau tidak tersedia.');
        }
    }

    function stopCamera() {
        if (mediaStream) {
            mediaStream.getTracks().forEach((t) => t.stop());
            mediaStream = null;
        }
        els.video.srcObject = null;
    }

    function updateCameraStatus(text, spinning) {
        els.cameraStatus.innerHTML = spinning
            ? `<i class="fa-solid fa-circle-notch animate-spin text-emerald-400"></i> ${text}`
            : `<i class="fa-solid fa-face-smile text-emerald-400"></i> ${text}`;
    }

    function startGeolocation() {
        if (!('geolocation' in navigator)) {
            setGpsState('bad', 'Perangkat tidak mendukung lokasi.');
            return;
        }
        setGpsState(null, 'Mencari lokasi…');
        geoWatchId = navigator.geolocation.watchPosition(
            (pos) => { currentPosition = pos.coords; setGpsState('ok', 'Lokasi ditemukan.'); },
            () => { currentPosition = null; setGpsState('bad', 'Izinkan akses lokasi untuk absen.'); },
            { enableHighAccuracy: true, maximumAge: 5000, timeout: 15000 }
        );
    }

    function stopGeolocation() {
        if (geoWatchId !== null && 'geolocation' in navigator) navigator.geolocation.clearWatch(geoWatchId);
        geoWatchId = null;
        currentPosition = null;
    }

    function setGpsState(state, text) {
        els.gpsPill.classList.remove('bg-amber-50', 'border-amber-200', 'text-amber-800', 'bg-emerald-50', 'border-emerald-200', 'text-emerald-700', 'bg-red-50', 'border-red-200', 'text-red-700');
        if (state === 'ok') els.gpsPill.classList.add('bg-emerald-50', 'border-emerald-200', 'text-emerald-700');
        else if (state === 'bad') els.gpsPill.classList.add('bg-red-50', 'border-red-200', 'text-red-700');
        else els.gpsPill.classList.add('bg-amber-50', 'border-amber-200', 'text-amber-800');
        els.gpsText.textContent = text;
    }

    function showModalError(message) {
        els.modalError.textContent = message;
        els.modalError.classList.remove('hidden');
    }

    // ================= Klik tombol -> deteksi wajah -> kirim ke server =================
    function onSubmitButtonClick(event) {
        event.preventDefault();
        if (busy) return; // proses sedang berjalan, cegah klik dobel
        handleSubmit();
    }

    async function handleSubmit() {
        busy = true;
        els.modalError.classList.add('hidden');

        try {
            if (!modelsReady) {
                SubmitButtonController.setState('detecting', 'Memuat model…');
                updateCameraStatus('Memuat model pengenalan wajah…', true);
                await loadFaceApiModels();
                if (!modelsReady) {
                    showModalError('Model pengenalan wajah belum siap. Coba lagi sebentar.');
                    SubmitButtonController.setState('error');
                    return;
                }
            }

            if (!mediaStream || els.video.readyState < 2) {
                showModalError('Kamera belum siap. Tunggu sampai video aktif lalu coba lagi.');
                SubmitButtonController.setState('error');
                return;
            }

            SubmitButtonController.setState('detecting');
            updateCameraStatus('Mendeteksi wajah…', true);

            const detection = await faceapi
                .detectSingleFace(els.video, DETECTOR_OPTIONS())
                .withFaceLandmarks()
                .withFaceDescriptor();

            if (!detection) {
                showModalError('Wajah tidak terdeteksi. Pastikan wajah berada di tengah bingkai dan pencahayaan cukup, lalu tekan tombol lagi.');
                SubmitButtonController.setState('error');
                updateCameraStatus('Kamera aktif, menunggu tindakan Anda…', false);
                return;
            }

            const skor = detection.detection.score;
            updateCameraStatus(`Wajah terdeteksi (keyakinan ${(skor * 100).toFixed(0)}%)…`, true);

            if (!currentPosition) {
                showModalError('Lokasi GPS belum ditemukan. Tunggu sebentar lalu tekan tombol lagi.');
                SubmitButtonController.setState('error');
                return;
            }

            SubmitButtonController.setState('submitting');
            updateCameraStatus('Mengirim absen ke server…', true);

            const foto = captureSnapshot();
            const url = aksiAktif === 'masuk' ? cfg.masukUrl : cfg.pulangUrl;

            const res = await fetch(url, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': cfg.csrfToken,
                },
                body: JSON.stringify({
                    latitude: currentPosition.latitude,
                    longitude: currentPosition.longitude,
                    descriptor: Array.from(detection.descriptor),
                    foto,
                }),
            });

            const json = await res.json();

            if (!res.ok) {
                showModalError(json.message || 'Terjadi kesalahan saat memproses absensi.');
                SubmitButtonController.setState('error');
                updateCameraStatus('Kamera aktif, menunggu tindakan Anda…', false);
                return;
            }

            const status = json.data && json.data.status;
            const label = aksiAktif === 'masuk'
                ? (status === 'terlambat' ? 'Absen masuk tercatat (Terlambat)' : 'Absen masuk tercatat (Hadir)')
                : 'Absen pulang tercatat';

            SubmitButtonController.setState('success');
            showSuccessAndReload(label, json.message);
        } catch (err) {
            console.error(err);
            showModalError('Terjadi kesalahan tak terduga. Coba lagi.');
            SubmitButtonController.setState('error');
            updateCameraStatus('Kamera aktif, menunggu tindakan Anda…', false);
        } finally {
            busy = false;
        }
    }

    function captureSnapshot() {
        const video = els.video;
        const canvas = els.canvas;
        canvas.width = video.videoWidth || 320;
        canvas.height = video.videoHeight || 320;
        const ctx = canvas.getContext('2d');
        ctx.translate(canvas.width, 0);
        ctx.scale(-1, 1);
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        return canvas.toDataURL('image/jpeg', 0.85);
    }

    function showSuccessAndReload(title, message) {
        els.modalCameraView.classList.add('hidden');
        els.modalSuccessView.classList.remove('hidden');
        els.modalSuccessText.textContent = title;

        stopCamera();
        stopGeolocation();
        queueFlash('success', message || title);

        setTimeout(() => window.location.reload(), 1400);
    }

    // ================= Modal & form pengajuan izin =================
    function bindIzinForm() {
        if (els.izinKeterangan && els.izinCounter) {
            els.izinKeterangan.addEventListener('input', function () {
                els.izinCounter.textContent = this.value.length;
            });
        }

        if (els.btnBukaIzin) {
            els.btnBukaIzin.addEventListener('click', () => {
                els.modalIzin.classList.remove('hidden');
                els.modalIzin.classList.add('flex');
                document.body.classList.add('modal-open');
                if (els.izinError) {
                    els.izinError.classList.add('hidden');
                    els.izinError.innerHTML = '';
                }
            });
        }

        if (els.btnTutupIzin) els.btnTutupIzin.addEventListener('click', closeModalIzin);
        if (els.modalIzin) {
            els.modalIzin.addEventListener('click', (e) => { if (e.target === els.modalIzin) closeModalIzin(); });
        }

        if (els.formIzin) {
            els.formIzin.addEventListener('submit', async function (e) {
                e.preventDefault();

                if (els.izinError) {
                    els.izinError.classList.add('hidden');
                    els.izinError.innerHTML = '';
                }

                if (els.btnKirimIzin) {
                    els.btnKirimIzin.disabled = true;
                    els.btnKirimIzin.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Mengirim…';
                }

                try {
                    const formData = new FormData(els.formIzin);

                    const res = await fetch(cfg.ajukanIzinUrl, {
                        method: 'POST',
                        headers: { Accept: 'application/json', 'X-CSRF-TOKEN': cfg.csrfToken },
                        body: formData,
                        credentials: 'same-origin',
                    });

                    const result = await res.json();

                    if (!res.ok) {
                        if (res.status === 422 && result.errors) {
                            throw new Error(Object.values(result.errors).flat().join('<br>'));
                        }
                        throw new Error(result.message || 'Gagal mengirim pengajuan.');
                    }

                    if (els.btnKirimIzin) {
                        els.btnKirimIzin.innerHTML = '<i class="fa-solid fa-check"></i> Berhasil dikirim';
                        els.btnKirimIzin.className = 'w-full py-3.5 px-4 rounded-xl font-bold shadow-sm transition-all flex items-center justify-center gap-2 bg-emerald-600 text-white text-sm';
                    }

                    queueFlash('success', result.message || 'Pengajuan berhasil dikirim.');

                    setTimeout(() => {
                        closeModalIzin();
                        window.location.reload();
                    }, 1000);
                } catch (err) {
                    console.error('Izin error:', err);
                    if (els.izinError) {
                        els.izinError.innerHTML = err.message || 'Terjadi kesalahan sistem.';
                        els.izinError.classList.remove('hidden');
                    }
                    if (els.btnKirimIzin) {
                        els.btnKirimIzin.disabled = false;
                        els.btnKirimIzin.innerHTML = '<i class="fa-solid fa-paper-plane"></i> Kirim pengajuan';
                    }
                }
            });
        }
    }

    function closeModalIzin() {
        if (!els.modalIzin) return;
        els.modalIzin.classList.add('hidden');
        els.modalIzin.classList.remove('flex');
        document.body.classList.remove('modal-open');
    }
})();