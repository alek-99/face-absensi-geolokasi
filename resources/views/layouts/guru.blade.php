<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="vapid-public-key" content="{{ env('VAPID_PUBLIC_KEY') }}">

    <title>@yield('title', 'SMART FACE ABSENSI SMK')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="{{ asset('storage/images/logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
    @stack('styles')
</head>

<body class="bg-slate-50/80 antialiased text-slate-800 pb-20 lg:pb-0 min-h-full flex flex-col">

    <div class="min-h-screen flex flex-col lg:flex-row flex-1">

        <!-- BACKDROP SIDEBAR (Mobile Overlay) -->
        <div id="sidebar-backdrop" onclick="toggleSidebar()"
            class="fixed inset-0 z-40 hidden bg-slate-900/60 backdrop-blur-sm lg:hidden transition-opacity duration-300">
        </div>

        <!-- SIDEBAR (Hijau, Full-Bleed Nav Hover/Active) -->
        <aside id="sidebar"
            class="fixed inset-y-0 left-0 z-50 flex flex-col w-64 bg-emerald-800 text-white border-r border-emerald-900/20 shadow-xl transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0">
            
            <!-- Header Sidebar -->
            <div class="flex items-center justify-between px-5 py-5 border-b border-emerald-700/50 bg-emerald-900/30">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center border border-white/20 shadow-inner">
                        <img src="{{ asset('storage/images/logo.png') }}" alt="Logo SIAFI" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <span class="text-base font-black tracking-wider text-white uppercase block leading-none">
                            SIAFI
                        </span>
                        <p class="text-[9px] font-bold text-emerald-200 tracking-wider uppercase flex items-center gap-1 mt-1">
                            <i class="fa-solid fa-face-recognition text-emerald-300"></i> Sistem Absensi Face-ID
                        </p>
                    </div>
                </div>

                <!-- Tombol Close (Mobile) -->
                <button onclick="toggleSidebar()" aria-label="Tutup Menu"
                    class="w-8 h-8 flex items-center justify-center text-emerald-200 rounded-lg hover:bg-emerald-700/50 active:scale-95 transition-all lg:hidden">
                    <i class="fa-solid fa-xmark text-base"></i>
                </button>
            </div>

            <!-- Navigasi Menu (Full Width / Tanpa Margin Kiri Kanan) -->
            <nav class="flex-1 py-4 space-y-0.5 overflow-y-auto">
                <p class="px-5 text-[10px] font-bold text-emerald-300/70 uppercase tracking-widest mb-2">Main Menu</p>

                <!-- Dashboard -->
                <a href="{{ route('guru.dashboard') }}"
                    class="flex items-center gap-3 px-5 py-3 text-xs font-bold transition-all border-l-4 {{ request()->routeIs('guru.dashboard') ? 'bg-emerald-900/80 text-white border-emerald-400 shadow-inner' : 'text-emerald-100/80 hover:bg-emerald-700/60 hover:text-white border-transparent' }}">
                    <i class="fa-solid fa-house w-5 text-sm text-center {{ request()->routeIs('guru.dashboard') ? 'text-emerald-300' : 'text-emerald-200/70' }}"></i>
                    Dashboard
                </a>

                <!-- Akun -->
                <a href="{{ route('profile.edit') }}"
                    class="flex items-center gap-3 px-5 py-3 text-xs font-bold transition-all border-l-4 {{ request()->routeIs('guru.profile*') ? 'bg-emerald-900/80 text-white border-emerald-400 shadow-inner' : 'text-emerald-100/80 hover:bg-emerald-700/60 hover:text-white border-transparent' }}">
                    <i class="fa-solid fa-user-gear w-5 text-sm text-center {{ request()->routeIs('guru.profile*') ? 'text-emerald-300' : 'text-emerald-200/70' }}"></i>
                    Akun
                </a>

                <!-- Absensi -->
                <a href="{{ route('guru.absensi.index') }}"
                    class="flex items-center gap-3 px-5 py-3 text-xs font-bold transition-all border-l-4 {{ request()->routeIs('guru.absensi.index') ? 'bg-emerald-900/80 text-white border-emerald-400 shadow-inner' : 'text-emerald-100/80 hover:bg-emerald-700/60 hover:text-white border-transparent' }}">
                    <i class="fa-solid fa-camera-retro w-5 text-sm text-center {{ request()->routeIs('guru.absensi.index') ? 'text-emerald-300' : 'text-emerald-200/70' }}"></i>
                    Absensi
                </a>

                <!-- Rekap Absensi -->
                <a href="{{ route('guru.absensi.riwayat') }}"
                    class="flex items-center gap-3 px-5 py-3 text-xs font-bold transition-all border-l-4 {{ request()->routeIs('guru.absensi.riwayat') ? 'bg-emerald-900/80 text-white border-emerald-400 shadow-inner' : 'text-emerald-100/80 hover:bg-emerald-700/60 hover:text-white border-transparent' }}">
                    <i class="fa-solid fa-clock-rotate-left w-5 text-sm text-center {{ request()->routeIs('guru.absensi.riwayat') ? 'text-emerald-300' : 'text-emerald-200/70' }}"></i>
                    Rekap Absensi
                </a>

                <!-- Absensi Siswa -->
                <a href="{{ route('guru.absen-siswa.index') }}"
                    class="flex items-center gap-3 px-5 py-3 text-xs font-bold transition-all border-l-4 {{ request()->routeIs('guru.absen-siswa*') ? 'bg-emerald-900/80 text-white border-emerald-400 shadow-inner' : 'text-emerald-100/80 hover:bg-emerald-700/60 hover:text-white border-transparent' }}">
                    <i class="fa-solid fa-calendar-week w-5 text-sm text-center {{ request()->routeIs('guru.absen-siswa*') ? 'text-emerald-300' : 'text-emerald-200/70' }}"></i>
                    Absensi Siswa
                </a>

                <!-- Rekap Absensi Siswa -->
                <a href="{{ route('guru.rekap-absen.index') }}"
                    class="flex items-center gap-3 px-5 py-3 text-xs font-bold transition-all border-l-4 {{ request()->routeIs('guru.rekap-absen.index') ? 'bg-emerald-900/80 text-white border-emerald-400 shadow-inner' : 'text-emerald-100/80 hover:bg-emerald-700/60 hover:text-white border-transparent' }}">
                    <i class="fa-solid fa-clock-rotate-left w-5 text-sm text-center {{ request()->routeIs('guru.rekap-absen.index') ? 'text-emerald-300' : 'text-emerald-200/70' }}"></i>
                    Rekap Absensi Siswa
                </a>

                <!-- Jadwal Mengajar -->
                <a href="{{ route('guru.jadwal.index') }}"
                    class="flex items-center gap-3 px-5 py-3 text-xs font-bold transition-all border-l-4 {{ request()->routeIs('guru.jadwal*') ? 'bg-emerald-900/80 text-white border-emerald-400 shadow-inner' : 'text-emerald-100/80 hover:bg-emerald-700/60 hover:text-white border-transparent' }}">
                    <i class="fa-solid fa-calendar-week w-5 text-sm text-center {{ request()->routeIs('guru.jadwal*') ? 'text-emerald-300' : 'text-emerald-200/70' }}"></i>
                    Jadwal Mengajar
                </a>

                <!-- Face ID -->
                <a href="{{ route('guru.wajah.halaman') }}"
                    class="flex items-center gap-3 px-5 py-3 text-xs font-bold transition-all border-l-4 {{ request()->routeIs('guru.wajah*') ? 'bg-emerald-900/80 text-white border-emerald-400 shadow-inner' : 'text-emerald-100/80 hover:bg-emerald-700/60 hover:text-white border-transparent' }}">
                    <i class="fa-solid fa-user-shield w-5 text-sm text-center {{ request()->routeIs('guru.wajah*') ? 'text-emerald-300' : 'text-emerald-200/70' }}"></i>
                    Face ID
                </a>

                <!-- Keluar Aplikasi -->
                <div class="pt-3 mt-3 border-t border-emerald-700/40">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="flex items-center gap-3 px-5 py-3 text-xs font-bold text-rose-200 hover:bg-rose-900/30 hover:text-rose-100 transition-all w-full text-left border-l-4 border-transparent">
                            <i class="fa-solid fa-arrow-right-from-bracket w-5 text-sm text-center text-rose-300"></i>
                            Keluar Aplikasi
                        </button>
                    </form>
                </div>
            </nav>

            <!-- Profil User (Sidebar Footer) -->
            <div class="flex items-center px-5 py-4 border-t border-emerald-700/50 bg-emerald-900/40">
                <div class="flex items-center justify-center shrink-0 w-8 h-8 bg-emerald-500 text-white font-bold text-xs rounded-lg shadow-sm border border-emerald-400/30">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="ml-3 overflow-hidden">
                    <p class="text-xs font-bold text-white truncate leading-tight">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] text-emerald-200/70 truncate mt-0.5">{{ Auth::user()->email }}</p>
                </div>
            </div>
        </aside>

        <!-- KONTEN UTAMA -->
        <div class="flex-1 flex flex-col min-w-0 lg:ml-64 relative min-h-screen">

            <!-- TOPBAR -->
            <header class="bg-white/80 backdrop-blur-md px-4 sm:px-6 py-3 flex items-center justify-between border-b border-slate-200/80 sticky top-0 z-30 shadow-sm">
                <!-- Sisi Kiri Header -->
                <div class="flex items-center gap-3">
                    <button onclick="toggleSidebar()" aria-label="Buka Menu"
                        class="w-9 h-9 flex items-center justify-center text-emerald-800 rounded-xl hover:bg-emerald-50 active:scale-95 lg:hidden transition-all border border-emerald-100">
                        <i class="fa-solid fa-bars text-base"></i>
                    </button>

                    <h1 class="text-xs sm:text-sm font-extrabold tracking-wide text-slate-800 uppercase">
                        @yield('page_title', 'Dashboard')
                    </h1>
                </div>

                <!-- Sisi Kanan Header -->
                <div class="flex items-center gap-1.5 sm:gap-3">
                    <a href="{{ route('panduan') }}" aria-label="Bantuan"
                        class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg active:scale-90 transition-all">
                        <i class="fa-solid fa-circle-question text-base"></i>
                    </a>

                    @php
                        $unreadCount = \App\Models\NotifikasiGuru::where('guru_id', Auth::user()->guru?->id)
                            ->whereNull('read_at')
                            ->count();
                    @endphp
                    <a href="{{ route('guru.notifikasi.index') }}" aria-label="Notifikasi"
                        class="relative w-8 h-8 flex items-center justify-center text-slate-400 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition-all">
                        <i class="fa-solid fa-bell text-base"></i>
                        @if ($unreadCount > 0)
                            <span class="absolute top-1 right-1 flex h-4 min-w-[16px] items-center justify-center rounded-full bg-rose-500 px-1 text-[9px] font-bold text-white ring-2 ring-white">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </a>

                    <div class="h-4 w-px bg-slate-200 mx-1 hidden sm:block"></div>

                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-slate-700 hidden sm:block">{{ Auth::user()->name }}</span>
                        <div class="flex items-center justify-center w-7 h-7 bg-emerald-700 rounded-lg text-white font-extrabold text-[11px] shadow-sm">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- AREA KONTEN DENGAN WATERMARK OVERLAY -->
            <div class="relative flex-1 flex flex-col">
                <!-- Watermark Logo Overlay -->
                <div class="fixed inset-0 lg:left-64 top-14 pointer-events-none z-0 flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('storage/images/logo.png') }}" 
                         alt="Watermark Logo" 
                         class="w-[280px] sm:w-[420px] md:w-[500px] object-contain opacity-[0.035] filter grayscale select-none">
                </div>

                <!-- Konten Halaman Sebenarnya -->
                <main class="p-4 sm:p-6 flex-1 relative z-10">
                    @yield('content')
                </main>
            </div>
        </div>
    </div>

    <!-- BOTTOM NAVBAR (Khusus Mobile) -->
    <div class="lg:hidden fixed bottom-0 left-0 right-0 z-40 px-3 pb-3 pt-1 bg-gradient-to-t from-slate-100 via-slate-100/80 to-transparent pointer-events-none">
        <nav class="pointer-events-auto max-w-md mx-auto bg-white/90 backdrop-blur-md rounded-2xl shadow-xl border border-slate-200/80 flex items-center justify-around py-2 px-1">
            
            <a href="{{ route('guru.dashboard') }}"
                class="flex flex-col items-center justify-center w-12 h-10 rounded-xl transition-all {{ request()->routeIs('guru.dashboard') ? 'text-emerald-700 font-extrabold' : 'text-slate-400 hover:text-slate-600' }}">
                <i class="fa-solid fa-house text-base"></i>
                <span class="text-[9px] mt-0.5">Home</span>
            </a>

            <a href="{{ route('guru.jadwal.index') }}"
                class="flex flex-col items-center justify-center w-12 h-10 rounded-xl transition-all {{ request()->routeIs('guru.jadwal*') ? 'text-emerald-700 font-extrabold' : 'text-slate-400 hover:text-slate-600' }}">
                <i class="fa-solid fa-calendar-week text-base"></i>
                <span class="text-[9px] mt-0.5">Jadwal</span>
            </a>

            <!-- Floating Action Button (Absen) -->
            <a href="{{ route('guru.absensi.index') }}"
                class="flex flex-col items-center justify-center w-12 h-12 rounded-2xl transition-all -translate-y-3 {{ request()->routeIs('guru.absensi.index') ? 'text-white bg-emerald-600 shadow-md shadow-emerald-200 ring-4 ring-white' : 'text-white bg-emerald-900 shadow-md ring-4 ring-white' }}">
                <i class="fa-solid fa-face-recognition text-lg"></i>
                <span class="text-[8px] font-bold uppercase tracking-tight">Absen</span>
            </a>

            <a href="{{ route('guru.absensi.riwayat') }}"
                class="flex flex-col items-center justify-center w-12 h-10 rounded-xl transition-all {{ request()->routeIs('guru.absensi.riwayat') ? 'text-emerald-700 font-extrabold' : 'text-slate-400 hover:text-slate-600' }}">
                <i class="fa-solid fa-clock-rotate-left text-base"></i>
                <span class="text-[9px] mt-0.5">Riwayat</span>
            </a>

            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();"
                class="flex flex-col items-center justify-center w-12 h-10 rounded-xl text-rose-500 hover:text-rose-700 transition-all">
                <i class="fa-solid fa-arrow-right-from-bracket text-base"></i>
                <span class="text-[9px] mt-0.5">Keluar</span>
            </a>

            <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
        </nav>
    </div>

    <!-- SCRIPT INTERAKSI SIDEBAR & WEB PUSH REGISTRATION -->
  <script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const backdrop = document.getElementById('sidebar-backdrop');

        sidebar.classList.toggle('-translate-x-full');
        backdrop.classList.toggle('hidden');
    }

    // SCRIPT REGISTRASI WEB PUSH SUBSCRIPTION
    document.addEventListener("DOMContentLoaded", async () => {
        if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
            return;
        }

        try {
            // 1. Register Service Worker
            const registration = await navigator.serviceWorker.register('/sw.js');

            // 2. Minta Izin Notifikasi
            const permission = await Notification.requestPermission();
            if (permission !== 'granted') {
                return;
            }

            // 3. Ambil VAPID Public Key dari Meta Tag
            const vapidMeta = document.querySelector('meta[name="vapid-public-key"]');
            const vapidPublicKey = vapidMeta ? vapidMeta.getAttribute('content') : null;

            if (!vapidPublicKey) {
                console.error('Push Subscription Gagal: VAPID_PUBLIC_KEY belum di-set di meta tag/env');
                return;
            }

            // 4. Lakukan Subscribe
            let subscription = await registration.pushManager.getSubscription();
            if (!subscription) {
                subscription = await registration.pushManager.subscribe({
                    userVisibleOnly: true,
                    applicationServerKey: urlBase64ToUint8Array(vapidPublicKey)
                });
            }

            // 5. Format Payload Subscription
            const subData = subscription.toJSON();
            subData.contentEncoding = (PushManager.supportedContentEncodings || ['aes128gcm'])[0];

            // Helper untuk UUID Perangkat
            let deviceId = localStorage.getItem('guru_device_uuid');
            if (!deviceId) {
                deviceId = 'dev-' + Math.random().toString(36).substring(2, 11) + '-' + Date.now();
                localStorage.setItem('guru_device_uuid', deviceId);
            }

            // 6. Kirim Data Subscription ke Server
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            const targetUrl = "{{ route('guru.push-subscription.save') }}";

            const response = await fetch(targetUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfMeta ? csrfMeta.getAttribute('content') : ''
                },
                redirect: 'follow', // Pastikan penanganan redirect yang aman
                body: JSON.stringify({
                    subscription: subData,
                    device_id: deviceId,
                    nama_perangkat: navigator.userAgent
                })
            });

            const resData = await response.json();
            console.log('Push Subscription Response:', resData);

        } catch (error) {
            console.error('Error saat mendaftarkan Web Push:', error);
        }
    });

    // Helper fungsi konversi Base64 VAPID Key
    function urlBase64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - base64String.length % 4) % 4);
        const base64 = (base64String + padding).replace(/\-/g, '+').replace(/_/g, '/');
        const rawData = window.atob(base64);
        const outputArray = new Uint8Array(rawData.length);
        for (let i = 0; i < rawData.length; ++i) {
            outputArray[i] = rawData.charCodeAt(i);
        }
        return outputArray;
    }
</script>
</body>

</html>