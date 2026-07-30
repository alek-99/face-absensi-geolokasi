@extends('layouts.guru')
@section('title','Detail Rekap Absen Siswa')
@section('page_title','Detail Rekap Absen Siswa')

@section('content')
<div class="min-h-screen bg-slate-50 text-slate-800 pb-16 font-sans">
    
    <!-- Link Kembali & Navigasi -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-4">
        <a href="{{ route('guru.rekap-absen.index') }}" 
           class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-slate-500 hover:text-emerald-600 transition-colors">
            <i class="fas fa-arrow-left text-emerald-600"></i> Kembali ke Daftar Rekap
        </a>
    </div>

    <!-- Header / Card Info Utama & Filter (Full Width Style Container) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                
                <!-- Info Utama Mata Pelajaran & Kelas -->
                <div class="space-y-1">
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 bg-emerald-50 px-3 py-1 rounded-lg border border-emerald-200/60 mb-2">
                        <i class="fas fa-book-open text-emerald-600"></i> Jurnal Presensi Akademik
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                        {{ $jadwal->mapel?->nama_mapel }}
                    </h1>
                    <div class="text-xs sm:text-sm text-slate-500 pt-1 flex items-center gap-2">
                        <span>Kelas Registered:</span>
                        <span class="font-bold text-emerald-700 bg-emerald-100/60 px-2.5 py-0.5 rounded-md border border-emerald-200/40">
                            {{ $jadwal->kelas?->nama_kelas }}
                        </span>
                    </div>
                </div>

                <!-- Form Filter Bulan & Action Export -->
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 border-t border-slate-100 lg:border-t-0 pt-4 lg:pt-0">
                    <form method="GET" action="{{ route('guru.rekap-absen.detail', $jadwal->id) }}" class="flex items-center gap-2">
                        <div class="relative flex-1 sm:w-auto">
                            <input type="month" name="bulan" value="{{ $bulan }}" 
                                   class="w-full text-xs font-bold bg-slate-50 border border-slate-200 text-slate-700 rounded-xl px-3 py-2.5 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all">
                        </div>
                        <button type="submit" 
                                class="px-4 py-2.5 text-xs font-bold tracking-wide text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl transition-all shadow-xs active:scale-[0.98] flex items-center justify-center gap-1.5 shrink-0">
                            <i class="fas fa-filter text-[10px]"></i>
                            <span>Filter</span>
                        </button>
                    </form>

                    <a href="{{ route('guru.rekap-absen.export', [$jadwal->id, 'bulan' => $bulan]) }}" 
                       class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-xs font-bold tracking-wide text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200/80 rounded-xl transition-all shadow-xs">
                        <i class="fas fa-file-excel text-emerald-600 text-sm"></i>
                        <span>Export Excel</span>
                    </a>
                </div>

            </div>
        </div>
    </div>

    <!-- Main Container Data -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- TAMPILAN MOBILE: Card Stack List (Tampil di < md) -->
        <div class="block md:hidden space-y-4">
            <div class="flex items-center justify-between px-1 pb-1">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Daftar Siswa ({{ count($siswas) }})</span>
                <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-100">Bulan: {{ $bulan }}</span>
            </div>

            @forelse($siswas as $idx => $s)
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 relative shadow-sm">
                <!-- Badge Nomor Urut -->
                <div class="absolute top-4 right-4 bg-slate-100 border border-slate-200/60 rounded-lg px-2.5 py-0.5 text-xs font-bold text-slate-600">
                    #{{ $idx + 1 }}
                </div>

                <!-- Informasi Siswa -->
                <div class="mb-4 pr-12">
                    <h3 class="font-extrabold text-base text-slate-900 leading-snug">{{ $s['nama'] }}</h3>
                    <p class="text-xs font-medium text-slate-400 mt-0.5">NISN: <span class="font-mono text-slate-600">{{ $s['nisn'] ?? '-' }}</span></p>
                </div>

                <!-- Grid Presensi Badge -->
                <div class="grid grid-cols-4 gap-2 pt-3 border-t border-slate-100 text-center">
                    <div class="bg-emerald-50/60 p-2 rounded-xl border border-emerald-100">
                        <span class="block text-[10px] uppercase font-bold text-emerald-700">Hadir</span>
                        <span class="text-base font-extrabold text-emerald-700">{{ $s['hadir'] }}</span>
                    </div>
                    <div class="bg-amber-50/60 p-2 rounded-xl border border-amber-100">
                        <span class="block text-[10px] uppercase font-bold text-amber-700">Izin</span>
                        <span class="text-base font-extrabold text-amber-700">{{ $s['izin'] }}</span>
                    </div>
                    <div class="bg-sky-50/60 p-2 rounded-xl border border-sky-100">
                        <span class="block text-[10px] uppercase font-bold text-sky-700">Sakit</span>
                        <span class="text-base font-extrabold text-sky-700">{{ $s['sakit'] }}</span>
                    </div>
                    <div class="bg-rose-50/60 p-2 rounded-xl border border-rose-100">
                        <span class="block text-[10px] uppercase font-bold text-rose-700">Alfa</span>
                        <span class="text-base font-extrabold text-rose-700">{{ $s['alfa'] }}</span>
                    </div>
                </div>

                <!-- Total Pertemuan Footer -->
                <div class="mt-3 pt-3 border-t border-slate-100 flex justify-between items-center text-xs">
                    <span class="text-slate-500 font-medium">Total Pertemuan:</span>
                    <span class="font-bold text-slate-800 bg-slate-100 px-2.5 py-1 rounded-lg border border-slate-200/60">{{ $s['total'] }} Sesi</span>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-2xl border border-slate-200/80 p-8 text-center text-slate-500">
                <i class="fas fa-folder-open text-3xl text-emerald-600/40 mb-2"></i>
                <p class="text-sm font-medium">Belum ada data presensi siswa untuk periode ini.</p>
            </div>
            @endforelse
        </div>

        <!-- TAMPILAN DESKTOP/TABLET: Modern Table (Tampil pada layar >= md) -->
        <div class="hidden md:block bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-900 text-white text-[11px] uppercase tracking-wider font-bold">
                            <th class="py-4 px-4 w-12 text-center">No</th>
                            <th class="py-4 px-6">Identitas Siswa</th>
                            <th class="py-4 px-4 text-center text-emerald-400">Hadir (H)</th>
                            <th class="py-4 px-4 text-center text-amber-300">Izin (I)</th>
                            <th class="py-4 px-4 text-center text-sky-300">Sakit (S)</th>
                            <th class="py-4 px-4 text-center text-rose-400">Alfa (A)</th>
                            <th class="py-4 px-4 text-center">Total Sesi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm font-medium">
                        @forelse($siswas as $idx => $s)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-4 px-4 text-center text-slate-400 font-mono text-xs">
                                {{ sprintf('%02d', $idx + 1) }}
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-900 text-base leading-snug">{{ $s['nama'] }}</div>
                                <div class="text-xs text-slate-400 font-mono">NISN: {{ $s['nisn'] ?? '-' }}</div>
                            </td>
                            <td class="py-4 px-4 text-center font-bold text-emerald-600 bg-emerald-50/30">
                                {{ $s['hadir'] }}
                            </td>
                            <td class="py-4 px-4 text-center font-bold text-amber-600 bg-amber-50/30">
                                {{ $s['izin'] }}
                            </td>
                            <td class="py-4 px-4 text-center font-bold text-sky-600 bg-sky-50/30">
                                {{ $s['sakit'] }}
                            </td>
                            <td class="py-4 px-4 text-center font-bold text-rose-600 bg-rose-50/30">
                                {{ $s['alfa'] }}
                            </td>
                            <td class="py-4 px-4 text-center font-extrabold text-slate-800">
                                {{ $s['total'] }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <i class="fas fa-folder-open block text-3xl mb-2 text-emerald-600/30"></i>
                                Belum ada data presensi siswa yang dicatat untuk kelas ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Footer Sub-info -->
            <div class="bg-slate-50 border-t border-slate-100 px-6 py-3 text-xs text-slate-400 flex justify-between items-center font-medium">
                <span>* Data diperbarui berdasarkan rekapitulasi bulanan</span>
                <span class="text-emerald-700 font-bold">Dokumen Presensi Resmi</span>
            </div>
        </div>

    </div>
</div>
@endsection