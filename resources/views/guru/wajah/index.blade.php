@extends('layouts.guru')

@section('title', 'Face-ID')
@section('page_title', 'Registrasi Face-ID')

@section('content')
<div class="max-w-md mx-auto px-2 py-4 space-y-5 animate-fade-in">

    {{-- Tombol Kembali --}}
    <div class="flex items-center px-1">
        <a href="{{ route('guru.absensi.index') }}"
            class="inline-flex items-center text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 transition-colors group">
            <i class="fa-solid fa-arrow-left mr-1.5 group-hover:-translate-x-0.5 transition-transform"></i>
            Menu Utama
        </a>
    </div>

    {{-- Main Status Card Container --}}
    <div class="bg-white dark:bg-[#161615] p-6 rounded-2xl border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-sm relative overflow-hidden">
        
        {{-- Loader Utama saat Sinkronisasi --}}
        <div id="main-loading" class="py-10 flex flex-col items-center justify-center space-y-3">
            <div class="relative w-10 h-10">
                <div class="absolute inset-0 rounded-full border-4 border-slate-100 dark:border-zinc-800"></div>
                <div class="absolute inset-0 rounded-full border-4 border-t-indigo-600 dark:border-t-indigo-400 animate-spin"></div>
            </div>
            <p class="text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] tracking-wide animate-pulse">
                Menyinkronkan status biometrik...
            </p>
        </div>

        {{-- Konten Utama (Diisi via JavaScript) --}}
        <div id="main-content" class="hidden text-center space-y-6 py-2 animate-fade-in">
            <div class="flex justify-center">
                <div id="status-icon-box" class="p-4 rounded-2xl border transition-all duration-300">
                    {{-- Ikon dimuat dinamis --}}
                </div>
            </div>

            <div class="space-y-1.5">
                <h2 id="status-title" class="text-lg font-bold text-[#1b1b18] dark:text-[#EDEDEC]">
                    Memuat...
                </h2>
                <p id="status-desc" class="text-xs text-[#706f6c] dark:text-[#A1A09A] leading-relaxed px-4">
                    Menghubungkan ke server...
                </p>
            </div>

            <div class="border-t border-gray-100 dark:border-zinc-900 pt-2"></div>

            <div id="action-area" class="px-2">
                {{-- Tombol aksi dimuat dinamis --}}
            </div>
        </div>
    </div>
</div>

