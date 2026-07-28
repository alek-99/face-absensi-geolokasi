<!DOCTYPE html>
<html lang="id">

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

<body class="bg-slate-50/70 antialiased text-slate-800 pb-20 lg:pb-0">

    <div class="min-h-screen flex flex-col lg:flex-row">

        <!-- BACKDROP SIDEBAR (Mobile Overlay) -->
        <div id="sidebar-backdrop" onclick="toggleSidebar()"
            class="fixed inset-0 z-40 hidden bg-slate-900/50 backdrop-blur-sm lg:hidden transition-opacity duration-300">
        </div>

        <!-- SIDEBAR (Mobile Drawers / Desktop Fixed) -->
        <aside id="sidebar"
            class="fixed inset-y-0 left-0 z-50 flex flex-col w-64 bg-white border-r border-slate-100 transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0">
            
            <!-- Header Sidebar -->
            <div class="flex items-center justify-between px-5 py-5 border-b border-slate-100 bg-gradient-to-b from-slate-50/50 to-white">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center p-1.5 border border-indigo-100/50 shadow-sm">
                        <img src="{{ asset('storage/images/logo.png') }}" alt="Logo SIAFI" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <span class="text-base font-black tracking-wider text-indigo-950 uppercase block leading-none">
                            SIAFI
                        </span>
                        <p class="text-[9px] font-bold text-slate-400 tracking-wider uppercase flex items-center gap-1 mt-1">
                            <i class="fa-solid fa-face-recognition text-indigo-500"></i> Smart Attendance
                        </p>
                    </div>
                </div>

                <!-- Tombol Close (Khusus Mobile) -->
                <button onclick="toggleSidebar()" aria-label="Tutup Menu"
                    class="w-8 h-8 flex items-center justify-center text-slate-400 rounded-lg hover:bg-slate-100 active:scale-95 transition-all lg:hidden">
                    <i class="fa-solid fa-xmark text-base"></i>
                </button>
            </div>

            <!-- Navigasi Menu -->
            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Main Menu</p>

                <a href="{{ route('guru.dashboard') }}"
                    class="flex items-center px-3 py-2.5 text-xs font-bold rounded-xl transition-all {{ request()->routeIs('guru.dashboard') ? 'text-white bg-indigo-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-indigo-600' }}">
                    <i class="fa-solid fa-house w-5 text-sm {{ request()->routeIs('guru.dashboard') ? 'text-white' : 'text-slate-400' }}"></i>
                    Dashboard
                </a>

                <a href="{{ route('profile.edit') }}"
                    class="flex items-center px-3 py-2.5 text-xs font-bold rounded-xl transition-all {{ request()->routeIs('guru.profile*') ? 'text-white bg-indigo-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-indigo-600' }}">
                    <i class="fa-solid fa-user-gear w-5 text-sm {{ request()->routeIs('guru.profile*') ? 'text-white' : 'text-slate-400' }}"></i>
                    Akun
                </a>

                <a href="{{ route('guru.absensi.index') }}"
                    class="flex items-center px-3 py-2.5 text-xs font-bold rounded-xl transition-all {{ request()->routeIs('guru.absensi.index') ? 'text-white bg-indigo-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-indigo-600' }}">
                    <i class="fa-solid fa-camera-retro w-5 text-sm {{ request()->routeIs('guru.absensi.index') ? 'text-white' : 'text-slate-400' }}"></i>
                    Absensi
                </a>

                <a href="{{ route('guru.absensi.riwayat') }}"
                    class="flex items-center px-3 py-2.5 text-xs font-bold rounded-xl transition-all {{ request()->routeIs('guru.absensi.riwayat') ? 'text-white bg-indigo-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-indigo-600' }}">
                    <i class="fa-solid fa-clock-rotate-left w-5 text-sm {{ request()->routeIs('guru.absensi.riwayat') ? 'text-white' : 'text-slate-400' }}"></i>
                    Rekap Absensi
                </a>

                <a href="{{ route('guru.absen-siswa.index') }}"
                    class="flex items-center px-3 py-2.5 text-xs font-bold rounded-xl transition-all {{ request()->routeIs('guru.absen-siswa*') ? 'text-white bg-indigo-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-indigo-600' }}">
                    <i class="fa-solid fa-calendar-week w-5 text-sm {{ request()->routeIs('guru.absen-siswa*') ? 'text-white' : 'text-slate-400' }}"></i>
                    Absensi Siswa
                </a>

                <a href="{{ route('guru.rekap-absen.index') }}"
                    class="flex items-center px-3 py-2.5 text-xs font-bold rounded-xl transition-all {{ request()->routeIs('guru.rekap-absen.index') ? 'text-white bg-indigo-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-indigo-600' }}">
                    <i class="fa-solid fa-clock-rotate-left w-5 text-sm {{ request()->routeIs('guru.rekap-absen.index') ? 'text-white' : 'text-slate-400' }}"></i>
                    Rekap Absensi Siswa
                </a>

                <a href="{{ route('guru.jadwal.index') }}"
                    class="flex items-center px-3 py-2.5 text-xs font-bold rounded-xl transition-all {{ request()->routeIs('guru.jadwal*') ? 'text-white bg-indigo-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-indigo-600' }}">
                    <i class="fa-solid fa-calendar-week w-5 text-sm {{ request()->routeIs('guru.jadwal*') ? 'text-white' : 'text-slate-400' }}"></i>
                    Jadwal Mengajar
                </a>

                <a href="{{ route('guru.wajah.halaman') }}"
                    class="flex items-center px-3 py-2.5 text-xs font-bold rounded-xl transition-all {{ request()->routeIs('guru.wajah*') ? 'text-white bg-indigo-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-indigo-600' }}">
                    <i class="fa-solid fa-user-shield w-5 text-sm {{ request()->routeIs('guru.wajah*') ? 'text-white' : 'text-slate-400' }}"></i>
                    Face ID
                </a>

                <div class="pt-3 mt-2 border-t border-slate-100">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="flex items-center px-3 py-2.5 text-xs font-bold text-rose-600 rounded-xl hover:bg-rose-50 transition-all w-full text-left">
                            <i class="fa-solid fa-arrow-right-from-bracket w-5 text-sm text-rose-500"></i>
                            Keluar Aplikasi
                        </button>
                    </form>
                </div>
            </nav>

            <!-- Profil User (Sidebar Footer) -->
            <div class="flex items-center px-4 py-3 border-t border-slate-100 bg-slate-50/50">
                <div class="flex items-center justify-center shrink-0 w-8 h-8 bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-lg text-white font-bold text-xs shadow-sm">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="ml-2.5 overflow-hidden">
                    <p class="text-xs font-bold text-slate-800 truncate leading-tight">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] text-slate-400 truncate mt-0.5">{{ Auth::user()->email }}</p>
                </div>
            </div>
        </aside>

        <!-- KONTEN UTAMA -->
        <div class="flex-1 flex flex-col min-w-0 lg:ml-64">

            <!-- TOPBAR -->
            <header class="bg-white/80 backdrop-blur-md px-4 sm:px-6 py-3 flex items-center justify-between border-b border-slate-100 sticky top-0 z-30">
                <!-- Sisi Kiri Header -->
                <div class="flex items-center gap-2">
                    <!-- Hamburger Menu Mobile -->
                    <button onclick="toggleSidebar()" aria-label="Buka Menu"
                        class="w-9 h-9 flex items-center justify-center text-slate-600 rounded-xl hover:bg-slate-100 active:scale-95 lg:hidden transition-all">
                        <i class="fa-solid fa-bars text-base"></i>
                    </button>

                    <!-- Title Halaman -->
                    <h1 class="text-xs sm:text-sm font-extrabold tracking-wide text-slate-800 uppercase">
                        @yield('page_title', 'Dashboard')
                    </h1>
                </div>

                <!-- Sisi Kanan Header -->
                <div class="flex items-center gap-1.5 sm:gap-3">
                    <!-- Bantuan -->
                    <a href="{{ route('panduan') }}" aria-label="Bantuan"
                        class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg active:scale-90 transition-all">
                        <i class="fa-solid fa-circle-question text-base"></i>
                    </a>

                    <!-- Notifikasi -->
                    @php
                        $unreadCount = \App\Models\NotifikasiGuru::where('guru_id', Auth::user()->guru?->id)
                            ->whereNull('read_at')
                            ->count();
                    @endphp
                    <a href="{{ route('guru.notifikasi.index') }}" aria-label="Notifikasi"
                        class="relative w-8 h-8 flex items-center justify-center text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-all">
                        <i class="fa-solid fa-bell text-base"></i>
                        @if ($unreadCount > 0)
                            <span class="absolute top-1 right-1 flex h-4 min-w-[16px] items-center justify-center rounded-full bg-rose-500 px-1 text-[9px] font-bold text-white ring-2 ring-white">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </a>

                    <div class="h-4 w-px bg-slate-200 mx-1 hidden sm:block"></div>

                    <!-- Informasi Profil Singkat -->
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-slate-700 hidden sm:block">{{ Auth::user()->name }}</span>
                        <div class="flex items-center justify-center w-7 h-7 bg-indigo-600 rounded-lg text-white font-extrabold text-[11px] shadow-sm">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- KONTEN HALAMAN -->
            <main class="p-4 sm:p-6 flex-1">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- BOTTOM NAVBAR (Navigasi Bawah Khusus Mobile) -->
    <div class="lg:hidden fixed bottom-0 left-0 right-0 z-40 px-3 pb-3 pt-1 bg-gradient-to-t from-slate-100 via-slate-100/80 to-transparent pointer-events-none">
        <nav class="pointer-events-auto max-w-md mx-auto bg-white/90 backdrop-blur-md rounded-2xl shadow-lg border border-slate-200/60 flex items-center justify-around py-2 px-1">
            
            <a href="{{ route('guru.dashboard') }}"
                class="flex flex-col items-center justify-center w-12 h-10 rounded-xl transition-all {{ request()->routeIs('guru.dashboard') ? 'text-indigo-600 font-extrabold' : 'text-slate-400 hover:text-slate-600' }}">
                <i class="fa-solid fa-house text-base"></i>
                <span class="text-[9px] mt-0.5">Home</span>
            </a>

            <a href="{{ route('guru.jadwal.index') }}"
                class="flex flex-col items-center justify-center w-12 h-10 rounded-xl transition-all {{ request()->routeIs('guru.jadwal*') ? 'text-indigo-600 font-extrabold' : 'text-slate-400 hover:text-slate-600' }}">
                <i class="fa-solid fa-calendar-week text-base"></i>
                <span class="text-[9px] mt-0.5">Jadwal</span>
            </a>

            <!-- Action Button Utama (Floating Action Button) -->
            <a href="{{ route('guru.absensi.index') }}"
                class="flex flex-col items-center justify-center w-12 h-12 rounded-2xl transition-all -translate-y-3 {{ request()->routeIs('guru.absensi.index') ? 'text-white bg-indigo-600 shadow-md shadow-indigo-300 ring-4 ring-white' : 'text-white bg-slate-900 shadow-md ring-4 ring-white' }}">
                <i class="fa-solid fa-face-recognition text-lg"></i>
                <span class="text-[8px] font-bold uppercase tracking-tight">Absen</span>
            </a>

            <a href="{{ route('guru.absensi.riwayat') }}"
                class="flex flex-col items-center justify-center w-12 h-10 rounded-xl transition-all {{ request()->routeIs('guru.absensi.riwayat') ? 'text-indigo-600 font-extrabold' : 'text-slate-400 hover:text-slate-600' }}">
                <i class="fa-solid fa-clock-rotate-left text-base"></i>
                <span class="text-[9px] mt-0.5">Riwayat</span>
            </a>

            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();"
                class="flex flex-col items-center justify-center w-12 h-10 rounded-xl text-rose-400 hover:text-rose-600 transition-all">
                <i class="fa-solid fa-arrow-right-from-bracket text-base"></i>
                <span class="text-[9px] mt-0.5">Keluar</span>
            </a>

            <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
        </nav>
    </div>

    <!-- SCRIPT INTERAKSI SIDEBAR -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');

            sidebar.classList.toggle('-translate-x-full');
            backdrop.classList.toggle('hidden');
        }
    </script>
</body>

</html>