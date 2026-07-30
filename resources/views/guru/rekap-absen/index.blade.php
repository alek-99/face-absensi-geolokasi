@extends('layouts.guru')
@section('title', 'Rekap Absen Siswa')
@section('page_title', 'Rekap Absen Siswa')

@section('content')
<div class="min-h-screen bg-slate-50 text-slate-800 pb-16 font-sans">

    <!-- Header Section (Full Width atas, kiri, kanan) -->
    <div class="w-full bg-white border-b border-slate-200/80 shadow-xs mb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-xl bg-emerald-600/10 border border-emerald-500/20 flex items-center justify-center text-emerald-600 shrink-0 shadow-xs">
                        <i class="fas fa-book-bookmark text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                            Rekap Absensi Siswa
                        </h1>
                        <p class="text-xs sm:text-sm text-slate-500 mt-1">
                            Pilih mata pelajaran dan kelas untuk meninjau rekapitulasi kehadiran.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @if($jadwals->isEmpty())
            <!-- Empty State -->
            <div class="bg-white rounded-2xl p-12 text-center border border-slate-200/80 shadow-sm">
                <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl border border-emerald-100">
                    <i class="fas fa-folder-open"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-1">Belum Ada Jadwal Mengajar</h3>
                <p class="text-xs sm:text-sm text-slate-500 max-w-md mx-auto">
                    Anda belum memiliki jadwal mengajar aktif yang terdaftar untuk melakukan rekapitulasi presensi.
                </p>
            </div>
        @else
            <!-- Grid Card List -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($jadwals as $j)
                <div class="group relative bg-white rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-xl hover:border-emerald-500/50 transition-all duration-300 p-6 flex flex-col justify-between overflow-hidden">

                    <!-- BACKGROUND LOGO WATERMARK -->
                    <div class="absolute -bottom-6 -right-6 w-36 h-36 opacity-[0.05] group-hover:opacity-[0.10] transition-all duration-300 pointer-events-none select-none transform group-hover:scale-110">
                        <img src="{{ asset('storage/images/logo.png') }}" 
                             alt="Logo Watermark" 
                             class="w-full h-full object-contain filter grayscale" />
                    </div>

                    <!-- Main Card Content -->
                    <div class="relative z-10">
                        <!-- Header Card Badge & Hari -->
                        <div class="flex items-center justify-between gap-2 mb-4">
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-200/60 shadow-2xs">
                                <i class="fas fa-door-open text-emerald-600"></i> Kelas {{ $j->kelas?->nama_kelas ?? '-' }}
                            </span>
                            <span class="text-[11px] font-bold text-emerald-800 uppercase tracking-wider bg-emerald-100/60 px-2.5 py-1 rounded-md border border-emerald-200/40">
                                {{ $j->hari }}
                            </span>
                        </div>

                        <!-- Nama Mata Pelajaran -->
                        <h3 class="text-xl font-extrabold text-slate-900 mb-4 group-hover:text-emerald-600 transition-colors leading-snug">
                            {{ $j->mapel?->nama_mapel ?? '-' }}
                        </h3>

                        <!-- Info Jam Mengajar -->
                        <div class="flex items-center gap-2.5 text-xs text-slate-600 mb-6 bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <i class="far fa-clock text-emerald-600 text-sm shrink-0"></i>
                            <span>Waktu: <strong class="text-slate-800 font-bold">{{ $j->jam_masuk?->format('H:i') }} - {{ $j->jam_pulang?->format('H:i') }}</strong> WIB</span>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="relative z-10 pt-2">
                        <a href="{{ route('guru.rekap-absen.detail', $j->id) }}" 
                           class="inline-flex items-center justify-center gap-2 w-full py-2.5 px-4 text-xs sm:text-sm font-bold tracking-wide text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl transition-all duration-200 shadow-md shadow-emerald-600/20 active:scale-[0.98]">
                            <i class="fas fa-file-signature text-xs"></i> Lihat Data Absensi
                        </a>
                    </div>

                </div>
                @endforeach
            </div>
        @endif

    </div>
</div>
@endsection