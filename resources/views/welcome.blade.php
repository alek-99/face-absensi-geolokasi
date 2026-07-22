<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- PWA Setup --}}
    <meta name="theme-color" content="#2563eb">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SIAFI - Sistem Absensi Face ID</title>
    <!-- Vite (Tailwind CSS & JS) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] flex items-center justify-center min-h-screen p-6 font-['Instrument_Sans',sans-serif]">

    <div class="w-full max-w-md bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] p-8 rounded-2xl shadow-[0px_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0px_8px_30px_rgb(0,0,0,0.2)] flex flex-col items-center relative overflow-hidden group">
        
        <!-- Ornamen Latar Belakang (Aksen Modern) -->
        <div class="absolute -top-10 -right-10 w-32 h-32 bg-blue-500/10 rounded-full blur-2xl dark:bg-blue-500/5"></div>
        <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-emerald-500/10 rounded-full blur-2xl dark:bg-emerald-500/5"></div>

        <!-- Branding / Logo -->
        <div class="text-center z-10">
            <div class="mb-5 relative inline-block">
                <!-- Fallback Icon jika logo.png tidak ditemukan -->
                <div class="w-20 h-20 bg-blue-50 dark:bg-blue-950/30 text-blue-600 dark:text-blue-400 rounded-2xl flex items-center justify-center text-3xl mx-auto shadow-sm">
                    <i class="fa-solid fa-user-shield"></i>
                </div>
                <!-- Sembunyikan container icon di atas lewat JS jika gambar asli berhasil dimuat -->
                <img src="{{ asset('storage/images/logo.png') }}" alt="Logo SIAFI" class="absolute top-0 left-0 w-20 h-20 mx-auto object-contain bg-[#FDFDFC] dark:bg-[#0a0a0a] rounded-2xl" onerror="this.style.display='none'">
            </div>
            
            <h1 class="text-3xl font-bold tracking-tight text-[#1b1b18] dark:text-[#EDEDEC] mb-1">
                SIAFI
            </h1>
            <p class="text-sm font-medium uppercase tracking-wider text-blue-600 dark:text-blue-400 mb-2">
                Sistem Absensi Face ID
            </p>
            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] max-w-xs mx-auto">
                Silahkan masuk ke portal aplikasi atau unduh aplikasi mandiri di perangkat Anda.
            </p>
        </div>

        <!-- Garis Pemisah Eksklusif -->
        <hr class="w-full border-[#e3e3e0] dark:border-[#3E3E3A] my-6">

        <!-- Tombol Aksi Utama -->
        <div class="w-full space-y-3.5 z-10">
            <!-- Link Masuk Aplikasi -->
            <a href="{{ route('login') }}" 
               class="flex items-center justify-between w-full bg-[#1b1b18] hover:bg-black text-white dark:bg-[#eeeeec] dark:hover:bg-white dark:text-[#1C1C1A] px-5 py-3.5 rounded-xl text-sm font-semibold transition-all duration-200 transform active:scale-[0.98] shadow-md group">
                <span class="flex items-center gap-3">
                    <i class="fa-solid fa-right-to-bracket text-base opacity-90"></i>
                    Masuk ke Aplikasi
                </span>
                <i class="fa-solid fa-arrow-right text-xs transition-transform duration-200 group-hover:translate-x-1"></i>
            </a>

            <!-- Tombol Unduh / Install PWA -->
            <button id="installBtn" style="display:none"
                    class="flex items-center justify-between w-full bg-transparent border border-[#e3e3e0] dark:border-[#3E3E3A] hover:bg-gray-50 dark:hover:bg-zinc-900/50 text-[#1b1b18] dark:text-[#EDEDEC] px-5 py-3.5 rounded-xl text-sm font-medium transition-all duration-200 transform active:scale-[0.98]">
                <span class="flex items-center gap-3">
                    <i class="fa-solid fa-download text-base text-blue-500"></i>
                    Install Aplikasi SIAFI
                </span>
                <span class="text-[11px] font-semibold bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 px-2 py-0.5 rounded-md">PWA</span>
            </button>
        </div>

        <!-- Footer Layanan Bantuan -->
        <div class="mt-8 text-center w-full z-10">
            <a href="https://wa.me/6281389090873?text=Halo%20Admin%20SIAFI,%20saya%20butuh%20bantuan%20terkait%20login."
               target="_blank" rel="noopener noreferrer"
               class="text-xs text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] transition-colors inline-flex items-center gap-1.5 font-medium group">
                <i class="fa-brands fa-whatsapp text-emerald-500 text-sm transition-transform group-hover:scale-110"></i>
                Butuh bantuan? Hubungi Admin
            </a>
        </div>
        
    </div>

 <script>
    // 1. Registrasi Service Worker yang Lebih Stabil
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/sw.js')
                .then(reg => { 
                    console.log("Service Worker aktif:", reg.scope); 
                })
                .catch(err => { 
                    console.error("Service Worker gagal:", err); 
                });
        });
    }

    // 2. Logika Penangkapan Prompt Install
    let deferredPrompt;
    const installBtn = document.getElementById('installBtn');

    // Cek apakah aplikasi sudah berjalan dalam mode PWA/Terinstal
    const isPWA = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone;

    if (!isPWA) {
        window.addEventListener('beforeinstallprompt', (e) => {
            // Mencegah browser memunculkan mini-infobar bawaan otomatis
            e.preventDefault();
            
            // Simpan event agar bisa dipicu nanti via tombol kustom
            deferredPrompt = e;
            
            // Paksa tombol muncul di mobile dengan menghapus style inline hidden
            if (installBtn) {
                installBtn.style.setProperty('display', 'flex', 'important');
            }
        });
    }

    // 3. Eksekusi Saat Tombol Diklik
    if (installBtn) {
        installBtn.addEventListener('click', async () => {
            if (!deferredPrompt) {
                // Skenario cadangan jika prompt belum siap/tidak didukung (seperti iOS)
                alert('Untuk memasang aplikasi, klik menu titik tiga (atau tombol Share di iPhone) lalu pilih "Tambahkan ke Layar Utama".');
                return;
            }
            
            // Munculkan prompt instalasi bawaan OS
            deferredPrompt.prompt();
            
            // Tunggu respon dari pengguna (terima/tolak)
            const { outcome } = await deferredPrompt.userChoice;
            console.log(`User response to install prompt: ${outcome}`);
            
            if (outcome === 'accepted') {
                installBtn.style.display = 'none';
            }
            
            // Reset prompt karena hanya bisa digunakan sekali
            deferredPrompt = null;
        });
    }

    // 4. Sembunyikan tombol otomatis jika PWA sukses terinstal saat itu juga
    window.addEventListener('appinstalled', () => {
        console.log('SIAFI berhasil diinstal.');
        if (installBtn) installBtn.style.display = 'none';
        deferredPrompt = null;
    });
</script>

</body>
</html>