{{-- MODAL KAMERA (Gaya Bottom Sheet Mobile-First) --}}
<div id="camera-modal" class="hidden fixed inset-0 bg-black/60 z-50 items-end sm:items-center justify-center backdrop-blur-xs transition-all duration-300">
    <div class="bg-white dark:bg-[#161615] w-full sm:max-w-md rounded-t-3xl sm:rounded-2xl overflow-hidden shadow-2xl border-t sm:border border-[#e3e3e0] dark:border-[#3E3E3A] transform transition-all duration-300 translate-y-0 max-h-[92vh] flex flex-col">
        
        {{-- Header Modal --}}
        <div class="px-5 py-4 border-b border-[#e3e3e0] dark:border-[#3E3E3A] flex justify-between items-center bg-gray-50 dark:bg-zinc-900/40">
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 bg-indigo-600 dark:bg-indigo-400 rounded-full animate-ping"></div>
                <h3 class="font-bold text-gray-800 dark:text-[#EDEDEC] text-sm">Pindai Wajah Anda</h3>
            </div>
            <button id="btn-close-modal" class="text-gray-400 hover:text-gray-600 dark:hover:text-zinc-300 p-1.5 rounded-full transition text-xl leading-none cursor-pointer">&times;</button>
        </div>

        {{-- Area Kamera --}}
        <div class="p-5 flex-1 overflow-y-auto space-y-4">
            <div class="relative w-full aspect-[4/3] bg-black rounded-2xl overflow-hidden border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-inner">
                
                {{-- Bingkai Pembantu Posisi Wajah --}}
                <div class="absolute inset-8 border-2 border-dashed border-indigo-500/30 rounded-2xl pointer-events-none z-20">
                    <div class="absolute top-0 left-0 w-5 h-5 border-t-4 border-l-4 border-indigo-600 dark:border-indigo-400 rounded-tl-md"></div>
                    <div class="absolute top-0 right-0 w-5 h-5 border-t-4 border-r-4 border-indigo-600 dark:border-indigo-400 rounded-tr-md"></div>
                    <div class="absolute bottom-0 left-0 w-5 h-5 border-b-4 border-l-4 border-indigo-600 dark:border-indigo-400 rounded-bl-md"></div>
                    <div class="absolute bottom-0 right-0 w-5 h-5 border-b-4 border-r-4 border-indigo-600 dark:border-indigo-400 rounded-br-md"></div>
                </div>

                {{-- Loader Internal Kamera --}}
                <div id="camera-loading" class="absolute inset-0 flex flex-col items-center justify-center bg-slate-50 dark:bg-zinc-950 text-center z-30 p-5">
                    <div class="w-8 h-8 border-4 border-indigo-500/10 border-t-indigo-600 dark:border-t-indigo-400 rounded-full animate-spin mb-3"></div>
                    <p id="loading-text" class="text-[11px] text-[#706f6c] dark:text-[#A1A09A] max-w-[200px] leading-relaxed font-medium">
                        Menyiapkan komponen kecerdasan wajah...
                    </p>
                </div>

                {{-- Elemen Video Cermin --}}
                <video id="video" autoplay muted playsinline class="w-full h-full object-cover scale-x-[-1] relative z-10"></video>
            </div>

            {{-- Alert Informasi --}}
            <div class="flex gap-2.5 bg-amber-50 dark:bg-amber-950/20 border border-amber-200/50 dark:border-amber-900/30 p-3.5 rounded-xl">
                <i class="fa-solid fa-circle-info text-amber-600 dark:text-amber-500 text-xs shrink-0 mt-0.5"></i>
                <p class="text-[11px] text-amber-800 dark:text-amber-400/90 leading-relaxed">
                    Posisikan wajah tepat di tengah bingkai. Hindari penggunaan masker, topi, atau kacamata hitam, serta pastikan pencahayaan ruangan mencukupi.
                </p>
            </div>
        </div>

        {{-- Aksi Modal --}}
        <div class="px-5 py-4 bg-gray-50 dark:bg-zinc-900/40 border-t border-[#e3e3e0] dark:border-[#3E3E3A] flex gap-3">
            <button id="btn-cancel" class="flex-1 bg-white dark:bg-zinc-900 hover:bg-gray-100 dark:hover:bg-zinc-800 text-[#706f6c] dark:text-[#A1A09A] font-bold py-3 rounded-xl text-xs transition border border-[#e3e3e0] dark:border-[#3E3E3A] cursor-pointer">
                Batal
            </button>
            <button id="btn-scan" class="flex-1 bg-indigo-600 hover:bg-indigo-700 disabled:bg-indigo-400 dark:disabled:bg-indigo-950/50 text-white font-bold py-3 rounded-xl text-xs transition disabled:cursor-not-allowed cursor-pointer shadow-sm" disabled>
                Ambil Data Wajah
            </button>
        </div>
    </div>
</div>

