(function () {
    'use strict';

    const cfg = window.ABSENSI_CONFIG || {};
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
        modalSuccessCheck: document.getElementById('modal-success-check'),
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
    };

    let currentPosition = null;
    let geoWatchId = null;
    let modelsReady = false;
    let modelsLoading = false;
    let statusData = null;
    let wajahTerdaftar = null;
    let aksiAktif = null; // 'masuk' | 'pulang' | null
    let mediaStream = null;
    let busy = false;

    // OPTIMASI 1: Ubah inputSize ke 224 agar ringan dan cepat di CPU / HP
    const DETECTOR_OPTIONS = () => new faceapi.TinyFaceDetectorOptions({
        inputSize: 224, 
        scoreThreshold: 0.4,
    });

    init();

    async function init() {
        renderTanggal();
        showPendingFlash();
        loadFaceApiModels().catch(err => console.error('Gagal preload model:', err));
        await fetchWajahStatus();
        await refreshStatus();

        if (els.modalClose) els.modalClose.addEventListener('click', closeModal);
        if (els.modal) {
            els.modal.addEventListener('click', (e) => {
                if (e.target === els.modal) closeModal();
            });
        }
        if (els.modalSubmit) els.modalSubmit.addEventListener('click', onSubmitButtonClick);
    }

    function renderTanggal() {
        if (!els.tanggal) return;
        const now = new Date();
        els.tanggal.textContent = now.toLocaleDateString('id-ID', {
            weekday: 'long', day: 'numeric', month: 'long', year: 'numeric',
        });
    }

    function showPendingFlash() {
        try {
            const raw = sessionStorage.getItem(FLASH_KEY);
            if (!raw) return;
            sessionStorage.removeItem(FLASH_KEY);
            const flash = JSON.parse(raw);
            showToast(flash.type, flash.message);
        } catch (e) {}
    }

    function queueFlash(type, message) {
        try {
            sessionStorage.setItem(FLASH_KEY, JSON.stringify({ type, message }));
        } catch (e) {}
    }

    function showToast(type, message) {
        if (!els.toast || !els.toastBody || !els.toastIcon || !els.toastMessage) return;
        const styles = {
            success: { cls: 'bg-emerald-50 border-emerald-200 text-emerald-700', icon: 'fa-circle-check text-emerald-500' },
            error: { cls: 'bg-red-50 border-red-200 text-red-700', icon: 'fa-circle-exclamation text-red-500' },
            info: { cls: 'bg-indigo-50 border-indigo-200 text-indigo-700', icon: 'fa-circle-info text-indigo-500' },
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
        showToast._timer = setTimeout(() => {
            els.toast.classList.add('hidden');
        }, 4000);
    }

    let modelsPromise = null;

    async function loadFaceApiModels() {
        if (modelsReady) return;
        if (modelsPromise) return modelsPromise;

        modelsPromise = (async () => {
            modelsLoading = true;
            try {
                const base = cfg.modelsUrl || '/models';
                await Promise.all([
                    faceapi.nets.tinyFaceDetector.loadFromUri(base),
                    faceapi.nets.faceLandmark68Net.loadFromUri(base),
                    faceapi.nets.faceRecognitionNet.loadFromUri(base),
                ]);
                modelsReady = true;
            } finally {
                modelsLoading = false;
            }
        })();

        try {
            await modelsPromise;
        } catch (e) {
            modelsPromise = null;
            modelsReady = false;
            throw e;
        }
    }

    async function fetchWajahStatus() {
        try {
            if (!cfg.wajahStatusUrl) return;
            const res = await fetch(cfg.wajahStatusUrl, {
                headers: { Accept: 'application/json' },
                credentials: 'same-origin',
            });
            if (!res.ok) throw new Error('Request status wajah gagal');
            const json = await res.json();
            wajahTerdaftar = !!(json.data && json.data.terdaftar);
        } catch (err) {
            console.error(err);
            wajahTerdaftar = true; 
        }
    }

    function renderWajahBelumTerdaftar() {
        aksiAktif = null;
        if (els.statusBanner) {
            els.statusBanner.className = 'rounded-xl p-4 border flex items-start gap-3 bg-amber-50 border-amber-200 text-amber-700';
            els.statusBanner.innerHTML = `
                <i class="fa-solid fa-face-viewfinder text-lg mt-0.5"></i>
                <div>
                    <p class="font-semibold">Wajah belum terdaftar</p>
                    <p class="text-sm mt-0.5">Data wajah Anda belum ada di sistem, sehingga absen tidak bisa dilakukan.</p>
                </div>`;
        }
        if (els.aksiArea) {
            els.aksiArea.innerHTML = `
                <a href="${cfg.wajahHalamanUrl || '#'}" class="w-full py-3.5 px-4 rounded-xl bg-amber-500 text-white font-semibold shadow-sm hover:bg-amber-600 active:bg-amber-700 transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-face-viewfinder"></i> Daftarkan Wajah Sekarang
                </a>`;
        }
    }

    async function refreshStatus() {
        for (let attempt = 1; attempt <= 3; attempt++) {
            try {
                if (!cfg.statusUrl) return;
                const res = await fetch(cfg.statusUrl, {
                    headers: { Accept: 'application/json' },
                    credentials: 'same-origin',
                });
                if (!res.ok) throw new Error('status request failed (' + res.status + ')');
                const json = await res.json();
                if (!json.data) throw new Error('status data is null');
                statusData = json.data;
                renderStatus(statusData);
                return;
            } catch (err) {
                console.error('refreshStatus attempt ' + attempt + ' failed:', err);
                if (attempt < 3) {
                    await new Promise(r => setTimeout(r, attempt * 1000));
                } else if (els.ringkasanLoading) {
                    els.ringkasanLoading.innerHTML = '<span class="text-red-600"><i class="fa-solid fa-triangle-exclamation mr-2"></i>Gagal memuat status absensi. Silakan refresh halaman.</span>';
                }
            }
        }
    }

    function formatJam(value) {
        if (!value) return '—';
        const match = String(value).match(/(\d{2}):(\d{2})/);
        return match ? `${match[1]}:${match[2]}` : value;
    }

    function renderStatus(data) {
        if (els.namaGuru) els.namaGuru.textContent = data.guru?.nama || 'Guru';
        if (els.infoJamMasuk) els.infoJamMasuk.textContent = formatJam(data.sekolah?.jam_masuk);
        if (els.infoJamKeluar) els.infoJamKeluar.textContent = formatJam(data.sekolah?.jam_keluar);

        if (els.infoJadwal) {
            if (!data.ada_jadwal_mengajar || !Array.isArray(data.jadwal) || data.jadwal.length === 0) {
                els.infoJadwal.innerHTML = `
                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-red-100 text-red-700 border border-red-300">
                        Tidak ada jadwal
                    </span>`;
            } else {
                els.infoJadwal.innerHTML = data.jadwal.map(j => `
                    <div class="mb-2 last:mb-0 text-right">
                        <div class="font-semibold text-slate-800">${j.mata_pelajaran}</div>
                        <div class="text-xs text-slate-500">${j.kelas ?? '-'} ${j.ruangan ? ' • ' + j.ruangan : ''}</div>
                        <div class="text-xs text-indigo-600">${formatJam(j.jam_masuk)} - ${formatJam(j.jam_pulang)}</div>
                    </div>
                `).join('');
            }
        }

        renderStatusBannerDanAksi(data);

        if (els.ringkasanLoading) els.ringkasanLoading.classList.add('hidden');
        if (els.ringkasanContent) els.ringkasanContent.classList.remove('hidden');

        document.dispatchEvent(new CustomEvent('absensi-status-update', { detail: data }));
    }

    function renderStatusBannerDanAksi(data) {
        if (!els.statusBanner || !els.aksiArea) return;

        if (data.sudah_ajukan_izin) {
            aksiAktif = null;
            const statusLabel = data.status_pengajuan === 'disetujui' ? 'Disetujui' :
                                data.status_pengajuan === 'ditolak' ? 'Ditolak' :
                                data.status_pengajuan === 'pending' ? 'Menunggu Persetujuan' : 'Diproses';
            const statusColor = data.status_pengajuan === 'disetujui' ? 'bg-emerald-50 border-emerald-200 text-emerald-700' :
                                data.status_pengajuan === 'ditolak' ? 'bg-red-50 border-red-200 text-red-700' :
                                'bg-amber-50 border-amber-200 text-amber-700';
            const statusIcon = data.status_pengajuan === 'disetujui' ? 'fa-circle-check' :
                               data.status_pengajuan === 'ditolak' ? 'fa-circle-xmark' :
                               'fa-hourglass-half';
            els.statusBanner.className = `rounded-xl p-4 border flex items-start gap-3 ${statusColor}`;
            els.statusBanner.innerHTML = `
                <i class="fa-solid ${statusIcon} text-lg mt-0.5"></i>
                <div>
                    <p class="font-semibold">Pengajuan izin/ketidakhadiran terdaftar</p>
                    <p class="text-sm mt-0.5">Status: <strong>${statusLabel}</strong>. Anda tidak dapat melakukan absen masuk/pulang pada hari ini.</p>
                </div>`;
            els.aksiArea.innerHTML = `
                <div class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-100 text-slate-500 border border-slate-200 text-sm font-semibold w-full justify-center">
                    <i class="fa-solid ${statusIcon}"></i> ${statusLabel}
                </div>`;
            return;
        }

        if (!data.ada_jadwal_mengajar) {
            aksiAktif = null;
            els.statusBanner.className = 'rounded-xl p-4 border flex items-start gap-3 bg-slate-50 border-slate-200 text-slate-500';
            els.statusBanner.innerHTML = `
                <i class="fa-solid fa-calendar-xmark text-lg mt-0.5"></i>
                <div>
                    <p class="font-semibold text-slate-600">Tidak ada jadwal mengajar</p>
                    <p class="text-sm mt-0.5">Anda tidak memiliki jadwal mengajar pada <strong>${data.hari || 'hari ini'}</strong>, sehingga tidak perlu absen.</p>
                </div>`;
            els.aksiArea.innerHTML = '';
            return;
        }

        if (data.sudah_absen_masuk && data.sudah_absen_pulang) {
            aksiAktif = null;
            const jamMasuk = formatJam(data.absensi?.jam_masuk);
            const jamPulang = formatJam(data.absensi?.jam_pulang);
            els.statusBanner.className = 'rounded-xl p-4 border flex items-start gap-3 bg-emerald-50 border-emerald-200 text-emerald-700';
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

        if (!data.sudah_absen_masuk) {
            if (!wajahTerdaftar) {
                renderWajahBelumTerdaftar();
                return;
            }

            const bukaMasuk = data.time_windows?.buka_absen_masuk;
            const sudahBuka = isWindowOpen(bukaMasuk);

            if (!sudahBuka) {
                aksiAktif = null;
                els.statusBanner.className = 'rounded-xl p-4 border flex items-start gap-3 bg-amber-50 border-amber-200 text-amber-700';
                els.statusBanner.innerHTML = `
                    <i class="fa-solid fa-hourglass-half text-lg mt-0.5"></i>
                    <div>
                        <p class="font-semibold">Absen masuk belum dibuka</p>
                        <p class="text-sm mt-0.5">Absen masuk akan dibuka pukul <strong>${formatJam(bukaMasuk)}</strong>.</p>
                    </div>`;
                els.aksiArea.innerHTML = `
                    <button disabled class="w-full py-3.5 px-4 rounded-xl bg-slate-200 text-slate-400 font-semibold text-center flex items-center justify-center gap-2 cursor-not-allowed">
                        <i class="fa-solid fa-lock"></i> Menunggu jam absen masuk
                    </button>`;
                return;
            }

            aksiAktif = 'masuk';
            els.statusBanner.className = 'rounded-xl p-4 border flex items-start gap-3 bg-indigo-50 border-indigo-200 text-indigo-700';
            els.statusBanner.innerHTML = `
                <i class="fa-solid fa-right-to-bracket text-lg mt-0.5"></i>
                <div>
                    <p class="font-semibold">Anda belum absen masuk</p>
                    <p class="text-sm mt-0.5">Tekan tombol di bawah untuk membuka kamera dan merekam absen masuk.</p>
                </div>`;
            els.aksiArea.innerHTML = `
                <button id="btn-buka-modal" class="w-full py-3.5 px-4 rounded-xl bg-indigo-600 text-white font-semibold shadow-sm hover:bg-indigo-700 active:bg-indigo-800 transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-camera"></i> Absen Masuk
                </button>`;
            bindBukaModal('masuk');
            return;
        }

        if (!wajahTerdaftar) {
            renderWajahBelumTerdaftar();
            return;
        }

        const bukaPulang = data.time_windows?.buka_absen_pulang;
        const sudahBukaPulang = isWindowOpen(bukaPulang);
        const jamMasuk = formatJam(data.absensi?.jam_masuk);
        const statusMasuk = data.absensi?.status === 'terlambat' ? 'Terlambat' : 'Hadir';
        const statusColor = data.absensi?.status === 'terlambat' ? 'text-amber-700' : 'text-emerald-700';

        if (!sudahBukaPulang) {
            aksiAktif = null;
            els.statusBanner.className = 'rounded-xl p-4 border flex items-start gap-3 bg-slate-50 border-slate-200 text-slate-600';
            els.statusBanner.innerHTML = `
                <i class="fa-solid fa-circle-check text-lg mt-0.5 ${statusColor}"></i>
                <div>
                    <p class="font-semibold ${statusColor}">Absen masuk tercatat (${statusMasuk})</p>
                    <p class="text-sm mt-0.5">Pukul <strong>${jamMasuk}</strong>. Absen pulang dibuka pukul <strong>${formatJam(bukaPulang)}</strong>.</p>
                </div>`;
            els.aksiArea.innerHTML = `
                <button disabled class="w-full py-3.5 px-4 rounded-xl bg-slate-200 text-slate-400 font-semibold text-center flex items-center justify-center gap-2 cursor-not-allowed">
                    <i class="fa-solid fa-lock"></i> Menunggu jam absen pulang
                </button>`;
            return;
        }

        aksiAktif = 'pulang';
        els.statusBanner.className = 'rounded-xl p-4 border flex items-start gap-3 bg-indigo-50 border-indigo-200 text-indigo-700';
        els.statusBanner.innerHTML = `
            <i class="fa-solid fa-right-from-bracket text-lg mt-0.5"></i>
            <div>
                <p class="font-semibold">Anda belum absen pulang</p>
                <p class="text-sm mt-0.5">Absen masuk tercatat pukul <strong>${jamMasuk}</strong> (${statusMasuk}). Tekan tombol di bawah untuk absen pulang.</p>
            </div>`;
        els.aksiArea.innerHTML = `
            <button id="btn-buka-modal" class="w-full py-3.5 px-4 rounded-xl bg-indigo-600 text-white font-semibold shadow-sm hover:bg-indigo-700 active:bg-indigo-800 transition-all flex items-center justify-center gap-2">
                <i class="fa-solid fa-camera"></i> Absen Pulang
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
        if (btn) {
            btn.addEventListener('click', () => openModal(aksi));
        }
    }

    const SubmitButtonController = {
        el: null,
        states: {
            idle: { label: 'Kirim Absen', icon: 'fa-paper-plane', disabled: false, cls: 'bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white' },
            detecting: { label: 'Mendeteksi wajah…', icon: 'fa-circle-notch fa-spin', disabled: true, cls: 'bg-indigo-400 text-white cursor-wait' },
            submitting: { label: 'Mengirim ke server…', icon: 'fa-circle-notch fa-spin', disabled: true, cls: 'bg-indigo-400 text-white cursor-wait' },
            error: { label: 'Coba Kirim Lagi', icon: 'fa-rotate-right', disabled: false, cls: 'bg-red-500 hover:bg-red-600 active:bg-red-700 text-white' },
            success: { label: 'Berhasil Dikirim', icon: 'fa-check', disabled: true, cls: 'bg-emerald-500 text-white' },
        },
        init(el) { this.el = el; },
        setState(name, labelOverride) {
            const s = this.states[name];
            if (!s || !this.el) return;
            this.el.disabled = s.disabled;
            this.el.className = 'w-full py-3.5 px-4 rounded-xl font-semibold shadow-sm transition-all flex items-center justify-center gap-2 mb-3 ' + s.cls;
            this.el.innerHTML = `<i class="fa-solid ${s.icon}"></i> ${labelOverride || s.label}`;
        },
    };
    if (els.modalSubmit) SubmitButtonController.init(els.modalSubmit);

    function openModal(aksi) {
        aksiAktif = aksi;
        if (els.modalTitle) {
            els.modalTitle.innerHTML = aksi === 'masuk'
                ? '<i class="fa-solid fa-camera text-indigo-500"></i> Absen Masuk'
                : '<i class="fa-solid fa-camera text-indigo-500"></i> Absen Pulang';
        }

        resetModalView();
        if (els.modal) {
            els.modal.classList.remove('hidden');
            els.modal.classList.add('flex', 'show');
        }

        startCamera();
        startGeolocation();
        loadFaceApiModels().catch(e => showModalError('Gagal memuat model pendeteksi wajah. Refresh halaman.'));
    }

    function resetModalView() {
        if (els.modalCameraView) els.modalCameraView.classList.remove('hidden');
        if (els.modalSuccessView) els.modalSuccessView.classList.add('hidden');
        if (els.modalSuccessCheck) els.modalSuccessCheck.classList.remove('draw');
        if (els.modalError) {
            els.modalError.classList.add('hidden');
            els.modalError.textContent = '';
        }
        busy = false;
        SubmitButtonController.setState('idle');
    }

    function closeModal() {
        if (els.modal) {
            els.modal.classList.add('hidden');
            els.modal.classList.remove('flex', 'show');
        }
        stopCamera();
        stopGeolocation();
    }

    function updateCameraStatus(text, isWarning = false) {
        if (!els.cameraStatus) return;
        els.cameraStatus.textContent = text;
        els.cameraStatus.className = 'mt-2 text-xs text-center font-medium ' + (isWarning ? 'text-amber-600' : 'text-slate-500');
    }

    async function startCamera() {
        updateCameraStatus('Menyiapkan kamera…', true);
        try {
            // OPTIMASI 2: Minta resolusi sedang (480p cukup untuk absensi wajah)
            mediaStream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: 'user', width: { ideal: 480 }, height: { ideal: 480 } },
                audio: false,
            });
            if (els.video) {
                els.video.srcObject = mediaStream;
                await els.video.play();
            }
            updateCameraStatus('Kamera aktif. Posisikan wajah lalu tekan "Kirim Absen".', false);
        } catch (err) {
            console.error('Kamera error:', err);
            updateCameraStatus('Gagal mengakses kamera. Izinkan akses kamera browser Anda.', true);
            showModalError('Akses kamera ditolak atau perangkat kamera tidak ditemukan.');
        }
    }

    function stopCamera() {
        if (mediaStream) {
            mediaStream.getTracks().forEach(track => track.stop());
            mediaStream = null;
        }
        if (els.video) els.video.srcObject = null;
    }

    function startGeolocation() {
        if (!navigator.geolocation) {
            showGpsStatus(false, 'GPS tidak didukung browser ini');
            return;
        }
        showGpsStatus(false, 'Mencari lokasi GPS…');
        geoWatchId = navigator.geolocation.watchPosition(
            (pos) => {
                currentPosition = pos.coords;
                showGpsStatus(true, `Akurasi GPS: ${Math.round(pos.coords.accuracy)}m`);
            },
            (err) => {
                currentPosition = null;
                showGpsStatus(false, 'Gagal mengambil lokasi GPS. Pastikan GPS aktif.');
            },
            { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
        );
    }

    function stopGeolocation() {
        if (geoWatchId !== null) {
            navigator.geolocation.clearWatch(geoWatchId);
            geoWatchId = null;
        }
    }

    function showGpsStatus(isValid, text) {
        if (!els.gpsPill || !els.gpsText) return;
        els.gpsText.textContent = text;
        els.gpsPill.className = 'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold ' +
            (isValid ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700');
    }

    function showModalError(msg) {
        if (!els.modalError) return;
        els.modalError.textContent = msg;
        els.modalError.classList.remove('hidden');
    }

    async function onSubmitButtonClick() {
        if (busy) return;
        busy = true;

        if (els.modalError) {
            els.modalError.classList.add('hidden');
            els.modalError.textContent = '';
        }

        try {
            if (!currentPosition) {
                throw new Error('Lokasi GPS belum didapatkan. Pastikan akses lokasi diizinkan.');
            }

            SubmitButtonController.setState('detecting');

            if (!modelsReady) {
                updateCameraStatus('Memuat model pendeteksi wajah…', true);
                await loadFaceApiModels();
            }

            if (!els.video || els.video.readyState < 2) {
                throw new Error('Video kamera belum siap. Coba beberapa detik lagi.');
            }

            // OPTIMASI 3: Berikan jeda 50ms agar UI browser sempat merefresh state tombol ("Mendeteksi Wajah")
            await new Promise(r => setTimeout(r, 50));

            const detection = await faceapi
                .detectSingleFace(els.video, DETECTOR_OPTIONS())
                .withFaceLandmarks()
                .withFaceDescriptor();

            if (!detection) {
                throw new Error('Wajah tidak terdeteksi. Posisikan wajah di tengah kamera dengan pencahayaan cukup.');
            }

            const descriptorArray = Array.from(detection.descriptor);

            // OPTIMASI 4: Kompresi foto canvas ke resolusi 480x360 dan JPEG Quality 0.5 (Sangat ringan)
            const canvas = els.canvas;
            canvas.width = 480;
            canvas.height = 360;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(els.video, 0, 0, canvas.width, canvas.height);
            const fotoBase64 = canvas.toDataURL('image/jpeg', 0.5);

            SubmitButtonController.setState('submitting');

            const payload = {
                latitude: currentPosition.latitude,
                longitude: currentPosition.longitude,
                descriptor: descriptorArray,
                foto: fotoBase64,
            };

            const targetUrl = aksiAktif === 'masuk' ? cfg.masukUrl : cfg.pulangUrl;
            if (!targetUrl) throw new Error('URL endpoint absensi tidak dikonfigurasi.');

            const res = await fetch(targetUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': cfg.csrfToken || '',
                },
                credentials: 'same-origin',
                body: JSON.stringify(payload),
            });

            const result = await res.json();

            if (!res.ok) {
                throw new Error(result.message || 'Gagal memproses absensi.');
            }

            SubmitButtonController.setState('success');
            queueFlash('success', result.message || 'Absensi berhasil dicatat.');

            setTimeout(() => {
                closeModal();
                window.location.reload();
            }, 1200);

        } catch (err) {
            console.error('Proses absen gagal:', err);
            showModalError(err.message || 'Terjadi kesalahan sistem.');
            SubmitButtonController.setState('error');
        } finally {
            busy = false;
        }
    }
})();