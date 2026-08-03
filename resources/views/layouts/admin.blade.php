<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard - Admin')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="{{ asset('storage/images/logo.png') }}">

    <!-- Google Fonts: Roboto -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }

        /* Custom scrollbar untuk sidebar & main content */
        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f8fafc;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .main-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        .main-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .main-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>

<body class="h-full text-slate-700 overflow-hidden antialiased bg-slate-50">

    <div class="flex h-screen overflow-hidden relative">

        <!-- Overlay Mobile -->
        <div id="sidebar-overlay" class="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-sm hidden md:hidden transition-opacity duration-300"></div>

        <!-- SIDEBAR (Warna Putih - Clean Theme) -->
        <aside id="sidebar"
            class="fixed md:static inset-y-0 left-0 w-72 bg-white text-slate-700 flex-shrink-0 flex flex-col justify-between border-r border-slate-200 z-50 transform -translate-x-full md:translate-x-0 transition-all duration-300 ease-in-out shadow-sm">
            
            <div class="flex flex-col flex-grow pt-5 overflow-y-auto custom-scrollbar">

                <!-- Brand / Logo Sidebar -->
                <div class="flex items-center justify-between px-5 mb-5">
                    <div class="flex flex-col items-center gap-2 w-full text-center relative">
                        
                        <!-- Close Button Mobile -->
                        <button id="close-sidebar-mobile" class="absolute right-0 top-0 text-slate-500 hover:text-slate-800 md:hidden focus:outline-none p-1">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>

                        <div class="p-2 rounded-2xl bg-emerald-50/60 shadow-sm w-16 h-16 flex items-center justify-center border border-emerald-100/80">
                            <img src="{{ asset('storage/images/logo.png') }}" class="w-full h-full object-contain filter drop-shadow-sm" alt="Logo SIAFI" />
                        </div>

                        <h2 class="text-xl font-black text-slate-800 tracking-wider">SIAFI</h2>
                        <span class="text-[11px] font-bold text-slate-500 tracking-wide uppercase">
                            Sistem Absensi Face-ID
                        </span>
                    </div>
                </div>

                <!-- Navigasi Menu -->
                <nav class="flex-1 space-y-1 pb-6">
                    <!-- Section: Core -->
                    <div class="px-5 my-2 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        Core
                    </div>

                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}" 
                        class="flex mx-3 my-0.5 items-center px-4 py-2.5 text-sm font-medium rounded-md transition-all duration-200 group 
                        {{ request()->routeIs('admin.dashboard') ? 'text-white bg-green-600 font-bold shadow-sm' : 'text-slate-600 hover:text-white hover:bg-green-600' }}">
                        <i class="fa-solid fa-chart-pie w-6 text-base transition-colors {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"></i>
                        <span>Dashboard</span>
                    </a>

                    <!-- Absensi -->
                    <a href="{{ route('admin.absensi.index') }}" 
                        class="flex mx-3 my-0.5 items-center px-4 py-2.5 text-sm font-medium rounded-md transition-all duration-200 group 
                        {{ request()->routeIs('admin.absensi.index*') ? 'text-white bg-green-600 font-bold shadow-sm' : 'text-slate-600 hover:text-white hover:bg-green-600' }}">
                        <i class="fa-solid fa-calendar-check w-6 text-base transition-colors {{ request()->routeIs('admin.absensi.index*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"></i>
                        <span>Absensi</span>
                    </a>

                    <!-- Pengajuan Absensi -->
                    <a href="{{ route('admin.absensi.pengajuan') }}" 
                        class="flex mx-3 my-0.5 items-center px-4 py-2.5 text-sm font-medium rounded-md transition-all duration-200 group 
                        {{ request()->routeIs('admin.absensi.pengajuan*') ? 'text-white bg-green-600 font-bold shadow-sm' : 'text-slate-600 hover:text-white hover:bg-green-600' }}">
                        <i class="fa-solid fa-envelope-open-text w-6 text-base transition-colors {{ request()->routeIs('admin.absensi.pengajuan*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"></i>
                        <span>Pengajuan Absensi</span>
                    </a>

                    <!-- Section: Master Data -->
                    <div class="pt-3 px-5 my-2 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        Master Data
                    </div>

                    <!-- Data Guru -->
                    <a href="{{ route('admin.guru.index') }}" 
                        class="flex mx-3 my-0.5 items-center px-4 py-2.5 text-sm font-medium rounded-md transition-all duration-200 group 
                        {{ request()->routeIs('admin.guru.*') ? 'text-white bg-green-600 font-bold shadow-sm' : 'text-slate-600 hover:text-white hover:bg-green-600' }}">
                        <i class="fa-solid fa-chalkboard-user w-6 text-base transition-colors {{ request()->routeIs('admin.guru.*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"></i>
                        <span>Data Guru</span>
                    </a>

                    <!-- Data Kelas -->
                    <a href="{{ route('admin.kelas.index') }}" 
                        class="flex mx-3 my-0.5 items-center px-4 py-2.5 text-sm font-medium rounded-md transition-all duration-200 group 
                        {{ request()->routeIs('admin.kelas.*') ? 'text-white bg-green-600 font-bold shadow-sm' : 'text-slate-600 hover:text-white hover:bg-green-600' }}">
                        <i class="fa-solid fa-school w-6 text-base transition-colors {{ request()->routeIs('admin.kelas.*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"></i>
                        <span>Data Kelas</span>
                    </a>

                    <!-- Data Siswa -->
                    <a href="{{ route('admin.siswa.index') }}" 
                        class="flex mx-3 my-0.5 items-center px-4 py-2.5 text-sm font-medium rounded-md transition-all duration-200 group 
                        {{ request()->routeIs('admin.siswa.*') ? 'text-white bg-green-600 font-bold shadow-sm' : 'text-slate-600 hover:text-white hover:bg-green-600' }}">
                        <i class="fa-solid fa-user-graduate w-6 text-base transition-colors {{ request()->routeIs('admin.siswa.*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"></i>
                        <span>Data Siswa</span>
                    </a>

                    <!-- Mata Pelajaran -->
                    <a href="{{ route('admin.mapel.index') }}" 
                        class="flex mx-3 my-0.5 items-center px-4 py-2.5 text-sm font-medium rounded-md transition-all duration-200 group 
                        {{ request()->routeIs('admin.mapel.*') ? 'text-white bg-green-600 font-bold shadow-sm' : 'text-slate-600 hover:text-white hover:bg-green-600' }}">
                        <i class="fa-solid fa-book-bookmark w-6 text-base transition-colors {{ request()->routeIs('admin.mapel.*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"></i>
                        <span>Mata Pelajaran</span>
                    </a>

                    <!-- Jadwal -->
                    <a href="{{ route('admin.jadwal.index') }}" 
                        class="flex mx-3 my-0.5 items-center px-4 py-2.5 text-sm font-medium rounded-md transition-all duration-200 group 
                        {{ request()->routeIs('admin.jadwal.*') ? 'text-white bg-green-600 font-bold shadow-sm' : 'text-slate-600 hover:text-white hover:bg-green-600' }}">
                        <i class="fa-solid fa-calendar-days w-6 text-base transition-colors {{ request()->routeIs('admin.jadwal.*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"></i>
                        <span>Jadwal Pelajaran</span>
                    </a>

                    <!-- Section: Settings -->
                    <div class="pt-3 px-5 my-2 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        User & Settings
                    </div>

                    <!-- Profil -->
                    <a href="{{ route('admin.sekolah.index') }}" 
                        class="flex mx-3 my-0.5 items-center px-4 py-2.5 text-sm font-medium rounded-md transition-all duration-200 group 
                        {{ request()->routeIs('admin.sekolah.*') ? 'text-white bg-green-600 font-bold shadow-sm' : 'text-slate-600 hover:text-white hover:bg-green-600' }}">
                        <i class="fa-solid fa-user-gear w-6 text-base transition-colors {{ request()->routeIs('admin.sekolah.*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"></i>
                        <span>Profil Sekolah</span>
                    </a>

                    <!-- Pengaturan Sistem -->
                    <a href="{{ route('admin.pengaturan.index') }}" 
                        class="flex mx-3 my-0.5 items-center px-4 py-2.5 text-sm font-medium rounded-md transition-all duration-200 group 
                        {{ request()->routeIs('admin.pengaturan.*') ? 'text-white bg-green-600 font-bold shadow-sm' : 'text-slate-600 hover:text-white hover:bg-green-600' }}">
                        <i class="fa-solid fa-sliders w-6 text-base transition-colors {{ request()->routeIs('admin.pengaturan.*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"></i>
                        <span>Pengaturan Sistem</span>
                    </a>
                </nav>
            </div>

            <!-- Footer Sidebar Admin Info -->
            <div class="flex-shrink-0 hidden md:flex border-t border-slate-200 p-3 bg-slate-50/80 justify-between items-center">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full bg-green-600 text-xs font-bold text-white ring-2 ring-green-100">
                        {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 2)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-bold text-slate-700 leading-tight truncate">{{ Auth::user()->name ?? 'Admin' }}</p>
                        <p class="text-[11px] text-slate-500 font-medium truncate">
                            {{ Auth::user()->role ?? 'Administrator' }}
                        </p>
                    </div>
                </div>
                <button onclick="document.getElementById('logout-form').submit();" class="text-slate-500 hover:text-rose-600 hover:bg-rose-50 p-1.5 rounded-lg transition-colors flex-shrink-0 ml-1" title="Logout">
                    <i class="fa-solid fa-power-off text-base"></i>
                </button>
            </div>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <div class="flex flex-col flex-1 min-w-0 overflow-hidden bg-slate-50">

            <!-- HEADER / TOPBAR -->
            <header class="relative z-10 flex-shrink-0 flex h-16 bg-green-600 border-b border-green-700 shadow-sm">

                <!-- Toggle Sidebar Hamburger Button -->
                <button id="toggle-sidebar" type="button" class="px-4 text-white hover:bg-green-700 focus:outline-none transition-colors duration-200">
                    <span class="sr-only">Toggle sidebar</span>
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>

                <div class="flex-1 px-4 sm:px-6 flex justify-between items-center min-w-0">
                    
                    <!-- Header Left Section: Judul & Subtitle -->
                    <div class="flex flex-col justify-center min-w-0 pr-4">
                        <h2 class="text-sm sm:text-base font-bold text-white tracking-wide uppercase truncate leading-tight">
                            ADMIN DASHBOARD
                        </h2>
                        <p class="text-[10px] sm:text-xs text-green-100/90 truncate font-normal leading-tight hidden xs:block">
                            Sistem absensi face-id dan geolokasi SMK Maulana Hasanudin
                        </p>
                    </div>

                    <!-- Header Right Section -->
                    <div class="flex items-center space-x-3 sm:space-x-4 flex-shrink-0">
                        
                        <!-- Notifikasi Bell -->
                        <a href="{{ route('admin.notifikasi.halaman') }}" class="relative p-2 text-green-100 hover:text-white hover:bg-green-700 rounded-lg focus:outline-none transition-colors duration-200" title="Notifikasi">
                            <i class="fa-regular fa-bell text-xl"></i>
                            <span id="admin-notifikasi-badge" class="absolute top-1 right-1 block min-w-[1.25rem] h-4 bg-rose-500 text-[10px] font-bold text-white items-center justify-center border border-green-600 rounded-full px-1" style="display: none;">
                                0
                            </span>
                        </a>

                        <div class="h-5 w-px bg-green-500/50"></div>

                        <!-- Profil Admin & Dropdown -->
                        <div class="relative flex items-center gap-3">
                            <div class="text-right hidden sm:block">
                                <p class="text-xs font-semibold text-white leading-none mb-1">{{ Auth::user()->name ?? 'Admin' }}</p>
                                <p class="text-[10px] text-green-200 flex items-center justify-end gap-1 font-medium">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 inline-block"></span> Available
                                </p>
                            </div>

                            <button id="user-menu-button" class="flex h-9 w-9 items-center justify-center rounded-full bg-green-700 text-xs font-bold text-white border border-green-500 hover:bg-green-800 hover:border-green-400 transition-colors duration-200 focus:outline-none">
                                {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 2)) }}
                            </button>

                            <!-- User Dropdown Menu -->
                            <div id="user-dropdown" class="origin-top-right absolute right-0 top-12 mt-1 w-48 shadow-lg py-1 bg-white rounded-md border border-slate-200 hidden focus:outline-none z-30">
                                <a href="{{ route('admin.sekolah.index') }}" class="flex items-center px-4 py-2 text-xs text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition-colors duration-150 gap-2">
                                    <i class="fa-solid fa-user-gear text-slate-400"></i> Profil Admin
                                </a>
                                <hr class="border-slate-100 my-1">
                                <button onclick="document.getElementById('logout-form').submit();" class="flex w-full items-center px-4 py-2 text-xs text-rose-600 hover:bg-rose-50 transition-colors duration-150 text-left gap-2">
                                    <i class="fa-solid fa-power-off"></i> Keluar (Logout)
                                </button>
                            </div>
                        </div>

                        <!-- Logout Button (Header Quick Action) -->
                        <button onclick="document.getElementById('logout-form').submit();" class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-white bg-rose-600 hover:bg-rose-700 transition-colors duration-200 rounded border border-rose-500" title="Keluar">
                            <i class="fa-solid fa-power-off text-xs"></i>
                            <span class="hidden sm:inline">Logout</span>
                        </button>

                    </div>
                </div>
            </header>

            <!-- CONTENT BODY -->
            <main class="flex-1 relative overflow-y-auto focus:outline-none p-4 sm:p-6 main-scrollbar">
                
                <!-- Background Overlay Logo Watermark -->
                <div class="aria-hidden:true pointer-events-none absolute inset-0 flex items-center justify-center overflow-hidden z-0">
                    <img src="{{ asset('storage/images/logo.png') }}" class="w-[500px] md:w-[650px] lg:w-[750px] opacity-15 object-contain select-none transition-all duration-300" alt="Overlay Background Logo" />
                </div>

                <!-- Content Slot -->
                <div class="relative z-10">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
    </form>

    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const toggleBtn = document.getElementById('toggle-sidebar');
        const closeMobileBtn = document.getElementById('close-sidebar-mobile');

        // Toggle Sidebar Function
        function toggleSidebar() {
            const isMobile = window.innerWidth < 768;
            
            if (isMobile) {
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('hidden');
            } else {
                sidebar.classList.toggle('md:-translate-x-full');
                sidebar.classList.toggle('md:w-0');
                sidebar.classList.toggle('md:overflow-hidden');
            }
        }

        if (toggleBtn) toggleBtn.addEventListener('click', toggleSidebar);
        if (closeMobileBtn) closeMobileBtn.addEventListener('click', toggleSidebar);
        if (overlay) overlay.addEventListener('click', toggleSidebar);

        // Script Dropdown Profil
        const userMenuButton = document.getElementById('user-menu-button');
        const userDropdown = document.getElementById('user-dropdown');

        if (userMenuButton && userDropdown) {
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