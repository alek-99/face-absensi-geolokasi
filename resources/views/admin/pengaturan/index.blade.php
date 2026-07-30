@extends('layouts.admin')

@section('title', 'Pengaturan Sistem')

@section('content')
<div class="space-y-6">
    
    {{-- Header Banner --}}
    <div class="bg-white shadow-sm border border-gray-200 border-l-4 border-l-green-500 rounded-none p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 tracking-wide flex items-center gap-2">
                <i class="fa-solid fa-sliders text-indigo-600"></i>
                Pusat Kendali Sistem
            </h2>
            <p class="text-gray-500 text-sm mt-1">
                Konfigurasi alur kerja, otomatisasi absensi, dan standarisasi laporan dalam satu dasbor terintegrasi.
            </p>
        </div>
        <div class="flex items-center space-x-2 bg-emerald-50 px-4 py-2 rounded-none text-xs font-semibold text-emerald-700 border border-emerald-200 uppercase tracking-wider self-start md:self-center">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            System Ready
        </div>
    </div>

    {{-- Grid Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        {{-- Card 1: Konfigurasi Absen --}}
        <a href="{{ route('admin.pengaturan.absensi') }}" class="group block bg-white rounded-none p-6 shadow-sm border border-gray-200 border-l-4 border-l-indigo-500 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
            <div class="w-12 h-12 bg-indigo-50 rounded-none flex items-center justify-center text-indigo-600 mb-4 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V12h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">Konfigurasi Absen</h3>
            <p class="text-gray-500 text-sm mt-2 leading-relaxed">Atur parameter waktu kerja, toleransi keterlambatan, dan sinkronisasi perangkat absensi.</p>
            <span class="inline-flex items-center text-xs font-bold text-indigo-600 mt-4 group-hover:translate-x-1 transition-transform">
                Kelola Sekarang &rarr;
            </span>
        </a>

        {{-- Card 2: Pengaturan Laporan --}}
        <a href="{{ route('admin.report-setting.index') }}" class="group block bg-white rounded-none p-6 shadow-sm border border-gray-200 border-l-4 border-l-cyan-500 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
            <div class="w-12 h-12 bg-cyan-50 rounded-none flex items-center justify-center text-cyan-600 mb-4 group-hover:bg-cyan-500 group-hover:text-white transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 group-hover:text-cyan-600 transition-colors">Pengaturan Laporan</h3>
            <p class="text-gray-500 text-sm mt-2 leading-relaxed">Kustomisasi variabel kalkulasi rekapitulasi, aturan lembur, dan otomasi periode pelaporan.</p>
            <span class="inline-flex items-center text-xs font-bold text-cyan-600 mt-4 group-hover:translate-x-1 transition-transform">
                Kelola Sekarang &rarr;
            </span>
        </a>

        {{-- Card 3: Notifikasi --}}
        <a href="{{ route('admin.notifikasi.halaman') }}" class="group block bg-white rounded-none p-6 shadow-sm border border-gray-200 border-l-4 border-l-emerald-500 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
            <div class="w-12 h-12 bg-emerald-50 rounded-none flex items-center justify-center text-emerald-600 mb-4 group-hover:bg-emerald-500 group-hover:text-white transition-colors duration-200">
               <i class="fas fa-bell text-lg"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 group-hover:text-emerald-600 transition-colors">Notifikasi</h3>
            <p class="text-gray-500 text-sm mt-2 leading-relaxed">Lihat Notifikasi Pengajuan Ijin Absen para guru.</p>
            <span class="inline-flex items-center text-xs font-bold text-emerald-600 mt-4 group-hover:translate-x-1 transition-transform">
                Kelola Sekarang &rarr;
            </span>
        </a>

    </div>
</div>
@endsection