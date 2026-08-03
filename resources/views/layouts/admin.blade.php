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
    class="fixed md:static inset-y-0 left-0 w-80 bg-white text-black flex-shrink-0 flex flex-col justify-between border-r border-slate-200 z-50 transform -translate-x-full md:translate-x-0 transition-all duration-300 ease-in-out shadow-sm">
    
    <div class="flex flex-col flex-grow pt-6 overflow-y-auto custom-scrollbar">

        <!-- Brand / Logo Sidebar -->
        <div class="flex items-center justify-between px-6 mb-6">
            <div class="flex flex-col items-center gap-3 w-full text-center relative">
                
                <!-- Close Button Mobile -->
                <button id="close-sidebar-mobile" class="absolute right-0 top-0 text-black hover:text-slate-600 md:hidden focus:outline-none">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>

                <div class="p-2 rounded-2xl bg-emerald-50/60 shadow-sm w-24 h-24 flex items-center justify-center border border-emerald-100/80">
                    <img src="{{ asset('storage/images/logo.png') }}" class="w-full h-full object-contain filter drop-shadow-sm" alt="Logo SIAFI" />
                </div>

                <h2 class="text-2xl font-black text-black tracking-wider">SIAFI</h2>
                <span class="text-xs font-bold text-black tracking-wide uppercase">
                    Sistem Absensi Face-ID
                </span>
            </div>
        </div>

        <!-- Navigasi Menu (Ukuran Font XL) -->
        <nav class="flex-1 space-y-1 pb-6">
            <!-- Section: Core -->
            <div class="px-6 mb-2 text-xs font-bold text-black uppercase tracking-wider">
                Core
            </div>

            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}" 
            class="flex items-center px-6 py-3.5 text-xl font-medium rounded-none transition-all duration-200 group 
            {{ request()->routeIs('admin.dashboard') ? 'text-white bg-green-600 font-bold shadow-sm' : 'text-black hover:text-white hover:bg-green-600' }}">
                <i class="fa-solid fa-chart-pie mr-4 text-xl transition-colors {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-black group-hover:text-white' }}"></i>
                Dashboard
            </a>

            <!-- Absensi -->
            <a href="{{ route('admin.absensi.index') }}" 
            class="flex items-center px-6 py-3.5 text-xl font-medium rounded-none transition-all duration-200 group 
            {{ request()->routeIs('admin.absensi.index*') ? 'text-white bg-green-600 font-bold shadow-sm' : 'text-black hover:text-white hover:bg-green-600' }}">
                <i class="fa-solid fa-calendar-check mr-4 text-xl transition-colors {{ request()->routeIs('admin.absensi.index*') ? 'text-white' : 'text-black group-hover:text-white' }}"></i>
                Absensi
            </a>

            <!-- Pengajuan Absensi -->
            <a href="{{ route('admin.absensi.pengajuan') }}" 
            class="flex items-center px-6 py-3.5 text-xl font-medium rounded-none transition-all duration-200 group 
            {{ request()->routeIs('admin.absensi.pengajuan*') ? 'text-white bg-green-600 font-bold shadow-sm' : 'text-black hover:text-white hover:bg-green-600' }}">
                <i class="fa-solid fa-envelope-open-text mr-4 text-xl transition-colors {{ request()->routeIs('admin.absensi.pengajuan*') ? 'text-white' : 'text-black group-hover:text-white' }}"></i>
                Pengajuan Absensi
            </a>

            <!-- Section: Master Data -->
            <div class="pt-4 px-6 mb-2 text-xs font-bold text-black uppercase tracking-wider">
                Master Data
            </div>

            <!-- Data Guru -->
            <a href="{{ route('admin.guru.index') }}" 
            class="flex items-center px-6 py-3.5 text-xl font-medium rounded-none transition-all duration-200 group 
            {{ request()->routeIs('admin.guru.*') ? 'text-white bg-green-600 font-bold shadow-sm' : 'text-black hover:text-white hover:bg-green-600' }}">
                <i class="fa-solid fa-chalkboard-user mr-4 text-xl transition-colors {{ request()->routeIs('admin.guru.*') ? 'text-white' : 'text-black group-hover:text-white' }}"></i>
                Data Guru
            </a>

            <!-- Data Kelas -->
            <a href="{{ route('admin.kelas.index') }}" 
            class="flex items-center px-6 py-3.5 text-xl font-medium rounded-none transition-all duration-200 group 
            {{ request()->routeIs('admin.kelas.*') ? 'text-white bg-green-600 font-bold shadow-sm' : 'text-black hover:text-white hover:bg-green-600' }}">
                <i class="fa-solid fa-school mr-4 text-xl transition-colors {{ request()->routeIs('admin.kelas.*') ? 'text-white' : 'text-black group-hover:text-white' }}"></i>
                Data Kelas
            </a>

            <!-- Data Siswa -->
            <a href="{{ route('admin.siswa.index') }}" 
            class="flex items-center px-6 py-3.5 text-xl font-medium rounded-none transition-all duration-200 group 
            {{ request()->routeIs('admin.siswa.*') ? 'text-white bg-green-600 font-bold shadow-sm' : 'text-black hover:text-white hover:bg-green-600' }}">
                <i class="fa-solid fa-user-graduate mr-4 text-xl transition-colors {{ request()->routeIs('admin.siswa.*') ? 'text-white' : 'text-black group-hover:text-white' }}"></i>
                Data Siswa
            </a>

            <!-- Mata Pelajaran -->
            <a href="{{ route('admin.mapel.index') }}" 
            class="flex items-center px-6 py-3.5 text-xl font-medium rounded-none transition-all duration-200 group 
            {{ request()->routeIs('admin.mapel.*') ? 'text-white bg-green-600 font-bold shadow-sm' : 'text-black hover:text-white hover:bg-green-600' }}">
                <i class="fa-solid fa-book-bookmark mr-4 text-xl transition-colors {{ request()->routeIs('admin.mapel.*') ? 'text-white' : 'text-black group-hover:text-white' }}"></i>
                Mata Pelajaran
            </a>

            <!-- Jadwal -->
            <a href="{{ route('admin.jadwal.index') }}" 
            class="flex items-center px-6 py-3.5 text-xl font-medium rounded-none transition-all duration-200 group 
            {{ request()->routeIs('admin.jadwal.*') ? 'text-white bg-green-600 font-bold shadow-sm' : 'text-black hover:text-white hover:bg-green-600' }}">
                <i class="fa-solid fa-calendar-days mr-4 text-xl transition-colors {{ request()->routeIs('admin.jadwal.*') ? 'text-white' : 'text-black group-hover:text-white' }}"></i>
                Jadwal Pelajaran
            </a>

            <!-- Section: Settings -->
            <div class="pt-4 px-6 mb-2 text-xs font-bold text-black uppercase tracking-wider">
                User & Settings
            </div>

            <!-- Profil -->
            <a href="{{ route('admin.sekolah.index') }}" 
            class="flex items-center px-6 py-3.5 text-xl font-medium rounded-none transition-all duration-200 group 
            {{ request()->routeIs('admin.sekolah.*') ? 'text-white bg-green-600 font-bold shadow-sm' : 'text-black hover:text-white hover:bg-green-600' }}">
                <i class="fa-solid fa-user-gear mr-4 text-xl transition-colors {{ request()->routeIs('admin.sekolah.*') ? 'text-white' : 'text-black group-hover:text-white' }}"></i>
                Profil Sekolah
            </a>

            <!-- Pengaturan Sistem -->
            <a href="{{ route('admin.pengaturan.index') }}" 
            class="flex items-center px-6 py-3.5 text-xl font-medium rounded-none transition-all duration-200 group 
            {{ request()->routeIs('admin.pengaturan.*') ? 'text-white bg-green-600 font-bold shadow-sm' : 'text-black hover:text-white hover:bg-green-600' }}">
                <i class="fa-solid fa-sliders mr-4 text-xl transition-colors {{ request()->routeIs('admin.pengaturan.*') ? 'text-white' : 'text-black group-hover:text-white' }}"></i>
                Pengaturan Sistem
            </a>
        </nav>
    </div>

    <!-- Footer Sidebar Admin Info -->
    <div class="flex-shrink-0 hidden md:flex border-t border-slate-200 p-4 bg-slate-50/80 justify-between items-center">
        <div class="flex items-center gap-3 min-w-0">
            <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-green-600 text-sm font-bold text-white ring-2 ring-green-100">
                {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 2)) }}
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-bold text-black leading-tight truncate">{{ Auth::user()->name ?? 'Admin' }}</p>
                <p class="text-xs text-black font-medium truncate">
                    {{ Auth::user()->role ?? 'Administrator' }}
                </p>
            </div>
        </div>
        <button onclick="document.getElementById('logout-form').submit();" class="text-black hover:text-rose-600 hover:bg-rose-50 p-2 rounded-lg transition-colors flex-shrink-0 ml-2" title="Logout">
            <i class="fa-solid fa-power-off text-lg"></i>
        </button>
    </div>
