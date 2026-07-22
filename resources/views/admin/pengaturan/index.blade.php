@extends('layouts.admin')

@section('title', 'Pengaturan Sistem')

@section('content')
<div class="space-y-6">
    
    <div class="bg-[#161d3f] rounded-2xl p-6 shadow-md border border-[#242b4d] flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-white tracking-wide flex items-center gap-2">
                <i class="fa-solid fa-sliders text-[#7367f0]"></i>
                Pusat Kendali Sistem
            </h2>
            <p class="text-[#626885] text-sm mt-1">Konfigurasi alur kerja, otomatisasi absensi, dan standarisasi laporan dalam satu dasbor terintegrasi.</p>
        </div>
        <div class="flex items-center space-x-2 bg-[#10163a] px-4 py-2 rounded-xl text-xs font-semibold text-[#c2c6dc] border border-[#242b4d] uppercase tracking-wider self-start md:self-center">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            System Ready
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <a href="{{ route('admin.pengaturan.absensi') }}" class="group block bg-[#161d3f] rounded-2xl p-6 shadow-md border border-[#242b4d] transition-all duration-300 hover:border-[#7367f0] hover:-translate-y-1">
            <div class="w-12 h-12 bg-[#7367f0]/10 rounded-xl flex items-center justify-center text-[#7367f0] mb-4 group-hover:bg-[#7367f0] group-hover:text-white transition-colors duration-300 shadow-xs">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V12h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-white group-hover:text-[#7367f0] transition-colors">Konfigurasi Absen</h3>
            <p class="text-[#626885] text-sm mt-2 leading-relaxed">Atur parameter waktu kerja, toleransi keterlambatan, dan sinkronisasi perangkat absensi.</p>
            <span class="inline-flex items-center text-xs font-semibold text-[#7367f0] mt-4 group-hover:translate-x-1 transition-transform">
                Kelola Sekarang &rarr;
            </span>
        </a>

        <a href="{{ route('admin.report-setting.index') }}" class="group block bg-[#161d3f] rounded-2xl p-6 shadow-md border border-[#242b4d] transition-all duration-300 hover:border-[#00cfdd] hover:-translate-y-1">
            <div class="w-12 h-12 bg-[#00cfdd]/10 rounded-xl flex items-center justify-center text-[#00cfdd] mb-4 group-hover:bg-[#00cfdd] group-hover:text-white transition-colors duration-300 shadow-xs">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-white group-hover:text-[#00cfdd] transition-colors">Pengaturan Laporan</h3>
            <p class="text-[#626885] text-sm mt-2 leading-relaxed">Kustomisasi variabel kalkulasi rekapitulasi, aturan lembur, dan otomasi periode pelaporan.</p>
            <span class="inline-flex items-center text-xs font-semibold text-[#00cfdd] mt-4 group-hover:translate-x-1 transition-transform">
                Kelola Sekarang &rarr;
            </span>
        </a>

        <a href="{{ route('admin.notifikasi.halaman') }}" class="group block bg-[#161d3f] rounded-2xl p-6 shadow-md border border-[#242b4d] transition-all duration-300 hover:border-[#28c76f] hover:-translate-y-1">
            <div class="w-12 h-12 bg-[#28c76f]/10 rounded-xl flex items-center justify-center text-[#28c76f] mb-4 group-hover:bg-[#28c76f] group-hover:text-white transition-colors duration-300 shadow-xs">
               <i class="fas fa-bell"></i>
            </div>
            <h3 class="text-lg font-bold text-white group-hover:text-[#28c76f] transition-colors">Notifikasi</h3>
            <p class="text-[#626885] text-sm mt-2 leading-relaxed">Lihat Notifikasi Pengajuan Ijin Absen para guru.</p>
            <span class="inline-flex items-center text-xs font-semibold text-[#28c76f] mt-4 group-hover:translate-x-1 transition-transform">
                Kelola Sekarang &rarr;
            </span>
        </a>

    </div>
</div>
@endsection