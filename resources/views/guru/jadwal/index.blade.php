@extends('layouts.guru') 
@section('title', 'Jadwal Mengajar')
@section('page_title', 'Jadwal Mengajarku')

@section('content')
<div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-6 space-y-6 animate-fade-in">

    {{-- Alert Error --}}
    @if(session('error'))
    <div class="flex items-center gap-3 bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800/50 text-emerald-800 dark:text-emerald-300 p-4 rounded-xl shadow-sm text-xs sm:text-sm font-medium">
        <i class="fa-solid fa-circle-exclamation text-base text-emerald-600 dark:text-emerald-400 flex-shrink-0"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    {{-- Header Section (Jazzmin Emerald Style) --}}
    <div class="bg-white dark:bg-[#1a231f] p-5 sm:p-6 rounded-2xl shadow-sm border border-emerald-100 dark:border-emerald-900/40 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="p-2 bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400 rounded-lg">
                    <i class="fa-solid fa-calendar-days text-lg sm:text-xl"></i>
                </span>
                <h1 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-gray-100 tracking-tight">
                    Jadwal Mengajar
                </h1>
            </div>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-2">
                Halo, <span class="font-semibold text-emerald-700 dark:text-emerald-400">{{ $guru->nama ?? 'Guru' }}</span>. Berikut agenda mengajar aktif Anda.
            </p>
        </div>
        
        {{-- Tombol Ekspor --}}
        <div class="flex flex-col sm:flex-row items-center gap-2.5 w-full md:w-auto">
            <a href="{{ route('guru.jadwal.cetak') }}" target="_blank" 
               class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 active:scale-[0.98] text-white font-semibold text-xs rounded-xl shadow-sm hover:shadow-emerald-600/20 transition-all duration-150">
                <i class="fa-solid fa-file-pdf"></i>
                Cetak PDF
            </a>

            <a href="{{ route('guru.jadwal.excel') }}" 
               class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-teal-600 hover:bg-teal-700 active:scale-[0.98] text-white font-semibold text-xs rounded-xl shadow-sm hover:shadow-teal-600/20 transition-all duration-150">
                <i class="fa-solid fa-file-excel"></i>
                Ekspor Excel
            </a>
        </div>
    </div>

    {{-- Statistik Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4">
        <!-- Card 1 -->
        <div class="bg-white dark:bg-[#1a231f] border border-emerald-100 dark:border-emerald-900/40 p-4 rounded-xl shadow-sm flex flex-col justify-between">
            <span class="text-[11px] font-bold text-emerald-700 dark:text-emerald-400 uppercase tracking-wider">Total Sesi</span>
            <div class="text-2xl font-black text-gray-900 dark:text-gray-100 mt-2 flex items-baseline gap-1">
                {{ $statistik['total_jadwal'] }} <span class="text-xs font-normal text-gray-500">Sesi</span>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white dark:bg-[#1a231f] border border-emerald-100 dark:border-emerald-900/40 p-4 rounded-xl shadow-sm flex flex-col justify-between">
            <span class="text-[11px] font-bold text-emerald-700 dark:text-emerald-400 uppercase tracking-wider">Mata Pelajaran</span>
            <div class="text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-2">
                {{ $statistik['total_mapel'] }}
            </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white dark:bg-[#1a231f] border border-emerald-100 dark:border-emerald-900/40 p-4 rounded-xl shadow-sm flex flex-col justify-between">
            <span class="text-[11px] font-bold text-emerald-700 dark:text-emerald-400 uppercase tracking-wider">Hari Aktif</span>
            <div class="text-2xl font-black text-teal-600 dark:text-teal-400 mt-2">
                {{ $statistik['hari_mengajar'] }}
            </div>
        </div>

        <!-- Card 4 -->
        <div class="bg-white dark:bg-[#1a231f] border border-emerald-100 dark:border-emerald-900/40 p-4 rounded-xl shadow-sm flex flex-col justify-between">
            <span class="text-[11px] font-bold text-emerald-700 dark:text-emerald-400 uppercase tracking-wider">Total Kelas</span>
            <div class="text-2xl font-black text-emerald-700 dark:text-emerald-300 mt-2">
                {{ $statistik['total_kelas'] }}
            </div>
        </div>

        <!-- Card 5 -->
        <div class="col-span-2 sm:col-span-1 bg-white dark:bg-[#1a231f] border border-emerald-100 dark:border-emerald-900/40 p-4 rounded-xl shadow-sm flex flex-col justify-between">
            <span class="text-[11px] font-bold text-emerald-700 dark:text-emerald-400 uppercase tracking-wider">Total Ruangan</span>
            <div class="text-2xl font-black text-gray-900 dark:text-gray-100 mt-2">
                {{ $statistik['total_ruangan'] }}
            </div>
        </div>
    </div>

    {{-- Content Section --}}
    @if($jadwalPerHari->isEmpty())
        <div class="bg-white dark:bg-[#1a231f] text-center py-12 rounded-2xl border border-emerald-100 dark:border-emerald-900/40 p-6 flex flex-col items-center justify-center shadow-sm">
            <div class="w-16 h-16 bg-emerald-50 dark:bg-emerald-950/50 text-emerald-500 rounded-full flex items-center justify-center text-2xl mb-3">
                <i class="fa-solid fa-calendar-xmark"></i>
            </div>
            <h3 class="text-base font-bold text-gray-900 dark:text-gray-100">Tidak Ada Jadwal</h3>
            <p class="text-gray-500 dark:text-gray-400 text-xs sm:text-sm mt-1 max-w-xs leading-relaxed">
                Belum ada agenda mengajar yang aktif atau terdaftar untuk Anda saat ini.
            </p>
        </div>
    @else
        <div class="space-y-6">
            @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $hari)
                @if($jadwalPerHari->has($hari))
                    <div class="space-y-3">
                        {{-- Label Pembatas Hari --}}
                        <div class="flex items-center justify-between border-b border-emerald-100 dark:border-emerald-900/40 pb-2 px-1">
                            <span class="text-sm font-bold text-gray-800 dark:text-gray-200 tracking-wide flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                Hari {{ $hari }}
                            </span>
                            <span class="text-xs font-bold bg-emerald-100 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800/50 text-emerald-700 dark:text-emerald-300 px-2.5 py-0.5 rounded-full">
                                {{ $jadwalPerHari[$hari]->count() }} Sesi
                            </span>
                        </div>
                        
                        {{-- Grid Card Item (Responsive: 1 kolom di HP, 2-3 kolom di layar besar) --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3.5">
                            @foreach($jadwalPerHari[$hari] as $item)
                                <div class="bg-white dark:bg-[#1a231f] border border-emerald-100 dark:border-emerald-900/40 p-4 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between gap-3 border-l-4 border-l-emerald-500">
                                    
                                    <!-- Header Card: Jam & Badge Kelas -->
                                    <div class="flex items-center justify-between gap-2">
                                        <div class="text-xs font-bold text-emerald-600 dark:text-emerald-400 flex items-center gap-1.5 bg-emerald-50 dark:bg-emerald-950/40 px-2.5 py-1 rounded-md border border-emerald-100 dark:border-emerald-900/30">
                                            <i class="fa-regular fa-clock text-[11px]"></i>
                                            {{ \Carbon\Carbon::parse($item->jam_masuk)->format('H:i') }} - {{ \Carbon\Carbon::parse($item->jam_pulang)->format('H:i') }}
                                        </div>

                                        <span class="bg-emerald-800 text-emerald-50 dark:bg-emerald-900 dark:text-emerald-200 px-2.5 py-1 rounded-md text-xs font-semibold tracking-wide">
                                            {{ $item->kelas->nama_kelas ?? '-' }}
                                        </span>
                                    </div>

                                    <!-- Detail Mapel & Ruangan -->
                                    <div class="space-y-1">
                                        <h4 class="text-base font-bold text-gray-900 dark:text-gray-100 leading-snug">
                                            {{ $item->mapel->nama_mapel ?? '-' }}
                                        </h4>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                                            <i class="fa-solid fa-location-dot text-emerald-500"></i>
                                            Ruang: <span class="font-medium text-gray-700 dark:text-gray-300">{{ $item->kelas->ruangan ?? '-' }}</span>
                                        </div>
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
@endsection