</aside>

            <!-- MAIN CONTENT AREA -->
            <div class="flex flex-col flex-1 min-w-0 overflow-hidden bg-slate-50">

                <!-- HEADER / TOPBAR -->
            <header class="relative z-10 flex-shrink-0 flex h-24 bg-green-600 border-b border-green-700">

        <!-- Toggle Sidebar Hamburger Button -->
        <button id="toggle-sidebar" type="button" class="px-5 text-white hover:bg-green-700 focus:outline-none transition-colors duration-200">
            <span class="sr-only">Toggle sidebar</span>
            <i class="fa-solid fa-bars text-3xl"></i>
        </button>

        <div class="flex-1 px-6 flex justify-between items-center">
            <div></div>

            <!-- Header Right Section -->
            <div class="flex items-center space-x-4 md:space-x-5">
                
                <!-- Notifikasi Bell -->
                <a href="{{ route('admin.notifikasi.halaman') }}" class="relative p-2.5 text-green-100 hover:text-white hover:bg-green-700 focus:outline-none transition-colors duration-200" title="Notifikasi">
                    <i class="fa-regular fa-bell text-2xl"></i>
                    <span id="admin-notifikasi-badge" class="absolute top-1.5 right-1.5 block min-w-[1rem] h-4 bg-rose-500 text-[9px] font-bold text-white items-center justify-center border border-green-600 px-1" style="display: none;">
                        0
                    </span>
                </a>

                <div class="h-6 w-px bg-green-500/50"></div>

                <!-- Profil Admin & Dropdown -->
                <div class="relative flex items-center gap-3">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-semibold text-white leading-none mb-1">{{ Auth::user()->name ?? 'Admin' }}</p>
                        <p class="text-xs text-green-200 flex items-center justify-end gap-1.5 font-medium">
                            <span class="h-2 w-2 bg-emerald-400 inline-block"></span> Available
                        </p>
                    </div>

                    <button id="user-menu-button" class="flex h-10 w-10 items-center justify-center bg-green-700 text-xs font-bold text-white border border-green-500 hover:bg-green-800 hover:border-green-400 transition-colors duration-200 focus:outline-none">
                        {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 2)) }}
                    </button>

                    <!-- User Dropdown Menu -->
                    <div id="user-dropdown" class="origin-top-right absolute right-0 top-12 mt-2 w-48 shadow-lg py-1 bg-white border border-slate-200 hidden focus:outline-none z-30">
                        <a href="{{ route('admin.sekolah.index') }}" class="flex items-center px-4 py-2.5 text-sm text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition-colors duration-150 gap-2.5">
                            <i class="fa-solid fa-user-gear text-slate-400"></i> Profil Admin
                        </a>
                        <hr class="border-slate-100 my-1">
                        <button onclick="document.getElementById('logout-form').submit();" class="flex w-full items-center px-4 py-2.5 text-sm text-rose-600 hover:bg-rose-50 transition-colors duration-150 text-left gap-2.5">
                            <i class="fa-solid fa-power-off"></i> Keluar (Logout)
                        </button>
                    </div>
                </div>

                <!-- Logout Button (Header Quick Action) -->
                <button onclick="document.getElementById('logout-form').submit();" class="flex items-center gap-2 px-3.5 py-2 text-xs font-semibold text-white bg-rose-600 hover:bg-rose-700 transition-colors duration-200 border border-rose-500" title="Keluar">
                    <i class="fa-solid fa-power-off text-xs"></i>
                    <span class="hidden sm:inline">Logout</span>
                </button>

            </div>
        </div>
    </header>

                <!-- CONTENT BODY -->
                <main class="flex-1 relative overflow-y-auto focus:outline-none p-6 main-scrollbar">
                    
                    <!-- Background Overlay Logo Watermark -->
                    <div class="aria-hidden:true pointer-events-none absolute inset-0 flex items-center justify-center overflow-hidden z-0">
                        <img src="{{ asset('storage/images/logo.png') }}" class="w-[550px] md:w-[650px] opacity-[0.05] object-contain select-none transition-all duration-300" alt="Overlay Background Logo" />
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