const DEBUG = true;

function log(...args) {
    if (DEBUG) {
        console.log("[WAJAH]", ...args);
    }
}

(function () {
    'use strict';

    const cfg = window.WAJAH_CONFIG;
    const FLASH_KEY = 'wajah_flash';

    const els = {
        statusLoading: document.getElementById('status-loading'),
        statusContent: document.getElementById('status-content'),
        statusBanner: document.getElementById('status-banner'),
        btnDaftar: document.getElementById('btn-daftar'),
        btnDaftarText: document.getElementById('btn-daftar-text'),
        btnHapus: document.getElementById('btn-hapus'),

        confirmModal: document.getElementById('confirm-modal'),
        confirmCancel: document.getElementById('confirm-cancel'),
        confirmOk: document.getElementById('confirm-ok'),

        modal: document.getElementById('camera-modal'),
        modalClose: document.getElementById('modal-close'),
        modalCameraView: document.getElementById('modal-camera-view'),
        modalSuccessView: document.getElementById('modal-success-view'),
        modalSuccessCheck: document.getElementById('modal-success-check'),
        modalSuccessText: document.getElementById('modal-success-text'),
        modalError: document.getElementById('modal-error'),

        // toast
        toast: document.getElementById('toast'),
        toastBody: document.getElementById('toast-body'),
        toastIcon: document.getElementById('toast-icon'),
        toastMessage: document.getElementById('toast-message'),

        video: document.getElementById('video'),
        canvas: document.getElementById('canvas-capture'),
        cameraStatus: document.getElementById('camera-status'),
    };

    const thresholds = {
        scoreThreshold: 0.4,
        minFaces: 1,
        maxFaces: 1,
        // jika tidak ada face, kita kasih jeda lebih lama biar UX enak
        noFaceDelay: 500,
        multipleFaceDelay: 800,
        lowConfidenceDelay: 600,
    };

    let modelsReady = false;
    let modelsLoading = false;
    let mediaStream = null;
    let detecting = false;
    let submitting = false;
    let detectTimer = null;
    let statusData = null;

    init();

    async function init() {
        showPendingFlash();
        loadFaceApiModels();
        await refreshStatus();

        if (els.btnDaftar) {
            els.btnDaftar.addEventListener('click', async () => {
                await openModal();
            });
        }
        if (els.btnHapus) {
            els.btnHapus.addEventListener('click', () => els.confirmModal.classList.remove('hidden') || els.confirmModal.classList.add('flex'));
        }
        els.confirmCancel?.addEventListener('click', () => closeConfirm());
        els.confirmOk?.addEventListener('click', hapusWajah);
        els.modalClose?.addEventListener('click', closeModal);
        els.modal?.addEventListener('click', (e) => { if (e.target === els.modal) closeModal(); });
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
        try { sessionStorage.setItem(FLASH_KEY, JSON.stringify({ type, message })); } catch (e) { /* abaikan */ }
    }

    function showToast(type, message) {
        if (!els.toast || !els.toastBody || !els.toastIcon || !els.toastMessage) {
            console.warn("[WAJAH] toast element missing, skip toast.", type, message);
            return;
        }

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
        showToast._timer = setTimeout(() => els.toast.classList.add('hidden'), 4000);
    }

    // ================= Face API models =================
    async function loadFaceApiModels() {
        if (modelsReady || modelsLoading) return;
        modelsLoading = true;
        try {
            const base = cfg.modelsUrl;
            await Promise.all([
                faceapi.nets.tinyFaceDetector.loadFromUri(base),
                faceapi.nets.faceLandmark68Net.loadFromUri(base),
                faceapi.nets.faceRecognitionNet.loadFromUri(base),
            ]);
            modelsReady = true;
            log("Face-API models loaded successfully.");
        } catch (err) {
            console.error("Gagal memuat model Face-API:", err);
            showModalError("Gagal memuat sistem deteksi wajah.");
        } finally {
            modelsLoading = false;
        }
    }

    // ================= Status =================
    async function refreshStatus() {
        try {
            const res = await fetch(cfg.statusUrl, {
                headers: { Accept: 'application/json' },
                credentials: 'same-origin',
            });
            if (!res.ok) throw new Error('status request failed');
            const json = await res.json();
            statusData = json.data;
            renderStatus(statusData);
        } catch (err) {
            console.error(err);
            els.statusLoading.innerHTML = '<span class="text-red-600"><i class="fa-solid fa-triangle-exclamation mr-2"></i>Gagal memuat status wajah.</span>';
        }
    }

    function formatTanggal(iso) {
        if (!iso) return '—';
        const d = new Date(iso);
        return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' });
    }

    function renderStatus(data) {
        if (data.terdaftar) {
            els.statusBanner.className = 'rounded-xl p-4 border flex items-start gap-3 bg-emerald-50 border-emerald-200 text-emerald-700';
            els.statusBanner.innerHTML = `
                <i class="fa-solid fa-circle-check text-lg mt-0.5"></i>
                <div>
                    <p class="font-semibold">Wajah sudah terdaftar</p>
                    <p class="text-sm mt-0.5">Terakhir diperbarui: <strong>${formatTanggal(data.diperbarui_pada)}</strong></p>
                </div>`;
            els.btnDaftarText.textContent = 'Perbarui Wajah';
            els.btnHapus.classList.remove('hidden');
        } else {
            els.statusBanner.className = 'rounded-xl p-4 border flex items-start gap-3 bg-amber-50 border-amber-200 text-amber-700';
            els.statusBanner.innerHTML = `
                <i class="fa-solid fa-triangle-exclamation text-lg mt-0.5"></i>
                <div>
                    <p class="font-semibold">Wajah belum terdaftar</p>
                    <p class="text-sm mt-0.5">Anda tidak bisa melakukan absen sebelum mendaftarkan wajah.</p>
                </div>`;
            els.btnDaftarText.textContent = 'Daftarkan Wajah';
            els.btnHapus.classList.add('hidden');
        }

        els.statusLoading.classList.add('hidden');
        els.statusContent.classList.remove('hidden');
    }

    // ================= Hapus =================
    function closeConfirm() {
        els.confirmModal.classList.add('hidden');
        els.confirmModal.classList.remove('flex');
    }

    async function hapusWajah() {
        els.confirmOk.disabled = true;
        els.confirmOk.textContent = 'Menghapus…';
        try {
            const res = await fetch(cfg.hapusUrl, {
                method: 'DELETE',
                credentials: 'same-origin',
                headers: {
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': cfg.csrfToken,
                },
            });
            const json = await res.json();
            closeConfirm();

            if (!res.ok) {
                showToast('error', json.message || 'Gagal menghapus data wajah.');
                return;
            }
            showToast('success', json.message || 'Data wajah berhasil dihapus.');
            await refreshStatus();
        } catch (err) {
            console.error(err);
            closeConfirm();
            showToast('error', 'Tidak dapat menghubungi server.');
        } finally {
            els.confirmOk.disabled = false;
            els.confirmOk.textContent = 'Ya, Hapus';
        }
    }

    // ================= Modal kamera =================
 async function openModal() {
        resetModalView();

        // Hapus hidden, lalu paksa tambahkan flex dan show
        els.modal.classList.remove('hidden');
        els.modal.classList.add('flex', 'show');
        
        // Cadangan jika Tailwind nge-bug: paksa via inline style
        els.modal.style.setProperty('display', 'flex', 'important');

        updateCameraStatus('Menyiapkan Face API...', true);

        try {
            await loadFaceApiModels();
            await startCamera();
        } catch (e) {
            console.error("Gagal membuka modal kamera:", e);
        }
    }

    function resetModalView() {
        els.modalCameraView.classList.remove('hidden');
        els.modalSuccessView.classList.add('hidden');
        els.modalSuccessCheck.classList.remove('draw');
        els.modalError.classList.add('hidden');
        els.modalError.textContent = '';
        submitting = false;
        detecting = false;
    }

   function closeModal() {
        els.modal.classList.add('hidden');
        els.modal.classList.remove('flex', 'show');
        
        // Kembalikan inline style
        els.modal.style.display = 'none';
        
        stopDetectionLoop();
        stopCamera();
    }

    async function startCamera() {
        updateCameraStatus('Menyiapkan kamera…', true);
        try {
            // PERBAIKAN 1: Menggunakan aspek rasio standar (4:3 atau 16:9 idealnya) agar didukung semua device
            mediaStream = await navigator.mediaDevices.getUserMedia({
                video: { 
                    facingMode: 'user', 
                    width: { ideal: 640 }, 
                    height: { ideal: 480 } 
                },
                audio: false,
            });
            els.video.srcObject = mediaStream;

            // PERBAIKAN 2: Memastikan metadata video terisi secara penuh sebelum loop deteksi dijalankan
            await new Promise((resolve) => {
                els.video.onloadedmetadata = () => {
                    resolve();
                };
            });

            await els.video.play();
            log("Kamera siap dengan resolusi asli:", els.video.videoWidth, "x", els.video.videoHeight);
            
            updateCameraStatus('Kamera aktif, mendeteksi wajah…', false);
            startDetectionLoop();
        } catch (err) {
            console.error(err);
            updateCameraStatus('Kamera tidak dapat diakses.', false);
            showModalError('Akses kamera ditolak atau tidak tersedia. Izinkan akses kamera lalu coba lagi.');
        }
    }

    function stopCamera() {
        if (mediaStream) {
            mediaStream.getTracks().forEach((t) => t.stop());
            mediaStream = null;
        }
        els.video.srcObject = null;
    }

    function showModalError(message) {
        els.modalError.textContent = message;
        els.modalError.classList.remove('hidden');
    }

    // ================= Deteksi otomatis =================
    function startDetectionLoop() {
        stopDetectionLoop();
        scheduleNextDetection(400); // interval dipercepat sedikit untuk respons yang lebih responsif
    }

    function stopDetectionLoop() {
        if (detectTimer) clearTimeout(detectTimer);
        detectTimer = null;
        detecting = false;
    }

    function scheduleNextDetection(delay) {
        if (els.modal.classList.contains('hidden')) return;
        detectTimer = setTimeout(runDetection, delay);
    }

    function showValidation(message, delayAfter = 600) {
        // Animasi validasi: gunakan modal-error + warna merah + jeda sebelum scan lagi
        if (els.modalError) {
            els.modalError.textContent = message;
            els.modalError.classList.remove('hidden');
        }
        // update status kamera sebagai feedback animasi
        setCameraStatus(message, false);
        scheduleNextDetection(delayAfter);
    }

    function clearValidation() {
        if (els.modalError) {
            els.modalError.classList.add('hidden');
            els.modalError.textContent = '';
        }
    }

    async function runDetection() {
        if (els.modal?.classList.contains('hidden')) return;
        if (submitting || detecting) {
            scheduleNextDetection(400);
            return;
        }

        if (!modelsReady || !mediaStream || els.video.readyState < 4) {
            scheduleNextDetection(300);
            return;
        }

        detecting = true;
        try {
            clearValidation();

            const options = new faceapi.TinyFaceDetectorOptions({
                inputSize: 224,
                scoreThreshold: thresholds.scoreThreshold,
            });

            // DETEKSI ALL FACES agar bisa validasi 0 / >1
            const detections = await faceapi
                .detectAllFaces(els.video, options)
                .withFaceLandmarks()
                .withFaceDescriptors();

            if (!detections || detections.length === 0) {
                detecting = false;
                showValidation('Wajah belum terdeteksi. Dekatkan kamera atau perbaiki pencahayaan.', thresholds.noFaceDelay);
                return;
            }

            if (detections.length > thresholds.maxFaces) {
                detecting = false;
                showValidation('Terlihat lebih dari 1 wajah. Harap tampil sendiri di frame.', thresholds.multipleFaceDelay);
                return;
            }

            const detection = detections[0];

            // confidence: bentuk object face-api bisa beda (tergantung chain detectAllFaces + landmark + descriptors)
            // TinyFaceDetectorOptions "scoreThreshold" ada, tetapi di runtime nilainya bisa berada di detection.detection.score ATAU detection.score
            const confidence =
                (detection?.detection && typeof detection.detection.score === 'number')
                    ? detection.detection.score
                    : (typeof detection?.score === 'number' ? detection.score : null);

            // Jika confidence tidak terbaca, jangan "mengunci" proses (agar selalu ada respon UI)
            if (confidence !== null && confidence < thresholds.scoreThreshold) {
                detecting = false;
                showValidation('Kualitas wajah kurang jelas. Coba sedikit lebih dekat / fokuskan wajah.', thresholds.lowConfidenceDelay);
                return;
            }

            submitting = true;
            updateCameraStatus('Wajah terdeteksi, menyimpan…', true);
            await submitDaftar(Array.from(detection.descriptor));
        } catch (err) {
            console.error("Error saat running deteksi:", err);
            detecting = false;
            scheduleNextDetection(600);
            showValidation('Terjadi error saat memindai wajah. Coba lagi.', 600);
        }
    }

    async function submitDaftar(descriptor) {
        try {
            const res = await fetch(cfg.simpanUrl, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': cfg.csrfToken,
                },
                body: JSON.stringify({ descriptor, version: 'v1' }),
            });

            const json = await res.json();

            if (!res.ok) {
                showModalError(json.message || 'Gagal menyimpan data wajah.');
                updateCameraStatus('Kamera aktif, mendeteksi wajah…', false);
                submitting = false;
                detecting = false;
                scheduleNextDetection(1500);
                return;
            }

            showSuccessAndReload(json.message || 'Wajah berhasil disimpan');
        } catch (err) {
            console.error(err);
            showModalError('Tidak dapat menghubungi server. Periksa koneksi internet Anda.');
            updateCameraStatus('Kamera aktif, mendeteksi wajah…', false);
            submitting = false;
            detecting = false;
            scheduleNextDetection(1500);
        }
    }

    function showSuccessAndReload(message) {
        stopDetectionLoop();
        els.modalCameraView.classList.add('hidden');
        els.modalSuccessView.classList.remove('hidden');
        els.modalSuccessText.textContent = message;
        void els.modalSuccessCheck.offsetWidth;
        els.modalSuccessCheck.classList.add('draw');

        stopCamera();
        queueFlash('success', message);

        setTimeout(() => window.location.reload(), 1400);
    }
})();