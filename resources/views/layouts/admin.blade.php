<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard - Admin')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="{{ asset('storage/images/logo.png') }}">

    <!-- Font Awesome CDN via Cloudflare -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght=300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Montserrat', sans-serif;
        }

        /* Custom scrollbar untuk sidebar & main content (Light Theme) */
        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>

<body class="h-full text-slate-700 overflow-hidden antialiased bg-slate-50">

    <div class="flex h-screen overflow-hidden relative">

        <!-- Overlay Mobile -->
        <div id="sidebar-overlay" class="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-sm hidden md:hidden transition-opacity duration-300"></div>

        <!-- SIDEBAR -->
        <aside id="sidebar"
            class="fixed md:static inset-y-0 left-0 w-64 bg-white flex-shrink-0 flex flex-col justify-between border-r border-slate-200 z-50 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out shadow-sm">
            
            <div class="flex flex-col flex-grow pt-5 overflow-y-auto custom-scrollbar">

                <!-- Brand / Logo -->
                <div class="flex items-center justify-between px-6 mb-6">
                    <div class="flex flex-col items-center gap-3 w-full text-center relative">
                        
                        <button id="close-sidebar" class="absolute right-0 top-0 text-slate-400 hover:text-slate-600 md:hidden focus:outline-none">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>

                        <div class="p-2 rounded-xl text-indigo-600 shadow-md shadow-indigo-100 bg-indigo-50 w-20 h-20 flex items-center justify-center border border-indigo-100">
                            <img src="{{ asset('storage/images/logo.png') }}" class="w-full h-full object-contain" alt="Logo SIAFI" />
                        </div>

                        <h2 class="text-2xl font-extrabold text-slate-800 tracking-wider">SIAFI</h2>
                        <span class="text-[10px] font-semibold text-slate-400 tracking-wide uppercase">
                            Sistem Absensi Face-ID
                        </span>
                    </div>
                </div>

                <!-- Navigasi Menu -->
                <nav class="flex-1 px-4 space-y-1 bg-white pb-6">
                    <!-- Section: Core -->
                    <div class="px-3 mb-2 text-xs font-bold text-slate-400 uppercase tracking-wider">
                        Core
                    </div>

                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 group 
                       {{ request()->routeIs('admin.dashboard') ? 'text-white bg-indigo-600 shadow-md shadow-indigo-200' : 'text-slate-600 hover:text-indigo-600 hover:bg-indigo-50' }}">
                        <i class="fa-solid fa-chart-pie mr-4 text-base transition-colors {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-indigo-600' }}"></i>
                        Dashboard
                    </a>

                    <!-- Absensi -->
                    <a href="{{ route('admin.absensi.index') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 group 
                       {{ request()->routeIs('admin.absensi.index*') ? 'text-white bg-indigo-600 shadow-md shadow-indigo-200' : 'text-slate-600 hover:text-indigo-600 hover:bg-indigo-50' }}">
                        <i class="fa-solid fa-calendar-check mr-4 text-base transition-colors {{ request()->routeIs('admin.absensi.index*') ? 'text-white' : 'text-slate-400 group-hover:text-indigo-600' }}"></i>
                        Absensi
                    </a>

                    <!-- Pengajuan Absensi -->
                    <a href="{{ route('admin.absensi.pengajuan') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 group 
                       {{ request()->routeIs('admin.absensi.pengajuan*') ? 'text-white bg-indigo-600 shadow-md shadow-indigo-200' : 'text-slate-600 hover:text-indigo-600 hover:bg-indigo-50' }}">
                        <i class="fa-solid fa-envelope-open-text mr-4 text-base transition-colors {{ request()->routeIs('admin.absensi.pengajuan*') ? 'text-white' : 'text-slate-400 group-hover:text-indigo-600' }}"></i>
                        Pengajuan Absensi
                    </a>

                    <!-- Section: Master Data -->
                    <div class="pt-4 px-3 mb-2 text-xs font-bold text-slate-400 uppercase tracking-wider">
                        Master Data
                    </div>

                    <!-- Data Guru -->
                    <a href="{{ route('admin.guru.index') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 group 
                       {{ request()->routeIs('admin.guru.*') ? 'text-white bg-indigo-600 shadow-md shadow-indigo-200' : 'text-slate-600 hover:text-indigo-600 hover:bg-indigo-50' }}">
                        <i class="fa-solid fa-chalkboard-user mr-4 text-base transition-colors {{ request()->routeIs('admin.guru.*') ? 'text-white' : 'text-slate-400 group-hover:text-indigo-600' }}"></i>
                        Data Guru
                    </a>

                    <!-- Data Kelas -->
                    <a href="{{ route('admin.kelas.index') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 group 
                       {{ request()->routeIs('admin.kelas.*') ? 'text-white bg-indigo-600 shadow-md shadow-indigo-200' : 'text-slate-600 hover:text-indigo-600 hover:bg-indigo-50' }}">
                        <i class="fa-solid fa-school mr-4 text-base transition-colors {{ request()->routeIs('admin.kelas.*') ? 'text-white' : 'text-slate-400 group-hover:text-indigo-600' }}"></i>
                        Data Kelas
                    </a>

                    <!-- Data Siswa  -->
                    <a href="{{ route('admin.siswa.index') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 group 
                       {{ request()->routeIs('admin.siswa.*') ? 'text-white bg-indigo-600 shadow-md shadow-indigo-200' : 'text-slate-600 hover:text-indigo-600 hover:bg-indigo-50' }}">
                        <i class="fa-solid fa-user-graduate mr-4 text-base transition-colors {{ request()->routeIs('admin.siswa.*') ? 'text-white' : 'text-slate-400 group-hover:text-indigo-600' }}"></i>
                        Data Siswa
                    </a>

                    <!-- Mata Pelajaran  -->
                    <a href="{{ route('admin.mapel.index') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 group 
                       {{ request()->routeIs('admin.mapel.*') ? 'text-white bg-indigo-600 shadow-md shadow-indigo-200' : 'text-slate-600 hover:text-indigo-600 hover:bg-indigo-50' }}">
                        <i class="fa-solid fa-book-bookmark mr-4 text-base transition-colors {{ request()->routeIs('admin.mapel.*') ? 'text-white' : 'text-slate-400 group-hover:text-indigo-600' }}"></i>
                        Mata Pelajaran
                    </a>

                    <!-- Jadwal -->
                    <a href="{{ route('admin.jadwal.index') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 group 
                       {{ request()->routeIs('admin.jadwal.*') ? 'text-white bg-indigo-600 shadow-md shadow-indigo-200' : 'text-slate-600 hover:text-indigo-600 hover:bg-indigo-50' }}">
                        <i class="fa-solid fa-calendar-days mr-4 text-base transition-colors {{ request()->routeIs('admin.jadwal.*') ? 'text-white' : 'text-slate-400 group-hover:text-indigo-600' }}"></i>
                        Jadwal Pelajaran
                    </a>

                    <!-- Section: Settings -->
                    <div class="pt-4 px-3 mb-2 text-xs font-bold text-slate-400 uppercase tracking-wider">
                        User & Settings
                    </div>

                    <!-- Profil -->
                    <a href="{{ route('admin.sekolah.index') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 group 
                       {{ request()->routeIs('admin.sekolah.*') ? 'text-white bg-indigo-600 shadow-md shadow-indigo-200' : 'text-slate-600 hover:text-indigo-600 hover:bg-indigo-50' }}">
                        <i class="fa-solid fa-user-gear mr-4 text-base transition-colors {{ request()->routeIs('admin.sekolah.*') ? 'text-white' : 'text-slate-400 group-hover:text-indigo-600' }}"></i>
                        Profil Sekolah
                    </a>

                    <!-- Pengaturan Sistem -->
                    <a href="{{ route('admin.pengaturan.index') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 group 
                       {{ request()->routeIs('admin.pengaturan.*') ? 'text-white bg-indigo-600 shadow-md shadow-indigo-200' : 'text-slate-600 hover:text-indigo-600 hover:bg-indigo-50' }}">
                        <i class="fa-solid fa-sliders mr-4 text-base transition-colors {{ request()->routeIs('admin.pengaturan.*') ? 'text-white' : 'text-slate-400 group-hover:text-indigo-600' }}"></i>
                        Pengaturan Sistem
                    </a>
                </nav>
            </div>

            <!-- Footer Sidebar Admin Info -->
            <div class="flex-shrink-0 hidden md:flex border-t border-slate-100 p-4 bg-slate-50 justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-indigo-100 text-xs font-bold text-indigo-600 ring-2 ring-indigo-500/20">
                        {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 2)) }}
                    </div>
                    <div class="ml-1">
                        <p class="text-xs font-semibold text-slate-800 leading-tight">{{ Auth::user()->name ?? 'Admin' }}</p>
                        <p class="text-[10px] text-slate-500 font-medium">
                            {{ Auth::user()->role ?? 'Administrator' }}
                        </p>
                    </div>
                </div>
                <button onclick="document.getElementById('logout-form').submit();" class="text-slate-400 hover:text-rose-600 hover:bg-rose-50 p-2 rounded-lg transition-colors" title="Logout">
                    <i class="fa-solid fa-power-off text-base"></i>
                </button>
            </div>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <div class="flex flex-col w-0 flex-1 overflow-hidden">

            <!-- HEADER -->
            <header class="relative z-10 flex-shrink-0 flex h-16 bg-white shadow-sm mx-6 mt-4 rounded-xl border border-slate-200/80">

                <button id="open-sidebar" type="button" class="px-4 border-r border-slate-100 text-slate-500 focus:outline-none md:hidden hover:text-slate-700 transition-colors">
                    <span class="sr-only">Open sidebar</span>
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>

                <div class="flex-1 px-6 flex justify-between items-center">
                    <div></div>

                    <!-- Header Right Section -->
                    <div class="flex items-center space-x-4 md:space-x-5">
                        
                        <!-- Notifikasi Bell -->
                        <a href="{{ route('admin.notifikasi.halaman') }}" class="relative p-2 text-slate-500 hover:text-indigo-600 focus:outline-none transition-colors rounded-lg hover:bg-slate-100" title="Notifikasi">
                            <i class="fa-regular fa-bell text-xl"></i>
                            <span id="admin-notifikasi-badge" class="absolute top-1 right-1 block min-w-[1rem] h-4 rounded-full bg-indigo-600 text-[9px] font-bold text-white flex items-center justify-center ring-2 ring-white" style="display: none;">
                                0
                            </span>
                        </a>

                        <div class="h-6 w-px bg-slate-200"></div>

                        <!-- Profil Admin & Dropdown -->
                        <div class="relative flex items-center gap-3">
                            <div class="text-right hidden sm:block">
                                <p class="text-sm font-semibold text-slate-800 leading-none mb-1">{{ Auth::user()->name ?? 'Admin' }}</p>
                                <p class="text-xs text-emerald-600 flex items-center justify-end gap-1 font-medium">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 inline-block"></span> Available
                                </p>
                            </div>

                            <button id="user-menu-button" class="flex h-9 w-9 items-center justify-center rounded-full bg-indigo-50 text-xs font-bold text-indigo-600 ring-2 ring-indigo-100 hover:ring-indigo-300 transition-all focus:outline-none">
                                {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 2)) }}
                            </button>

                            <div id="user-dropdown" class="origin-top-right absolute right-0 top-11 mt-2 w-48 rounded-xl shadow-lg py-1 bg-white border border-slate-200 hidden focus:outline-none z-30">
                                <a href="{{ route('admin.sekolah.index') }}" class="flex items-center px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 hover:text-indigo-600 transition-colors gap-2">
                                    <i class="fa-solid fa-user-gear text-slate-400"></i> Profil Admin
                                </a>
                                <hr class="border-slate-100 my-1">
                                <button onclick="document.getElementById('logout-form').submit();" class="flex w-full items-center px-4 py-2.5 text-sm text-rose-600 hover:bg-rose-50 transition-colors text-left gap-2">
                                    <i class="fa-solid fa-power-off"></i> Keluar (Logout)
                                </button>
                            </div>
                        </div>

                        <!-- Logout Button -->
                        <button onclick="document.getElementById('logout-form').submit();" class="flex items-center gap-2 px-3 py-1.5 text-xs font-semibold text-rose-600 hover:text-rose-700 bg-rose-50 hover:bg-rose-100 rounded-lg transition-colors border border-rose-200" title="Keluar">
                            <i class="fa-solid fa-power-off text-xs"></i>
                            <span class="hidden sm:inline">Logout</span>
                        </button>

                    </div>
                </div>
            </header>

            <!-- CONTENT BODY -->
            <main class="flex-1 relative overflow-y-auto focus:outline-none p-6 custom-scrollbar">
                @yield('content')
            </main>
        </div>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
    </form>

    {{-- Script Ajax & Modal Control --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const openBtn = document.getElementById('open-sidebar');
        const closeBtn = document.getElementById('close-sidebar');

        function toggleSidebar() {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        if(openBtn) openBtn.addEventListener('click', toggleSidebar);
        if(closeBtn) closeBtn.addEventListener('click', toggleSidebar);
        if(overlay) overlay.addEventListener('click', toggleSidebar);

        // Script Dropdown Profil
        const userMenuButton = document.getElementById('user-menu-button');
        const userDropdown = document.getElementById('user-dropdown');

        if(userMenuButton && userDropdown) {
            userMenuButton.addEventListener('click', (event) => {
                event.stopPropagation();
                userDropdown.classList.toggle('hidden');
            });

            document.addEventListener('click', (event) => {
                if (!userMenuButton.contains(event.target) && !userDropdown.contains(event.target)) {
                    userDropdown.classList.add('hidden');
                }
            });
        }

        // Script Badge Notifikasi
        const notifikasiBadge = document.getElementById('admin-notifikasi-badge');
        const notifikasiCountUrl = '{{ route('admin.notifikasi.unread-count') }}';

        async function refreshNotifikasiBadge() {
            if (!notifikasiBadge) return;

            try {
                const response = await fetch(notifikasiCountUrl, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    credentials: 'same-origin',
                });

                if (!response.ok) return;

                const data = await response.json();
                const count = Number(data.unread_count || 0);
                notifikasiBadge.textContent = count;
                notifikasiBadge.style.display = count > 0 ? 'flex' : 'none';
            } catch (error) {
                console.error('Gagal mengambil jumlah notifikasi:', error);
            }
        }

        window.addEventListener('DOMContentLoaded', () => {
            refreshNotifikasiBadge();
        });
    </script>
</body>

</html>