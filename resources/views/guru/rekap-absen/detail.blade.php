@extends('layouts.guru')
@section('title','Detail Rekap Absen Siswa')
@section('page_title','Detail Rekap Absen-Siswa')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Link Kembali -->
    <div class="mb-6">
        <a href="{{ route('guru.rekap-absen.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-indigo-600 transition-colors">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Rekap
        </a>
    </div>

    <!-- Header Info & Filter -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 mb-1 flex items-center gap-2">
                <i class="fas fa-book-open text-indigo-600"></i> {{ $jadwal->mapel?->nama_mapel }}
            </h1>
            <p class="text-sm text-slate-500">
                Kelas: <span class="font-semibold text-slate-700">{{ $jadwal->kelas?->nama_kelas }}</span>
            </p>
        </div>

        <!-- Form Filter Bulan & Tombol Export -->
        <div class="flex flex-wrap items-center gap-3">
            <form method="GET" action="{{ route('guru.rekap-absen.detail', $jadwal->id) }}" class="flex items-center gap-2">
                <input type="month" name="bulan" value="{{ $bulan }}" 
                       class="text-sm rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-slate-700">
                <button type="submit" class="px-4 py-2 text-sm font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                    <i class="fas fa-filter me-1"></i> Filter
                </button>
            </form>

            <a href="{{ route('guru.rekap-absen.export', [$jadwal->id, 'bulan' => $bulan]) }}" 
               class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl transition-colors shadow-sm">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
        </div>
    </div>

    <!-- Tabel Rekap Absensi -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-xs uppercase tracking-wider text-slate-500 font-semibold">
                        <th class="py-4 px-6 w-12 text-center">#</th>
                        <th class="py-4 px-6">Nama Siswa</th>
                        <th class="py-4 px-6 text-center text-emerald-600">Hadir</th>
                        <th class="py-4 px-6 text-center text-amber-600">Izin</th>
                        <th class="py-4 px-6 text-center text-blue-600">Sakit</th>
                        <th class="py-4 px-6 text-center text-red-600">Alfa</th>
                        <th class="py-4 px-6 text-center">Total Pertemuan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($siswas as $idx => $s)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="py-4 px-6 text-center text-slate-400 font-medium">{{ $idx + 1 }}</td>
                        <td class="py-4 px-6">
                            <div class="font-bold text-slate-800">{{ $s['nama'] }}</div>
                            <div class="text-xs text-slate-400">NISN: {{ $s['nisn'] ?? '-' }}</div>
                        </td>
                        <td class="py-4 px-6 text-center font-semibold text-emerald-600">{{ $s['hadir'] }}</td>
                        <td class="py-4 px-6 text-center font-semibold text-amber-600">{{ $s['izin'] }}</td>
                        <td class="py-4 px-6 text-center font-semibold text-blue-600">{{ $s['sakit'] }}</td>
                        <td class="py-4 px-6 text-center font-semibold text-red-600">{{ $s['alfa'] }}</td>
                        <td class="py-4 px-6 text-center font-bold text-slate-700">{{ $s['total'] }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-slate-400">Belum ada data siswa di kelas ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection