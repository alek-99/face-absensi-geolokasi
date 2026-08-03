@extends('layouts.guru')
@section('title', 'Dashboard Guru')

@section('content')
<div class="max-w-5xl mx-auto min-h-screen pb-16 space-y-6 font-sans antialiased text-slate-800">

    {{-- HEADER (Welcome Banner dengan Vibe Edukasi & Modern) --}}
    <div class="relative overflow-hidden bg-gradient-to-br from-emerald-600 via-teal-600 to-cyan-700 p-6 sm:p-8 shadow-xl shadow-emerald-500/15 text-white">
        <!-- Accent Glow & Ornamen Transparan -->
        <div class="absolute -right-10 -bottom-10 w-44 h-44 bg-white/10 blur-2xl pointer-events-none"></div>
        <div class="absolute -right-6 -bottom-6 w-36 h-36 opacity-10 rotate-12 pointer-events-none select-none">
            <img src="{{ asset('storage/images/logo.png') }}" alt="SIAFI Ornament" class="w-full h-full object-contain grayscale brightness-200">
        </div>

        <div class="relative z-10 flex items-center justify-between">
            <div class="space-y-1">
                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-white/15 backdrop-blur-md text-[11px] font-medium tracking-wide text-emerald-100 border border-white/20">
                    <i class="fas fa-sparkles text-amber-300"></i>
                    <span>Selamat Bertugas, Pendidik!</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Halo, {{ explode(' ', Auth::user()->name)[0] }} 👋</h1>
                <p class="text-xs sm:text-sm text-emerald-100 font-light max-w-xs leading-relaxed">
                    Sistem Absensi Berbasis Face-ID & Geolokasi
                </p>
            </div>

            <!-- Logo Utama dengan Card Glassmorphism -->
            <div class="w-16 h-16 sm:w-20 sm:h-20 shrink-0 shadow-lg bg-white/95 p-2.5 backdrop-blur-md border border-white/40 flex items-center justify-center transition-transform hover:scale-105 duration-300">
                <img src="{{ asset('storage/images/logo.png') }}" alt="SIAFI Logo" class="w-full h-full object-contain">
            </div>
        </div>

        <!-- Banner Motivation Footer -->
        <div class="mt-4 pt-3 border-t border-white/15 flex items-center justify-between text-[11px] text-emerald-100/90 font-medium">
            <span class="flex items-center gap-1.5">
                <i class="fas fa-calendar-day text-amber-300"></i>
                {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </span>
            <span class="bg-emerald-800/40 px-2 py-0.5 border border-white/10">Bulan {{ \Carbon\Carbon::now()->translatedFormat('F') }}</span>
        </div>
    </div>

    {{-- STATISTIK DATA (Ringkasan Kehadiran) --}}
    <div>
        <div class="flex items-center justify-between mb-3 px-1">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 flex items-center gap-2">
                <i class="fas fa-chart-line-up text-emerald-500"></i> Kehadiran Bulan Ini
            </h3>
            <span class="text-[11px] text-slate-400 font-medium">Real-time</span>
        </div>

        <div class="grid grid-cols-3 gap-3">
            {{-- Hadir --}}
            <div class="relative bg-white p-4 border border-slate-100 shadow-sm shadow-slate-200/50 flex flex-col items-center justify-between text-center transition-all hover:border-emerald-200 hover:shadow-md group">
                <div class="w-9 h-9 bg-emerald-50 text-emerald-600 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                    <i class="fas fa-user-check text-sm"></i>
                </div>
                <span class="text-2xl font-black text-slate-800 tracking-tight mb-0.5">{{ $totalHadir }}</span>
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Hadir</span>
            </div>

            {{-- Sakit/Izin --}}
            <div class="relative bg-white p-4 border border-slate-100 shadow-sm shadow-slate-200/50 flex flex-col items-center justify-between text-center transition-all hover:border-amber-200 hover:shadow-md group">
                <div class="w-9 h-9 bg-amber-50 text-amber-500 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                    <i class="fas fa-envelope-open-text text-sm"></i>
                </div>
                <span class="text-2xl font-black text-slate-800 tracking-tight mb-0.5">{{ $totalIzin }}</span>
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Izin/Sakit</span>
            </div>

            {{-- Alpa --}}
            <div class="relative bg-white p-4 border border-slate-100 shadow-sm shadow-slate-200/50 flex flex-col items-center justify-between text-center transition-all hover:border-rose-200 hover:shadow-md group">
                <div class="w-9 h-9 bg-rose-50 text-rose-500 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                    <i class="fas fa-user-times text-sm"></i>
                </div>
                <span class="text-2xl font-black text-slate-800 tracking-tight mb-0.5">{{ $totalAlpha }}</span>
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Alpa</span>
            </div>
        </div>
    </div>

    {{-- MENU UTAMA — ABSENSI SAYA --}}
    <div>
        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3 px-1 flex items-center gap-2">
            <i class="fas fa-id-badge text-emerald-500"></i> Absensi Saya
        </h3>

        <div class="grid grid-cols-3 gap-3">
            {{-- ABSEN SEKARANG (Featured / Highlight) --}}
            <a href="{{ route('guru.absensi.index') }}"
                class="col-span-3 sm:col-span-1 relative overflow-hidden flex sm:flex-col items-center gap-4 sm:gap-2 bg-gradient-to-br from-emerald-500 to-teal-600 p-4 sm:p-5 text-white shadow-md shadow-emerald-200/60 group transition-all hover:shadow-lg hover:-translate-y-0.5 active:scale-95">
                <div class="absolute -right-4 -bottom-4 opacity-10">
                    <i class="fas fa-face-recognition text-7xl"></i>
                </div>
                <div class="relative z-10 w-12 h-12 bg-white/20 flex items-center justify-center group-hover:scale-110 transition-transform shrink-0">
                    <i class="fas fa-camera text-lg"></i>
                </div>
                <div class="relative z-10 text-left sm:text-center">
                    <span class="text-sm font-extrabold block">Absen Sekarang</span>
                    <span class="text-[10px] text-emerald-100 font-light">Face-ID & Geolokasi</span>
                </div>
            </a>

            {{-- JADWAL MENGAJAR --}}
            <a href="{{ route('guru.jadwal.index') }}" class="flex flex-col items-center text-center bg-white p-4 border border-slate-100 shadow-sm shadow-slate-200/50 group transition-all hover:border-orange-200 hover:shadow-md hover:-translate-y-0.5 active:scale-95">
                <div class="w-11 h-11 bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center text-white shadow-md shadow-amber-200/60 mb-2 group-hover:scale-110 transition-transform">
                    <i class="fas fa-chalkboard-user text-base"></i>
                </div>
                <span class="text-[11px] font-bold text-slate-700">Jadwal Mengajar</span>
            </a>

            {{-- REKAP ABSENSI --}}
            <a href="{{ route('guru.absensi.riwayat') }}" class="flex flex-col items-center text-center bg-white p-4 border border-slate-100 shadow-sm shadow-slate-200/50 group transition-all hover:border-sky-200 hover:shadow-md hover:-translate-y-0.5 active:scale-95">
                <div class="w-11 h-11 bg-gradient-to-br from-sky-400 to-blue-600 flex items-center justify-center text-white shadow-md shadow-sky-200/60 mb-2 group-hover:scale-110 transition-transform">
                    <i class="fas fa-clock-rotate-left text-base"></i>
                </div>
                <span class="text-[11px] font-bold text-slate-700">Rekap Absensi</span>
            </a>
        </div>
    </div>

    {{-- MENU UTAMA — ABSENSI SISWA --}}
    <div>
        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3 px-1 flex items-center gap-2">
            <i class="fas fa-people-group text-emerald-500"></i> Absensi Siswa
        </h3>

        <div class="grid grid-cols-2 gap-3">
            {{-- ABSENSI SISWA --}}
            <a href="{{ route('guru.absen-siswa.index') }}" class="flex items-center gap-3 bg-white p-4 border border-slate-100 shadow-sm shadow-slate-200/50 group transition-all hover:border-indigo-200 hover:shadow-md hover:-translate-y-0.5 active:scale-95">
                <div class="w-11 h-11 shrink-0 bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white shadow-md shadow-indigo-200/60 group-hover:scale-110 transition-transform">
                    <i class="fas fa-calendar-week text-base"></i>
                </div>
                <div class="min-w-0">
                    <span class="text-[11px] font-bold text-slate-700 block">Absensi Siswa</span>
                    <span class="text-[10px] text-slate-400 block truncate">Input kehadiran kelas</span>
                </div>
            </a>

            {{-- REKAP ABSENSI SISWA --}}
            <a href="{{ route('guru.rekap-absen.index') }}" class="flex items-center gap-3 bg-white p-4 border border-slate-100 shadow-sm shadow-slate-200/50 group transition-all hover:border-teal-200 hover:shadow-md hover:-translate-y-0.5 active:scale-95">
                <div class="w-11 h-11 shrink-0 bg-gradient-to-br from-teal-400 to-cyan-600 flex items-center justify-center text-white shadow-md shadow-teal-200/60 group-hover:scale-110 transition-transform">
                    <i class="fas fa-chart-simple text-base"></i>
                </div>
                <div class="min-w-0">
                    <span class="text-[11px] font-bold text-slate-700 block">Rekap Absensi Siswa</span>
                    <span class="text-[10px] text-slate-400 block truncate">Laporan kehadiran kelas</span>
                </div>
            </a>
        </div>
    </div>

    {{-- MENU UTAMA — LAINNYA --}}
    <div>
        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3 px-1 flex items-center gap-2">
            <i class="fas fa-grid-2 text-emerald-500"></i> Lainnya
        </h3>

        <div class="grid grid-cols-4 gap-3 bg-white p-4 border border-slate-100 shadow-sm shadow-slate-200/50">
            {{-- FACE ID --}}
            <a href="{{ route('guru.wajah.halaman') }}" class="flex flex-col items-center group transition-all">
                <div class="w-12 h-12 bg-gradient-to-br from-violet-500 to-fuchsia-600 flex items-center justify-center text-white shadow-md shadow-violet-200/60 group-hover:scale-110 active:scale-95 transition-all">
                    <i class="fas fa-user-shield text-base"></i>
                </div>
                <span class="text-[11px] font-semibold text-slate-600 mt-2 text-center group-hover:text-violet-600 transition-colors">Face-ID</span>
            </a>

            {{-- AKUN --}}
            <a href="{{ route('profile.edit') }}" class="flex flex-col items-center group transition-all">
                <div class="w-12 h-12 bg-gradient-to-br from-slate-500 to-slate-700 flex items-center justify-center text-white shadow-md shadow-slate-200/60 group-hover:scale-110 active:scale-95 transition-all">
                    <i class="fas fa-user-gear text-base"></i>
                </div>
                <span class="text-[11px] font-semibold text-slate-600 mt-2 text-center group-hover:text-slate-800 transition-colors">Akun</span>
            </a>

            {{-- NOTIFIKASI --}}
            <a href="{{ route('guru.notifikasi.index') }}" class="flex flex-col items-center group transition-all">
                <div class="w-12 h-12 bg-gradient-to-br from-rose-400 to-red-600 flex items-center justify-center text-white shadow-md shadow-rose-200/60 group-hover:scale-110 active:scale-95 transition-all">
                    <i class="fas fa-bell text-base"></i>
                </div>
                <span class="text-[11px] font-semibold text-slate-600 mt-2 text-center group-hover:text-rose-600 transition-colors">Notifikasi</span>
            </a>

            {{-- PANDUAN --}}
            <a href="{{ route('panduan') }}" class="flex flex-col items-center group transition-all">
                <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-green-700 flex items-center justify-center text-white shadow-md shadow-emerald-200/60 group-hover:scale-110 active:scale-95 transition-all">
                    <i class="fas fa-book-open text-base"></i>
                </div>
                <span class="text-[11px] font-semibold text-slate-600 mt-2 text-center group-hover:text-emerald-700 transition-colors">Panduan</span>
            </a>
        </div>
    </div>

    {{-- INFORMASI SISTEM & BANTUAN --}}
    <div class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white p-5 shadow-xl shadow-slate-900/10 border border-slate-700/40">
        <!-- Background Pattern Decor -->
        <div class="absolute -right-4 -bottom-4 opacity-5 text-emerald-400">
            <i class="fas fa-graduation-cap text-9xl"></i>
        </div>

        <div class="relative z-10">
            <div class="flex items-center gap-2 text-emerald-400 mb-2">
                <div class="w-6 h-6 bg-emerald-500/10 flex items-center justify-center">
                    <i class="fas fa-info-circle text-xs"></i>
                </div>
                <h4 class="text-xs font-bold uppercase tracking-wider">Mengenal SIAFI</h4>
            </div>

            <p class="text-xs text-slate-300 leading-relaxed mb-4 font-light">
                Sistem absensi cerdas berbasis <span class="text-emerald-400 font-medium">Face ID</span> & <span class="text-emerald-400 font-medium">Geolokasi</span> untuk mendukung efisiensi, akurasi, dan profesionalitas pencatatan kehadiran Bapak/Ibu Guru.
            </p>

            <div class="grid grid-cols-2 gap-3 pt-3 border-t border-slate-700/60">
                <a href="{{ route('panduan') }}" class="flex items-center justify-center gap-2 bg-slate-800 hover:bg-slate-700/80 active:scale-95 py-2.5 px-3 text-xs font-medium text-slate-200 transition-all border border-slate-700">
                    <i class="fas fa-book-open text-emerald-400"></i> Panduan
                </a>
                <a href="https://wa.me/6281389090873" target="_blank" class="flex items-center justify-center gap-2 bg-emerald-500 hover:bg-emerald-600 active:scale-95 py-2.5 px-3 text-xs font-semibold text-white transition-all shadow-md shadow-emerald-950/30">
                    <i class="fab fa-whatsapp text-sm"></i> Bantuan
                </a>
            </div>
        </div>
    </div>

</div>
@endsection