@extends('layouts.guru') 
@section('title', 'Jadwal Mengajar')
@section('page_title', 'Jadwal Mengajarku')

@push('styles')
{{-- Font Awesome via Cloudflare CDN --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6 animate-fade-in">

    {{-- Alert Error --}}
    @if(session('error'))
    <div class="flex items-center gap-3 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800/60 text-red-800 dark:text-red-300 p-4 rounded-2xl shadow-sm text-xs sm:text-sm font-medium transition-all">
        <div class="w-8 h-8 rounded-xl bg-red-100 dark:bg-red-900/60 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-triangle-exclamation text-red-600 dark:text-red-400 text-sm"></i>
        </div>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    {{-- Header Section --}}
    <div class="bg-white dark:bg-[#1a231f] p-5 sm:p-6 rounded-2xl shadow-sm border border-emerald-100/80 dark:border-emerald-900/40 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <span class="p-2.5 bg-emerald-100/80 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400 rounded-xl shadow-xs">
                    <i class="fa-solid fa-calendar-week text-xl"></i>
                </span>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100 tracking-tight">
                    Jadwal Mengajar
                </h1>
            </div>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-2 flex items-center gap-1.5">
                <i class="fa-regular fa-circle-user text-emerald-600 dark:text-emerald-400"></i>
                Halo, <span class="font-semibold text-emerald-700 dark:text-emerald-400">{{ $guru->nama ?? 'Guru' }}</span>. Berikut agenda mengajar aktif Anda.
            </p>
        </div>
        
        {{-- Tombol Ekspor --}}
        <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
            <a href="{{ route('guru.jadwal.cetak') }}" target="_blank" 
               class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 active:scale-[0.98] text-white font-medium text-xs rounded-xl shadow-sm hover:shadow-emerald-600/20 transition-all duration-200">
                <i class="fa-solid fa-file-pdf text-sm"></i>
                <span>Cetak PDF</span>
            </a>

            <a href="{{ route('guru.jadwal.excel') }}" 
               class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-teal-600 hover:bg-teal-700 active:scale-[0.98] text-white font-medium text-xs rounded-xl shadow-sm hover:shadow-teal-600/20 transition-all duration-200">
                <i class="fa-solid fa-file-excel text-sm"></i>
                <span>Ekspor Excel</span>
            </a>
        </div>
    </div>

    {{-- Statistik Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3.5 sm:gap-4">
        <!-- Card 1: Total Sesi -->
        <div class="bg-white dark:bg-[#1a231f] border border-emerald-100 dark:border-emerald-900/40 p-4 rounded-2xl shadow-sm hover:border-emerald-200 dark:hover:border-emerald-800 transition-all flex flex-col justify-between group">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-emerald-700 dark:text-emerald-400 uppercase tracking-wider">Total Sesi</span>
                <span class="w-7 h-7 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xs group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </span>
            </div>
            <div class="text-2xl font-black text-gray-900 dark:text-gray-100 mt-3 flex items-baseline gap-1">
                {{ $statistik['total_jadwal'] }} <span class="text-xs font-normal text-gray-500">Sesi</span>
            </div>
        </div>

        <!-- Card 2: Mapel -->
        <div class="bg-white dark:bg-[#1a231f] border border-emerald-100 dark:border-emerald-900/40 p-4 rounded-2xl shadow-sm hover:border-emerald-200 dark:hover:border-emerald-800 transition-all flex flex-col justify-between group">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-emerald-700 dark:text-emerald-400 uppercase tracking-wider">Mata Pelajaran</span>
                <span class="w-7 h-7 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xs group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-book-bookmark"></i>
                </span>
            </div>
            <div class="text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-3">
                {{ $statistik['total_mapel'] }}
            </div>
        </div>

        <!-- Card 3: Hari Aktif -->
        <div class="bg-white dark:bg-[#1a231f] border border-emerald-100 dark:border-emerald-900/40 p-4 rounded-2xl shadow-sm hover:border-emerald-200 dark:hover:border-emerald-800 transition-all flex flex-col justify-between group">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-emerald-700 dark:text-emerald-400 uppercase tracking-wider">Hari Aktif</span>
                <span class="w-7 h-7 rounded-lg bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400 flex items-center justify-center text-xs group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-calendar-day"></i>
                </span>
            </div>
            <div class="text-2xl font-black text-teal-600 dark:text-teal-400 mt-3">
                {{ $statistik['hari_mengajar'] }}
            </div>
        </div>

        <!-- Card 4: Total Kelas -->
        <div class="bg-white dark:bg-[#1a231f] border border-emerald-100 dark:border-emerald-900/40 p-4 rounded-2xl shadow-sm hover:border-emerald-200 dark:hover:border-emerald-800 transition-all flex flex-col justify-between group">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-emerald-700 dark:text-emerald-400 uppercase tracking-wider">Total Kelas</span>
                <span class="w-7 h-7 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 flex items-center justify-center text-xs group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-chalkboard-user"></i>
                </span>
            </div>
            <div class="text-2xl font-black text-emerald-700 dark:text-emerald-300 mt-3">
                {{ $statistik['total_kelas'] }}
            </div>
        </div>

        <!-- Card 5: Total Ruangan -->
        <div class="col-span-2 sm:col-span-1 bg-white dark:bg-[#1a231f] border border-emerald-100 dark:border-emerald-900/40 p-4 rounded-2xl shadow-sm hover:border-emerald-200 dark:hover:border-emerald-800 transition-all flex flex-col justify-between group">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-emerald-700 dark:text-emerald-400 uppercase tracking-wider">Total Ruangan</span>
                <span class="w-7 h-7 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-gray-600 dark:text-gray-300 flex items-center justify-center text-xs group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-door-open"></i>
                </span>
            </div>
            <div class="text-2xl font-black text-gray-900 dark:text-gray-100 mt-3">
                {{ $statistik['total_ruangan'] }}
            </div>
        </div>
    </div>

    {{-- Content Section --}}
    @if($jadwalPerHari->isEmpty())
        <div class="bg-white p-2 dark:bg-[#1a231f] text-center py-14 rounded-2xl border border-emerald-100 dark:border-emerald-900/40 p-6 flex flex-col items-center justify-center shadow-sm">
            <div class="w-16 h-16 bg-emerald-50 dark:bg-emerald-950/50 text-emerald-500 rounded-2xl flex items-center justify-center text-3xl mb-4 border border-emerald-100 dark:border-emerald-900/50">
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
                    <div class="space-y-3.5">
                        {{-- Label Pembatas Hari --}}
                        <div class="flex items-center justify-between border-b border-emerald-100 dark:border-emerald-900/40 pb-2.5 px-1">
                            <span class="text-sm font-bold text-gray-800 dark:text-gray-200 tracking-wide flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shadow-sm shadow-emerald-500/50"></span>
                                Hari {{ $hari }}
                            </span>
                            <span class="text-[11px] font-bold bg-emerald-100/70 dark:bg-emerald-950/80 border border-emerald-200/80 dark:border-emerald-800/50 text-emerald-700 dark:text-emerald-300 px-3 py-0.5 rounded-full flex items-center gap-1.5">
                                <i class="fa-solid fa-layer-group text-[10px]"></i>
                                {{ $jadwalPerHari[$hari]->count() }} Sesi
                            </span>
                        </div>
                        
                        {{-- Grid Card Item (Flat/Sharp Edge Layout) --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($jadwalPerHari[$hari] as $item)
                                <div class="bg-white dark:bg-[#1a231f] border-0 border-l-4 border-l-emerald-500 rounded-none p-4.5 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between gap-3 group">
                                    
                                    <!-- Header Card: Jam & Badge Kelas -->
                                    <div class="flex items-center justify-between gap-2">
                                        <div class="text-xs font-bold text-emerald-700 dark:text-emerald-400 flex items-center gap-1.5 bg-emerald-50 dark:bg-emerald-950/60 px-2.5 py-1 rounded-none border-0">
                                            <i class="fa-regular fa-clock text-[11px]"></i>
                                            {{ \Carbon\Carbon::parse($item->jam_masuk)->format('H:i') }} - {{ \Carbon\Carbon::parse($item->jam_pulang)->format('H:i') }}
                                        </div>

                                        <span class="bg-emerald-800 text-emerald-50 dark:bg-emerald-900 dark:text-emerald-100 px-3 py-1 rounded-none text-xs font-semibold tracking-wide flex items-center gap-1.5">
                                            <i class="fa-solid fa-users text-[10px]"></i>
                                            {{ $item->kelas->nama_kelas ?? '-' }}
                                        </span>
                                    </div>

                                    <!-- Detail Mapel & Ruangan -->
                                    <div class="space-y-1.5 mt-1">
                                        <h4 class="text-base font-bold text-gray-900 dark:text-gray-100 leading-snug group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">
                                            {{ $item->mapel->nama_mapel ?? '-' }}
                                        </h4>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                                            <i class="fa-solid fa-location-dot text-emerald-500"></i>
                                            <span>Ruang:</span>
                                            <span class="font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 px-2 py-0.5 rounded-none">{{ $item->kelas->ruangan ?? '-' }}</span>
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