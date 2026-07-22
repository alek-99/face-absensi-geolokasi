@extends('layouts.guru') 
@section('title', 'Jadwal Mengajar')
@section('page_title', 'Jadwal Mengajarku')
@section('content')

<!-- Font Awesome via Cloudflare CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

<div class="max-w-md mx-auto px-2 py-4 space-y-6 animate-fade-in">

    {{-- Alert Error --}}
    @if(session('error'))
    <div class="flex items-center gap-3 bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-900/40 text-red-700 dark:text-red-400 p-4 rounded-2xl shadow-sm text-xs font-medium">
        <i class="fa-solid fa-circle-exclamation text-base text-red-500"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    {{-- Header Section (Mobile Dashboard Style) --}}
    <div class="bg-white dark:bg-[#161615] p-5 rounded-2xl shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A] flex flex-col gap-4">
        <div>
            <h1 class="text-xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] flex items-center gap-2">
                <i class="fa-solid fa-calendar-days text-indigo-600 dark:text-indigo-400"></i>
                Jadwal Mengajar
            </h1>
            <p class="text-xs text-[#706f6c] dark:text-[#A1A09A] mt-1">
                Halo, <span class="font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">{{ $guru->nama ?? 'Guru' }}</span>. Berikut agenda mengajar aktif Anda.
            </p>
        </div>
        
        <a href="{{ route('guru.jadwal.cetak') }}" target="_blank" 
           class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-rose-600 hover:bg-rose-700 active:scale-[0.98] text-white font-semibold text-xs rounded-xl shadow-sm transition-all duration-150">
            <i class="fa-solid fa-file-pdf text-sm"></i>
            Cetak Jadwal (PDF)
        </a>
    </div>

    {{-- Statistik Horizontal Scrollable (Sangat Hemat Ruang di Mobile) --}}
    <div class="flex gap-3 overflow-x-auto pb-2 -mx-2 px-2 scrollbar-none snap-x">
        <!-- Card 1 -->
        <div class="flex-shrink-0 w-28 bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] p-3 rounded-xl snap-start">
            <span class="text-[10px] font-bold text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider block">Total Sesi</span>
            <div class="text-xl font-black text-[#1b1b18] dark:text-[#EDEDEC] mt-0.5 flex items-baseline gap-1">
                {{ $statistik['total_jadwal'] }} <span class="text-[10px] font-normal text-[#706f6c]">Jam</span>
            </div>
        </div>
        <!-- Card 2 -->
        <div class="flex-shrink-0 w-28 bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] p-3 rounded-xl snap-start">
            <span class="text-[10px] font-bold text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider block">Mapel</span>
            <div class="text-xl font-black text-indigo-600 dark:text-indigo-400 mt-0.5">
                {{ $statistik['total_mapel'] }}
            </div>
        </div>
        <!-- Card 3 -->
        <div class="flex-shrink-0 w-28 bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] p-3 rounded-xl snap-start">
            <span class="text-[10px] font-bold text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider block">Hari Aktif</span>
            <div class="text-xl font-black text-emerald-600 dark:text-emerald-400 mt-0.5">
                {{ $statistik['hari_mengajar'] }}
            </div>
        </div>
        <!-- Card 4 -->
        <div class="flex-shrink-0 w-28 bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] p-3 rounded-xl snap-start">
            <span class="text-[10px] font-bold text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider block">Kelas</span>
            <div class="text-xl font-black text-amber-600 dark:text-amber-400 mt-0.5">
                {{ $statistik['total_kelas'] }}
            </div>
        </div>
        <!-- Card 5 -->
        <div class="flex-shrink-0 w-28 bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] p-3 rounded-xl snap-start">
            <span class="text-[10px] font-bold text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider block">Ruangan</span>
            <div class="text-xl font-black text-[#1b1b18] dark:text-[#EDEDEC] mt-0.5">
                {{ $statistik['total_ruangan'] }}
            </div>
        </div>
    </div>

    {{-- Content Section --}}
    @if($jadwalPerHari->isEmpty())
        <div class="bg-white dark:bg-[#161615] text-center py-12 rounded-2xl border border-[#e3e3e0] dark:border-[#3E3E3A] p-6 flex flex-col items-center justify-center">
            <div class="w-16 h-16 bg-gray-50 dark:bg-zinc-900 text-gray-400 rounded-full flex items-center justify-center text-2xl mb-3">
                <i class="fa-solid fa-calendar-xmark"></i>
            </div>
            <h3 class="text-sm font-bold text-[#1b1b18] dark:text-[#EDEDEC]">Tidak Ada Jadwal</h3>
            <p class="text-[#706f6c] dark:text-[#A1A09A] text-xs mt-1 max-w-xs leading-relaxed">Belum ada agenda mengajar yang aktif atau terdaftar untuk Anda saat ini.</p>
        </div>
    @else
        <div class="space-y-5">
            @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $hari)
                @if($jadwalPerHari->has($hari))
                    <div class="space-y-2.5">
                        {{-- Label Pembatas Hari --}}
                        <div class="flex items-center justify-between px-1">
                            <span class="text-xs font-bold text-[#1b1b18] dark:text-[#EDEDEC] tracking-wide flex items-center gap-1.5">
                                <i class="fa-solid fa-circle text-[6px] text-indigo-500"></i>
                                Hari {{ $hari }}
                            </span>
                            <span class="text-[10px] font-bold bg-indigo-50 dark:bg-indigo-950/50 border border-indigo-100 dark:border-indigo-900 text-indigo-600 dark:text-indigo-400 px-2 py-0.5 rounded-full">
                                {{ $jadwalPerHari[$hari]->count() }} Sesi Kelas
                            </span>
                        </div>
                        
                        {{-- List Card Item Menggantikan Table --}}
                        <div class="space-y-3">
                            @foreach($jadwalPerHari[$hari] as $item)
                                <div class="bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] p-4 rounded-xl shadow-sm flex items-center justify-between transition duration-200 active:scale-[0.99] active:bg-slate-50 dark:active:bg-zinc-900">
                                    
                                    <!-- Detail Waktu & Info Kelas -->
                                    <div class="space-y-1.5">
                                        <!-- Jam Mengajar -->
                                        <div class="text-xs font-bold text-indigo-600 dark:text-indigo-400 flex items-center gap-1.5">
                                            <i class="fa-regular fa-clock text-[11px]"></i>
                                            {{ date('H:i', strtotime($item->jam_masuk)) }} - {{ date('H:i', strtotime($item->jam_pulang)) }}
                                        </div>
                                        <!-- Mata Pelajaran -->
                                        <h4 class="text-sm font-bold text-[#1b1b18] dark:text-[#EDEDEC] leading-tight">
                                            {{ $item->mata_pelajaran ?? '-' }}
                                        </h4>
                                        <!-- Ruangan Tempat Mengajar -->
                                        <div class="text-[11px] text-[#706f6c] dark:text-[#A1A09A] flex items-center gap-1">
                                            <i class="fa-solid fa-location-dot text-[10px] text-rose-500"></i>
                                            {{ $item->ruangan ?? '-' }}
                                        </div>
                                    </div>

                                    <!-- Badge Nama Kelas Kanan -->
                                    <div class="flex-shrink-0">
                                        <span class="bg-slate-100 dark:bg-zinc-800 text-[#1b1b18] dark:text-[#EDEDEC] px-3 py-1.5 rounded-lg text-xs font-bold border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-inner tracking-wide">
                                            {{ $item->kelas ?? '-' }}
                                        </span>
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @endif
</div>

{{-- Tambahkan style CSS kustom opsional untuk menghilangkan scrollbar bawaan --}}
<style>
    .scrollbar-none::-webkit-scrollbar { display: none; }
    .scrollbar-none { -ms-overflow-style: none; scrollbar-width: none; }
</style>

@endsection