<script>
    const mainLoading = document.getElementById('main-loading');
    const mainContent = document.getElementById('main-content');
    const statusIconBox = document.getElementById('status-icon-box');
    const statusTitle = document.getElementById('status-title');
    const statusDesc = document.getElementById('status-desc');
    const actionArea = document.getElementById('action-area');

    const cameraModal = document.getElementById('camera-modal');
    const video = document.getElementById('video');
    const btnScan = document.getElementById('btn-scan');
    const cameraLoading = document.getElementById('camera-loading');
    const loadingText = document.getElementById('loading-text');

    let streamKamera = null;
    let modelTerbuka = false;
    
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';

    // 1. Cek Status Registrasi Wajah
    async function cekStatusWajah() {
        try {
            const response = await fetch('/guru/wajah/status');
            const res = await response.json();
            
            mainLoading.classList.add('hidden');
            mainContent.classList.remove('hidden');

            if (res.data && res.data.terdaftar) {
                // STATUS: TERDAFTAR
                statusIconBox.className = "p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/20 border-emerald-200 dark:border-emerald-900/30 text-emerald-600 dark:text-emerald-400 shadow-xs";
                statusIconBox.innerHTML = `<i class="fa-solid fa-face-smile text-3xl"></i>`;
                
                statusTitle.innerText = "Wajah Terdaftar";
                statusDesc.innerHTML = `Data biometrik Anda aman disimpan sejak <span class="font-bold text-indigo-600 dark:text-indigo-400">${new Date(res.data.terdaftar_pada).toLocaleDateString('id-ID')}</span>. Anda bisa melakukan absensi menggunakan pemindaian wajah langsung di kelas.`;
                
                actionArea.innerHTML = `
                    <button id="btn-hapus" class="w-full bg-rose-50 dark:bg-rose-950/20 hover:bg-rose-100 dark:hover:bg-rose-950/40 text-rose-600 dark:text-rose-400 font-bold py-3 rounded-xl transition duration-150 border border-rose-200 dark:border-rose-900/30 text-xs cursor-pointer shadow-xs">
                        <i class="fa-solid fa-trash-can mr-1.5"></i> Hapus Registrasi Wajah
                    </button>
                `;
                document.getElementById('btn-hapus').addEventListener('click', hapusWajah);
            } else {
                // STATUS: BELUM TERDAFTAR
                statusIconBox.className = "p-4 rounded-2xl bg-amber-50 dark:bg-amber-950/20 border-amber-200 dark:border-amber-900/30 text-amber-600 dark:text-amber-500 shadow-xs";
                statusIconBox.innerHTML = `<i class="fa-solid fa-face-meh-blank text-3xl"></i>`;
                
                statusTitle.innerText = "Belum Ada Data Wajah";
                statusDesc.innerText = "Anda belum mendaftarkan template wajah pada perangkat ini. Mohon lakukan pendaftaran biometrik terlebih dahulu untuk mengaktifkan fitur presensi wajah.";
                
                actionArea.innerHTML = `
                    <button id="btn-buka-kamera" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 rounded-xl transition duration-150 border border-indigo-500/20 text-xs cursor-pointer shadow-sm">
                        <i class="fa-solid fa-camera mr-1.5"></i> Daftarkan Wajah Sekarang
                    </button>
                `;
                document.getElementById('btn-buka-kamera').addEventListener('click', bukaModalKamera);
            }
        } catch (error) {
            console.error("Gagal sinkronisasi data:", error);
        }
    }

    // 2. Buka Modal Kamera & Muat Model AI
    async function bukaModalKamera() {
        cameraModal.classList.remove('hidden');
        cameraModal.classList.add('flex');
        btnScan.disabled = true;
        btnScan.innerText = "Menyiapkan...";
        cameraLoading.classList.remove('hidden');

        tutupStreamSaja();

        try {
            // Aktifkan Kamera Perangkat
            loadingText.innerText = "Mengakses kamera depan...";
            const constraints = {
                video: {
                    facingMode: "user",
                    width: { ideal: 640 },
                    height: { ideal: 480 }
                },
                audio: false
            };

            try {
                streamKamera = await navigator.mediaDevices.getUserMedia(constraints);
            } catch (camError) {
                console.warn("Kamera standar gagal, mencoba fallback...");
                streamKamera = await navigator.mediaDevices.getUserMedia({ video: true });
            }

            video.srcObject = streamKamera;

            // Muat Model AI face-api
            if (!modelTerbuka) {
                loadingText.innerText = "Mengunduh file kecerdasan biometrik (face-api)...";
                
                if (typeof faceapi === 'undefined') {
                    throw new Error("Library faceapi tidak terdeteksi di window global.");
                }

                const MODEL_FOLDER_URL = "/models"; 

                try {
                    await faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_FOLDER_URL);
                    await faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_FOLDER_URL);
                    await faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_FOLDER_URL);
                    modelTerbuka = true;
                } catch (modelError) {
                    console.error("Gagal memuat file weights AI:", modelError);
                    throw new Error("Gagal mengunduh file bobot model. Pastikan file .json berada di direktori /public/models/");
                }
            }

            loadingText.innerText = "Menyelaraskan frame video...";
            
            video.onloadedmetadata = async () => {
                try {
                    await video.play();
                    cameraLoading.classList.add('hidden');
                    btnScan.disabled = false;
                    btnScan.innerText = "Ambil Data Wajah";
                } catch (playError) {
                    console.error("Gagal melakukan autoplay stream:", playError);
                    cameraLoading.classList.add('hidden');
                    btnScan.disabled = false;
                }
            };

            if (video.readyState >= 2) {
                await video.play().catch(e => console.log("Autoplay di-bypass"));
                cameraLoading.classList.add('hidden');
                btnScan.disabled = false;
            }

        } catch (error) {
            console.error("Detail Error Sistem:", error);
            alert('Terjadi kesalahan teknis:\n' + error.message);
            tutupModalKamera();
        }
    }

    function tutupStreamSaja() {
        if (streamKamera) {
            streamKamera.getTracks().forEach(track => track.stop());
            streamKamera = null;
        }
        if (video) {
            video.pause();
            video.srcObject = null;
        }
    }

    function tutupModalKamera() {
        cameraModal.classList.add('hidden');
        cameraModal.classList.remove('flex');
        tutupStreamSaja();
    }

    // 4. Ekstraksi Koordinat Wajah
    btnScan.addEventListener('click', async () => {
        if (!modelTerbuka || !streamKamera) return;

        btnScan.disabled = true;
        btnScan.innerText = "Mengekstrak ciri wajah...";

        try {
            const deteksi = await faceapi.detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
                                         .withFaceLandmarks()
                                         .withFaceDescriptor();

            if (!deteksi) {
                alert("Wajah tidak terdeteksi!\n\nPastikan wajah tegak lurus ke depan, tidak memakai masker, dan berada di ruang yang cukup terang.");
                btnScan.disabled = false;
                btnScan.innerText = "Ambil Data Wajah";
                return;
            }

            const descriptorArray = Array.from(deteksi.descriptor);
            simpanWajah(descriptorArray);

        } catch (err) {
            console.error("Error saat ekstraksi wajah:", err);
            alert("Terjadi kesalahan sistem saat memproses struktur wajah.");
            btnScan.disabled = false;
            btnScan.innerText = "Ambil Data Wajah";
        }
    });

    // 5. Kirim data ke API Laravel via Fetch
    async function simpanWajah(descriptor) {
        try {
            const response = await fetch('/guru/wajah', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    descriptor: descriptor,
                    version: "face-api-v0.22"
                })
            });

            const hasil = await response.json();
            if (response.ok) {
                alert(hasil.message || "Data biometrik wajah berhasil diverifikasi dan disimpan!");
                tutupModalKamera();
                mainContent.classList.add('hidden');
                mainLoading.classList.remove('hidden');
                cekStatusWajah();
            } else {
                alert("Gagal memproses: " + (hasil.message || "Kesalahan Validasi"));
                btnScan.disabled = false;
                btnScan.innerText = "Ambil Data Wajah";
            }
        } catch (error) {
            alert("Gangguan jaringan/server. Silakan ulangi.");
            btnScan.disabled = false;
            btnScan.innerText = "Ambil Data Wajah";
        }
    }

    // 6. Hapus Data Wajah
    async function hapusWajah() {
        if (!confirm('Apakah Anda yakin ingin menghapus registrasi wajah Anda? Fitur presensi wajah tidak akan aktif sampai Anda melakukan registrasi ulang.')) return;

        try {
            const response = await fetch('/guru/wajah', {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken }
            });
            const hasil = await response.json();
            alert(hasil.message || "Data wajah berhasil dihapus dari sistem.");
            
            mainContent.classList.add('hidden');
            mainLoading.classList.remove('hidden');
            cekStatusWajah();
        } catch (error) {
            alert("Gagal menghapus data wajah.");
        }
    }

    document.getElementById('btn-close-modal').addEventListener('click', tutupModalKamera);
    document.getElementById('btn-cancel').addEventListener('click', tutupModalKamera);

    window.onclick = function(e) {
        if (e.target === cameraModal) tutupModalKamera();
    }

    window.onload = cekStatusWajah;
</script>
@endsection