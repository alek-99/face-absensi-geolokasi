@extends('layouts.guru')
@section('title', 'Rekap Absen Siswa')
@section('page_title', 'Rekap Absen Siswa')

@section('content')
<div class="min-h-screen bg-[#FAF8F5] text-slate-800 pb-16 font-serif">

    <!-- Header / Title Section (Classic Institutional Style) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-6">
        <div class="bg-white rounded-xl border border-amber-900/10 shadow-sm p-6 sm:p-8 relative overflow-hidden">
            <!-- Decorative Classic Corner/Border Accents -->
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-800 via-amber-600 to-emerald-900"></div>
            
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-emerald-900/5 border border-emerald-800/20 flex items-center justify-center text-emerald-800 shrink-0">
                        <i class="fas fa-book-bookmark text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold font-serif text-slate-900 tracking-tight">
                            Rekap Absensi Siswa
                        </h1>
                        <p class="text-xs sm:text-sm text-slate-600 font-sans mt-1">
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
            <div class="bg-white rounded-xl p-10 text-center border border-amber-900/10 shadow-sm font-sans">
                <div class="w-16 h-16 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl border border-slate-200">
                    <i class="fas fa-folder-open"></i>
                </div>
                <h3 class="text-lg font-bold font-serif text-slate-800 mb-1">Belum Ada Jadwal Mengajar</h3>
                <p class="text-xs sm:text-sm text-slate-500 max-w-md mx-auto">
                    Anda belum memiliki jadwal mengajar aktif yang terdaftar untuk melakukan rekapitulasi presensi.
                </p>
            </div>
        @else
            <!-- Grid Card List -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($jadwals as $j)
                <div class="group relative bg-white rounded-xl border border-amber-900/15 shadow-sm hover:shadow-xl hover:border-emerald-800/40 transition-all duration-300 p-6 flex flex-col justify-between overflow-hidden">
                    
                    <!-- Decorative Top Border Accent -->
                    <div class="absolute top-0 left-0 right-0 h-1 bg-emerald-800 group-hover:bg-amber-600 transition-colors duration-300"></div>

                    <!-- BACKGROUND LOGO WATERMARK (OPSI B) -->
                    <div class="absolute -bottom-6 -right-6 w-40 h-40 opacity-[0.06] group-hover:opacity-[0.12] transition-all duration-300 pointer-events-none select-none transform group-hover:scale-105">
                        <img src="{{ asset('storage/images/logo.png') }}" 
                             alt="Logo Watermark" 
                             class="w-full h-full object-contain filter grayscale" />
                    </div>

                    <!-- Main Card Content -->
                    <div class="relative z-10">
                        <!-- Header Card Badge & Hari -->
                        <div class="flex items-center justify-between mb-4">
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold font-sans text-emerald-900 bg-emerald-50 px-3 py-1 rounded border border-emerald-800/20">
                                <i class="fas fa-door-open text-emerald-800"></i> Kelas {{ $j->kelas?->nama_kelas ?? '-' }}
                            </span>
                            <span class="text-[11px] font-sans font-medium text-slate-600 uppercase tracking-wider bg-slate-100/80 border border-slate-200 px-2 py-0.5 rounded">
                                {{ $j->hari }}
                            </span>
                        </div>

                        <!-- Nama Mata Pelajaran -->
                        <h3 class="text-xl font-bold font-serif text-slate-900 mb-3 group-hover:text-emerald-900 transition-colors leading-snug">
                            {{ $j->mapel?->nama_mapel ?? '-' }}
                        </h3>

                        <!-- Info Jam Mengajar -->
                        <div class="flex items-center gap-2 text-xs font-sans text-slate-600 mb-6 bg-[#FAF8F5] p-2.5 rounded-lg border border-amber-900/10">
                            <i class="far fa-clock text-amber-700 shrink-0"></i>
                            <span>Waktu: <strong class="text-slate-800">{{ $j->jam_masuk?->format('H:i') }} - {{ $j->jam_pulang?->format('H:i') }}</strong> WIB</span>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="relative z-10 pt-2 font-sans">
                        <a href="{{ route('guru.rekap-absen.detail', $j->id) }}" 
                           class="inline-flex items-center justify-center gap-2 w-full py-2.5 px-4 text-xs sm:text-sm font-semibold tracking-wide text-emerald-900 bg-emerald-50/50 hover:bg-emerald-800 hover:text-white rounded-lg transition-all duration-200 border border-emerald-800/30 hover:border-emerald-800 shadow-sm active:scale-[0.99]">
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