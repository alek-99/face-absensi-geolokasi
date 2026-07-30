@extends('layouts.guru')
@section('title', 'Dashboard Guru')

@section('content')
<div class="max-w-md mx-auto min-h-screen bg-slate-50/80 pb-16 space-y-6 px-4 pt-5 font-sans antialiased text-slate-800">

    {{-- HEADER (Welcome Banner dengan Vibe Edukasi & Modern) --}}
    <div class="relative overflow-hidden bg-gradient-to-br from-emerald-600 via-teal-600 to-cyan-700 p-6 rounded-3xl shadow-xl shadow-emerald-500/15 text-white">
        <!-- Accent Glow & Ornamen Transparan -->
        <div class="absolute -right-10 -bottom-10 w-44 h-44 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute -right-6 -bottom-6 w-36 h-36 opacity-10 rotate-12 pointer-events-none select-none">
            <img src="{{ asset('storage/images/logo.png') }}" alt="SIAFI Ornament" class="w-full h-full object-contain grayscale brightness-200">
        </div>

        <div class="relative z-10 flex items-center justify-between">
            <div class="space-y-1">
                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-white/15 backdrop-blur-md text-[11px] font-medium tracking-wide text-emerald-100 border border-white/20">
                    <i class="fas fa-sparkles text-amber-300"></i>
                    <span>Selamat Bertugas, Pendidik!</span>
                </div>
                <h1 class="text-2xl font-extrabold tracking-tight">SIAFI</h1>
                <p class="text-xs text-emerald-100 font-light max-w-[200px] leading-relaxed">
                    Sistem Absensi Berbasis Face-ID & Geolokasi
                </p>
            </div>

            <!-- Logo Utama dengan Card Glassmorphism -->
            <div class="w-16 h-16 shrink-0 shadow-lg rounded-2xl bg-white/95 p-2.5 backdrop-blur-md border border-white/40 flex items-center justify-center transition-transform hover:scale-105 duration-300">
                <img src="{{ asset('storage/images/logo.png') }}" alt="SIAFI Logo" class="w-full h-full object-contain">
            </div>
        </div>

        <!-- Banner Motivation Footer -->
        <div class="mt-4 pt-3 border-t border-white/15 flex items-center justify-between text-[11px] text-emerald-100/90 font-medium">
            <span class="flex items-center gap-1.5">
                <i class="fas fa-calendar-day text-amber-300"></i>
                {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </span>
            <span class="bg-emerald-800/40 px-2 py-0.5 rounded-md border border-white/10">Bulan {{ \Carbon\Carbon::now()->translatedFormat('F') }}</span>
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
            <div class="relative bg-white p-4 rounded-2xl border border-slate-100 shadow-sm shadow-slate-200/50 flex flex-col items-center justify-between text-center transition-all hover:border-emerald-200 hover:shadow-md group">
                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                    <i class="fas fa-user-check text-sm"></i>
                </div>
                <span class="text-2xl font-black text-slate-800 tracking-tight mb-0.5">{{ $totalHadir }}</span>
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Hadir</span>
            </div>

            {{-- Sakit/Izin --}}
            <div class="relative bg-white p-4 rounded-2xl border border-slate-100 shadow-sm shadow-slate-200/50 flex flex-col items-center justify-between text-center transition-all hover:border-amber-200 hover:shadow-md group">
                <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                    <i class="fas fa-envelope-open-text text-sm"></i>
                </div>
                <span class="text-2xl font-black text-slate-800 tracking-tight mb-0.5">{{ $totalIzin }}</span>
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Izin/Sakit</span>
            </div>

            {{-- Alpa --}}
            <div class="relative bg-white p-4 rounded-2xl border border-slate-100 shadow-sm shadow-slate-200/50 flex flex-col items-center justify-between text-center transition-all hover:border-rose-200 hover:shadow-md group">
                <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                    <i class="fas fa-user-times text-sm"></i>
                </div>
                <span class="text-2xl font-black text-slate-800 tracking-tight mb-0.5">{{ $totalAlpha }}</span>
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Alpa</span>
            </div>
        </div>
    </div>

    {{-- MENU UTAMA (Quick Actions Modern Grid) --}}
    <div>
        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3 px-1 flex items-center gap-2">
            <i class="fas fa-grid-2 text-emerald-500"></i> Menu Utama
        </h3>

        <div class="grid grid-cols-4 gap-3 bg-white p-4 rounded-3xl border border-slate-100 shadow-sm shadow-slate-200/50">
            {{-- ABSENSI --}}
            <a href="{{ route('guru.absensi.index') }}" class="flex flex-col items-center group transition-all">
                <div class="w-13 h-13 w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white shadow-md shadow-emerald-200/60 group-hover:scale-110 active:scale-95 transition-all">
                    <i class="fas fa-camera text-base"></i>
                </div>
                <span class="text-[11px] font-semibold text-slate-600 mt-2 text-center group-hover:text-emerald-600 transition-colors">Absen</span>
            </a>

            {{-- JADWAL --}}
            <a href="{{ route('guru.jadwal.index') }}" class="flex flex-col items-center group transition-all">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center text-white shadow-md shadow-amber-200/60 group-hover:scale-110 active:scale-95 transition-all">
                    <i class="fas fa-calendar-alt text-base"></i>
                </div>
                <span class="text-[11px] font-semibold text-slate-600 mt-2 text-center group-hover:text-orange-500 transition-colors">Jadwal</span>
            </a>

            {{-- FACE ID --}}
            <a href="{{ route('guru.wajah.halaman') }}" class="flex flex-col items-center group transition-all">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white shadow-md shadow-indigo-200/60 group-hover:scale-110 active:scale-95 transition-all">
                    <i class="fas fa-user-check text-base"></i>
                </div>
                <span class="text-[11px] font-semibold text-slate-600 mt-2 text-center group-hover:text-indigo-600 transition-colors">Face-ID</span>
            </a>

            {{-- PROFIL --}}
            <a href="{{ route('profile.edit') }}" class="flex flex-col items-center group transition-all">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-sky-400 to-blue-600 flex items-center justify-center text-white shadow-md shadow-sky-200/60 group-hover:scale-110 active:scale-95 transition-all">
                    <i class="fas fa-user-circle text-base"></i>
                </div>
                <span class="text-[11px] font-semibold text-slate-600 mt-2 text-center group-hover:text-blue-600 transition-colors">Profil</span>
            </a>
        </div>
    </div>

    {{-- INFORMASI SISTEM & BANTUAN --}}
    <div class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white rounded-3xl p-5 shadow-xl shadow-slate-900/10 border border-slate-700/40">
        <!-- Background Pattern Decor -->
        <div class="absolute -right-4 -bottom-4 opacity-5 text-emerald-400">
            <i class="fas fa-graduation-cap text-9xl"></i>
        </div>

        <div class="relative z-10">
            <div class="flex items-center gap-2 text-emerald-400 mb-2">
                <div class="w-6 h-6 rounded-lg bg-emerald-500/10 flex items-center justify-center">
                    <i class="fas fa-info-circle text-xs"></i>
                </div>
                <h4 class="text-xs font-bold uppercase tracking-wider">Mengenal SIAFI</h4>
            </div>

            <p class="text-xs text-slate-300 leading-relaxed mb-4 font-light">
                Sistem absensi cerdas berbasis <span class="text-emerald-400 font-medium">Face ID</span> & <span class="text-emerald-400 font-medium">Geolokasi</span> untuk mendukung efisiensi, akurasi, dan profesionalitas pencatatan kehadiran Bapak/Ibu Guru.
            </p>

            <div class="grid grid-cols-2 gap-3 pt-3 border-t border-slate-700/60">
                <a href="{{ route('panduan') }}" class="flex items-center justify-center gap-2 bg-slate-800 hover:bg-slate-700/80 active:scale-95 py-2.5 px-3 rounded-xl text-xs font-medium text-slate-200 transition-all border border-slate-700">
                    <i class="fas fa-book-open text-emerald-400"></i> Panduan
                </a>
                <a href="https://wa.me/6281389090873" target="_blank" class="flex items-center justify-center gap-2 bg-emerald-500 hover:bg-emerald-600 active:scale-95 py-2.5 px-3 rounded-xl text-xs font-semibold text-white transition-all shadow-md shadow-emerald-950/30">
                    <i class="fab fa-whatsapp text-sm"></i> Bantuan
                </a>
            </div>
        </div>
    </div>

</div>
@endsection