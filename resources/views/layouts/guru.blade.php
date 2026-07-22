<!DOCTYPE html>
<html lang="id">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SMART FACE ABSENSI SMK')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" href="{{ asset('storage/images/logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* Mencegah konten terbawah tertutup oleh bottom navbar pada perangkat mobile */
        @media (max-width: 767px) {
            body {
                padding-bottom: 76px;
            }
        }
    </style>
    @stack('styles')
</head>

<body class="bg-gray-50/60 antialiased text-slate-800">
    <!-- VAPID Public Key for Push Subscription -->
    <meta name="vapid-public-key" content="{{ env('VAPID_PUBLIC_KEY') }}">

    <div class="flex min-h-screen">

        <!-- SIDEBAR BACKDROP (Mobile Only) -->
        <div id="sidebar-backdrop" onclick="toggleSidebar()" class="fixed inset-0 z-40 hidden bg-slate-900/40 backdrop-blur-sm md:hidden transition-all"></div>

        <!-- SIDEBAR -->
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 flex flex-col w-64 h-screen bg-white border-r border-slate-100 transition-transform duration-300 ease-in-out -translate-x-full md:translate-x-0">
            <!-- Sidebar Header -->
            <div class="flex flex-col items-center px-6 py-6 border-b border-slate-50 relative bg-gradient-to-b from-slate-50/50 to-white">
                <div class="flex flex-col items-center text-center">
                    <div class="w-14 h-14 bg-indigo-50 rounded-2xl flex items-center justify-center p-2 mb-3 shadow-sm shadow-indigo-50/50 border border-indigo-100/40">
                        <img src="{{ asset('storage/images/logo.png') }}" alt="Logo SIAFI" class="w-full h-full object-contain drop-shadow-sm">
                    </div>

                    <span class="text-lg font-black tracking-widest text-indigo-900 uppercase">
                        SIAFI
                    </span>

                    <p class="text-[10px] font-bold text-slate-400 tracking-wider mt-0.5 uppercase flex items-center gap-1">
                        <i class="fa-solid fa-face-recognition text-indigo-500"></i> Smart Attendance
                    </p>
                </div>

                <!-- Close Button (Mobile Only) -->
                <button onclick="toggleSidebar()" class="absolute top-4 right-4 w-7 h-7 flex items-center justify-center text-slate-400 rounded-full hover:bg-slate-50 active:scale-95 transition-all md:hidden">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <!-- Sidebar Navigation (Desktop) -->
            <nav class="flex-1 px-4 py-5 space-y-1.5 overflow-y-auto">
                <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Main Menu</p>

                <a href="{{ route('guru.dashboard') }}" class="flex items-center px-3.5 py-2.5 text-xs font-bold rounded-xl transition-all {{ request()->routeIs('guru.dashboard') ? 'text-white bg-indigo-600 shadow-md shadow-indigo-100' : 'text-slate-600 hover:bg-slate-50 hover:text-indigo-600' }}">
                    <i class="fa-solid fa-house w-5 mr-2 text-sm {{ request()->routeIs('guru.dashboard') ? 'text-white' : 'text-slate-400' }}"></i>
                    Dashboard
                </a>

                <a href="{{ route('profile.edit') }}" class="flex items-center px-3.5 py-2.5 text-xs font-bold rounded-xl transition-all {{ request()->routeIs('guru.profile*') ? 'text-white bg-indigo-600 shadow-md shadow-indigo-100' : 'text-slate-600 hover:bg-slate-50 hover:text-indigo-600' }}">
                    <i class="fa-solid fa-user-gear w-5 mr-2 text-sm {{ request()->routeIs('guru.profile*') ? 'text-white' : 'text-slate-400' }}"></i>
                    Akun
                </a>

                <a href="{{ route('guru.absensi.index') }}" class="flex items-center px-3.5 py-2.5 text-xs font-bold rounded-xl transition-all {{ request()->routeIs('guru.absensi.index') ? 'text-white bg-indigo-600 shadow-md shadow-indigo-100' : 'text-slate-600 hover:bg-slate-50 hover:text-indigo-600' }}">
                    <i class="fa-solid fa-camera-retro w-5 mr-2 text-sm {{ request()->routeIs('guru.absensi.index') ? 'text-white' : 'text-slate-400' }}"></i>
                    Absensi
                </a>

                <a href="{{ route('guru.absensi.riwayat') }}" class="flex items-center px-3.5 py-2.5 text-xs font-bold rounded-xl transition-all {{ request()->routeIs('guru.absensi.riwayat') ? 'text-white bg-indigo-600 shadow-md shadow-indigo-100' : 'text-slate-600 hover:bg-slate-50 hover:text-indigo-600' }}">
                    <i class="fa-solid fa-clock-rotate-left w-5 mr-2 text-sm {{ request()->routeIs('guru.absensi.riwayat') ? 'text-white' : 'text-slate-400' }}"></i>
                    Rekap Absensi
                </a>

                <a href="{{ route('guru.jadwal.index') }}" class="flex items-center px-3.5 py-2.5 text-xs font-bold rounded-xl transition-all {{ request()->routeIs('guru.jadwal*') ? 'text-white bg-indigo-600 shadow-md shadow-indigo-100' : 'text-slate-600 hover:bg-slate-50 hover:text-indigo-600' }}">
                    <i class="fa-solid fa-calendar-week w-5 mr-2 text-sm {{ request()->routeIs('guru.jadwal*') ? 'text-white' : 'text-slate-400' }}"></i>
                    Jadwal Mengajar
                </a>

                <a href="{{ route('guru.wajah.halaman') }}" class="flex items-center px-3.5 py-2.5 text-xs font-bold rounded-xl transition-all {{ request()->routeIs('guru.wajah*') ? 'text-white bg-indigo-600 shadow-md shadow-indigo-100' : 'text-slate-600 hover:bg-slate-50 hover:text-indigo-600' }}">
                    <i class="fa-solid fa-user-shield w-5 mr-2 text-sm {{ request()->routeIs('guru.wajah*') ? 'text-white' : 'text-slate-400' }}"></i>
                    Face ID
                </a>

                <!-- Logout Button Area -->
                <div class="pt-4 border-t border-slate-100">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="flex items-center px-3.5 py-2.5 text-xs font-bold text-rose-600 rounded-xl hover:bg-rose-50 hover:text-rose-700 transition-all w-full text-left">
                            <i class="fa-solid fa-arrow-right-from-bracket w-5 mr-2 text-sm text-rose-400"></i>Keluar Aplikasi
                        </button>
                    </form>
                </div>
            </nav>

            <!-- Sidebar User Info Footer -->
            <div class="flex items-center px-4 py-3.5 border-t border-slate-50 bg-slate-50/50">
                <div class="flex items-center justify-center flex-shrink-0 w-9 h-9 bg-gradient-to-br from-amber-400 to-amber-500 rounded-xl text-white font-bold shadow-sm shadow-amber-200 text-xs border border-white">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="ml-3 overflow-hidden">
                    <p class="text-xs font-bold text-slate-700 truncate leading-tight">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] text-slate-400 truncate mt-0.5">{{ Auth::user()->email }}</p>
                </div>
            </div>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <div class="flex-1 min-h-screen w-full md:ml-64 flex flex-col">

            <!-- TOPBAR -->
            <header class="bg-white/80 backdrop-blur-md px-4 md:px-6 py-3.5 flex justify-between items-center border-b border-slate-100 sticky top-0 z-30">
                <!-- Left Side: Logo, Help Button, and Page Title -->
                <div class="flex items-center space-x-2.5">
                    <button onclick="toggleSidebar()" class="w-8 h-8 flex items-center justify-center text-slate-600 rounded-xl hover:bg-slate-50 focus:outline-none md:hidden transition-all">
                        <i class="fa-solid fa-bars text-base"></i>
                    </button>

                    <img src="{{ asset('storage/images/logo.png') }}" alt="Logo" class="w-6 h-6 object-contain md:block hidden">

                    <!-- Tombol Tanda Tanya (Bantuan) -->
                    <a href="{{ route('panduan') }}" class="w-7 h-7 flex items-center justify-center text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-50 active:scale-90 transition-all" title="Bantuan">
                        <i class="fa-solid fa-circle-question text-sm"></i>
                    </a>

                    <h1 class="text-xs font-extrabold tracking-wide text-slate-800 border-l pl-2.5 border-slate-200 uppercase">
                        @yield('page_title', 'Dashboard')
                    </h1>
                </div>

                <!-- Right Side: Notification & Profile Info -->
                <div class="flex items-center space-x-4">
                    <!-- Tombol Bel Notifikasi (Dengan Indikator Titik Merah) -->
                    @php
                        $unreadCount = \App\Models\NotifikasiGuru::where('guru_id', Auth::user()->guru?->id)
                            ->whereNull('read_at')
                            ->count();
                    @endphp

                    <a href="{{ route('guru.notifikasi.index') }}" class="relative w-8 h-8 flex items-center justify-center text-slate-400 hover:text-slate-600 hover:bg-slate-50 rounded-lg transition-all" title="Notifikasi ({{ $unreadCount }} Belum Dibaca)">
                        <i class="fa-solid fa-bell text-sm"></i>

                        <!-- Dot merah penanda hanya muncul jika ada notifikasi baru -->
                        @if ($unreadCount > 0)
                            <span class="absolute -top-1 -right-1 flex h-4 min-w-4 items-center justify-center rounded-full bg-rose-500 px-1 text-[9px] font-bold text-white ring-2 ring-white">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </a>

                    <!-- Profile Info -->
                    <div class="flex items-center space-x-2.5">
                        <span class="text-xs font-bold text-slate-600 hidden sm:block">{{ Auth::user()->name }}</span>
                        <div class="flex items-center justify-center w-7 h-7 bg-gradient-to-br from-indigo-500 to-violet-600 rounded-lg text-white font-extrabold text-[11px] shadow-sm shadow-indigo-100">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Yield Content -->
            <main class="p-4 md:p-6 flex-1 bg-slate-50/50">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- PREMIUM BOTTOM NAVBAR (Mobile Only) -->
    <div class="md:hidden fixed bottom-0 left-0 right-0 z-40 px-4 pb-4 bg-gradient-to-t from-gray-50 via-gray-50/80 to-transparent pointer-events-none">
        <nav class="pointer-events-auto max-w-md mx-auto bg-white/90 backdrop-blur-lg rounded-2xl shadow-xl shadow-slate-200/80 border border-slate-100/80 flex justify-around items-center py-2.5 px-2">

            <a href="{{ route('guru.dashboard') }}" class="flex flex-col items-center justify-center w-12 h-12 rounded-xl transition-all {{ request()->routeIs('guru.dashboard') ? 'text-indigo-600 bg-indigo-50/70' : 'text-slate-400 hover:text-slate-600' }}">
                <i class="fa-solid fa-house text-base"></i>
                <span class="text-[9px] font-bold mt-1 tracking-tight">Home</span>
            </a>

            <a href="{{ route('guru.jadwal.index') }}" class="flex flex-col items-center justify-center w-12 h-12 rounded-xl transition-all {{ request()->routeIs('guru.jadwal*') ? 'text-indigo-600 bg-indigo-50/70' : 'text-slate-400 hover:text-slate-600' }}">
                <i class="fa-solid fa-calendar-week text-base"></i>
                <span class="text-[9px] font-bold mt-1 tracking-tight">Jadwal</span>
            </a>

            <!-- Center Target Action: Absensi Face ID -->
            <a href="{{ route('guru.absensi.index') }}" class="flex flex-col items-center justify-center w-14 h-14 rounded-2xl transition-all -translate-y-2 {{ request()->routeIs('guru.absensi.index') ? 'text-white bg-gradient-to-br from-indigo-600 to-violet-600 shadow-md shadow-indigo-200' : 'text-white bg-slate-800 shadow-md shadow-slate-900/10' }}">
                <i class="fa-solid fa-face-recognition text-lg"></i>
                <span class="text-[8px] font-extrabold mt-0.5 uppercase tracking-wide">Absen</span>
            </a>

            <a href="{{ route('guru.absensi.riwayat') }}" class="flex flex-col items-center justify-center w-12 h-12 rounded-xl transition-all {{ request()->routeIs('guru.absensi.riwayat') ? 'text-indigo-600 bg-indigo-50/70' : 'text-slate-400 hover:text-slate-600' }}">
                <i class="fa-solid fa-clock-rotate-left text-base"></i>
                <span class="text-[9px] font-bold mt-1 tracking-tight">Riwayat</span>
            </a>

            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();" class="flex flex-col items-center justify-center w-12 h-12 rounded-xl text-rose-400 hover:text-rose-600 hover:bg-rose-50/50 transition-all">
                <i class="fa-solid fa-arrow-right-from-bracket text-base"></i>
                <span class="text-[9px] font-bold mt-1 tracking-tight">Keluar</span>
            </a>

            <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
        </nav>
    </div>

    <!-- SIDEBAR INTERACTION SCRIPT -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');

            sidebar.classList.toggle('-translate-x-full');
            backdrop.classList.toggle('hidden');
        }
    </script>

    <!-- PWA / Push registration script -->
    @stack('scripts')
</body>

</html>
