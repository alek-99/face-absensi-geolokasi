@extends('layouts.guru')
@section('title', 'Dashboard Guru')
@section('content')
    <div class="max-w-md mx-auto min-h-screen bg-gray-50 pb-12 space-y-6 px-4 pt-4 font-sans">
        
        {{--HEADER (Welcome Banner) --}}
        <div class="relative overflow-hidden bg-gradient-to-r from-emerald-500 to-teal-600 p-6 rounded-3xl shadow-lg shadow-emerald-100 flex items-center gap-4 text-white">
    <!-- Ornamen Dekoratif Latar Belakang (Logo SIAFI Besar & Transparan) -->
    <div class="absolute -right-8 -bottom-8 w-40 h-40 opacity-15 rotate-12 pointer-events-none select-none">
        <img src="{{ asset('storage/images/logo.png') }}" alt="SIAFI Ornament" class="w-full h-full object-contain grayscale brightness-200">
    </div>
    
    <!-- Logo Utama -->
    <div class="w-16 h-16 shrink-0 shadow-md rounded-2xl bg-white/90 p-2 backdrop-blur-sm transition-transform hover:scale-105 duration-300 z-10">
        <img src="{{ asset('storage/images/logo.png') }}" alt="SIAFI Logo" class="w-full h-full object-contain">
    </div>
    
    <!-- Teks / Konten -->
    <div class="flex flex-col z-10">
        <span class="text-xs font-semibold tracking-wider text-emerald-100 uppercase">Selamat Datang di</span>
        <h2 class="text-2xl font-black tracking-tight font-mono">SIAFI</h2>
        <p class="text-xs text-emerald-500 bg-white/90 px-2 py-0.5 rounded-full font-medium w-max mt-1 shadow-sm">
            Sistem Absensi FACE ID
        </p>
    </div>
</div>

        {{-- STATISTIK DATA (Ringkasan Kehadiran) --}}
      <div>
    <h3 class="text-sm font-bold text-gray-700 mb-3 px-1 flex items-center gap-1.5">
        <i class="fas fa-chart-pie text-emerald-500"></i> Ringkasan Kehadiran Bulan Ini
    </h3>
    <div class="grid grid-cols-3 gap-3">
        {{-- Hadir --}}
        <div class="bg-white p-3 rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center justify-center text-center transition-all active:scale-95">
            <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-blue-500 mb-2">
                <i class="fas fa-user-check text-sm"></i>
            </div>
            <span class="text-xs font-medium text-gray-500 block mb-0.5">Hadir</span>
            <span class="text-lg font-bold text-gray-800">{{ $totalHadir }}</span>
        </div>
        
        {{-- Sakit/Izin --}}
        <div class="bg-white p-3 rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center justify-center text-center transition-all active:scale-95">
            <div class="w-8 h-8 rounded-full bg-amber-50 flex items-center justify-center text-amber-500 mb-2">
                <i class="fas fa-envelope-open-text text-sm"></i>
            </div>
            <span class="text-xs font-medium text-gray-500 block mb-0.5">Sakit/Izin</span>
            <span class="text-lg font-bold text-gray-800">{{ $totalIzin }}</span>
        </div>
        
        {{-- Alpa --}}
        <div class="bg-white p-3 rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center justify-center text-center transition-all active:scale-95">
            <div class="w-8 h-8 rounded-full bg-rose-50 flex items-center justify-center text-rose-500 mb-2">
                <i class="fas fa-user-times text-sm"></i>
            </div>
            <span class="text-xs font-medium text-gray-500 block mb-0.5">Alpa</span>
            <span class="text-lg font-bold text-gray-800">{{ $totalAlpha }}</span>
        </div>
    </div>
</div>

        {{-- MENU CEPAT (Quick Actions) --}}
        <div>
            <h3 class="text-sm font-bold text-gray-700 mb-3 px-1 flex items-center gap-1.5">
                <i class="fas fa-th-large text-emerald-500"></i> Menu Utama
            </h3>
            <div class="grid grid-cols-4 gap-3 bg-white p-4 rounded-3xl shadow-sm border border-gray-100">
                {{-- ABSENSI --}}
                <a href="{{ route('guru.absensi.index') }}" class="flex flex-col items-center group transition-all active:scale-90">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white shadow-md shadow-blue-100 group-hover:scale-105 transition-transform">
                        <i class="fas fa-camera text-base"></i>
                    </div>
                    <span class="text-[11px] font-semibold text-gray-600 mt-2 text-center truncate w-full">Absen</span>
                </a>
                
                {{-- JADWAL --}}
                <a href="{{ route('guru.jadwal.index') }}" class="flex flex-col items-center group transition-all active:scale-90">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center text-white shadow-md shadow-orange-100 group-hover:scale-105 transition-transform">
                        <i class="fas fa-calendar-alt text-base"></i>
                    </div>
                    <span class="text-[11px] font-semibold text-gray-600 mt-2 text-center truncate w-full">Jadwal</span>
                </a>
                
                {{-- FACE ID --}}
                <a href="{{ route('guru.wajah.halaman') }}" class="flex flex-col items-center group transition-all active:scale-90">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-400 to-teal-600 flex items-center justify-center text-white shadow-md shadow-teal-100 group-hover:scale-105 transition-transform">
                       <i class="fas fa-user-check text-base"></i>
                    </div>
                    <span class="text-[11px] font-semibold text-gray-600 mt-2 text-center truncate w-full">Face-ID</span>
                </a>
                
                {{-- PROFIL --}}
                <a href="{{ route('profile.edit') }}" class="flex flex-col items-center group transition-all active:scale-90">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center text-white shadow-md shadow-purple-100 group-hover:scale-105 transition-transform">
                        <i class="fas fa-user-circle text-base"></i>
                    </div>
                    <span class="text-[11px] font-semibold text-gray-600 mt-2 text-center truncate w-full">Profil</span>
                </a>
            </div>
        </div>

        {{-- INFORMASI SISTEM & BANTUAN --}}
        <div class="bg-gradient-to-br from-slate-800 to-slate-900 text-white rounded-3xl p-5 shadow-xl relative overflow-hidden">
            <div class="absolute right-0 top-0 opacity-10 translate-x-4 -translate-y-4">
                <i class="fas fa-info-circle text-8xl"></i>
            </div>
            
            <h4 class="text-sm font-bold tracking-wide mb-1 flex items-center gap-2 text-emerald-400">
                <i class="fas fa-info-circle"></i> Mengenal SIAFI
            </h4>
            <p class="text-xs text-slate-300 leading-relaxed mb-4">
                Sistem absensi berbasis Face ID dan geolokasi. Membantu pencatatan kehadiran Dewan Guru secara otomatis, akurat, efektif, dan efisien.
            </p>
            
            <div class="grid grid-cols-2 gap-3 pt-2 border-t border-slate-700/50">
                <a href="{{ route('panduan') }}" class="flex items-center justify-center gap-2 bg-slate-700/50 hover:bg-slate-700 py-2.5 px-3 rounded-xl text-xs font-semibold transition-colors border border-slate-600/30">
                    <i class="fas fa-book-open text-emerald-400"></i> Panduan
                </a>
                <a href="https://wa.me/6281389090873" target="_blank" class="flex items-center justify-center gap-2 bg-emerald-500 hover:bg-emerald-600 py-2.5 px-3 rounded-xl text-xs font-semibold text-white transition-colors shadow-sm shadow-emerald-900/20">
                    <i class="fab fa-whatsapp text-sm"></i> Hubungi Bantuan
                </a>
            </div>
        </div>

    </div>
@endsection