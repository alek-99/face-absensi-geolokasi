@extends('layouts.guru')
@section('title','Rekap Absen Siswa')
@section('page_title','Rekap Absen-Siswa')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-800 flex items-center gap-2">
            <i class="fas fa-file-invoice text-indigo-600"></i> Rekap Absensi Siswa
        </h1>
        <p class="text-sm text-slate-500 mt-1">Pilih mata pelajaran dan kelas untuk melihat rekap kehadiran siswa.</p>
    </div>

    @if($jadwals->isEmpty())
        <div class="bg-white rounded-2xl p-12 text-center border border-slate-100 shadow-sm">
            <i class="fas fa-folder-open text-4xl text-slate-300 mb-3"></i>
            <p class="text-slate-500 font-medium">Anda belum memiliki jadwal mengajar aktif.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($jadwals as $j)
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow p-6 flex flex-col justify-between">
                <div>
                    <span class="inline-flex items-center gap-1 text-xs font-medium text-indigo-700 bg-indigo-50 px-2.5 py-1 rounded-lg mb-3">
                        <i class="fas fa-school"></i> Kelas {{ $j->kelas?->nama_kelas ?? '-' }}
                    </span>
                    <h3 class="text-lg font-bold text-slate-800 mb-1">{{ $j->mapel?->nama_mapel ?? '-' }}</h3>
                    <p class="text-xs text-slate-400 mb-6">Hari {{ $j->hari }} ({{ $j->jam_masuk?->format('H:i') }} - {{ $j->jam_pulang?->format('H:i') }})</p>
                </div>

                <a href="{{ route('guru.rekap-absen.detail', $j->id) }}" 
                   class="inline-flex items-center justify-center gap-2 w-full py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition-colors shadow-sm">
                    <i class="fas fa-eye"></i> Lihat Data Absensi
                </a>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection