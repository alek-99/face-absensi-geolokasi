@extends('layouts.guru')
@section('title','Detail Rekap Absen Siswa')
@section('page_title','Detail Rekap Absen-Siswa')

@section('content')
{{-- Font Import untuk sentuhan Klasik/Elegan (Serif Font) --}}
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,400&family=Lora:ital,wght@0,400;0,500;0,600;1,400&display=swap');
    
    .font-serif-title { font-family: 'Playfair Display', Georgia, serif; }
    .font-serif-body { font-family: 'Lora', Georgia, serif; }
</style>

<div class="min-h-screen bg-[#FDFBF7] py-6 px-3 sm:px-6 lg:px-8 font-serif-body text-[#2C221E]">
    
    <!-- Link Kembali -->
    <div class="mb-5 max-w-5xl mx-auto">
        <a href="{{ route('guru.rekap-absen.index') }}" 
           class="inline-flex items-center gap-2 text-xs uppercase tracking-widest font-semibold text-[#6B5E54] hover:text-[#8C2D19] transition-colors border-b border-transparent hover:border-[#8C2D19] pb-0.5">
            <i class="fas fa-arrow-left text-[#8C2D19]"></i> Kembali ke Daftar Rekap
        </a>
    </div>

    <div class="max-w-5xl mx-auto space-y-6">

        <!-- Card Header Info & Filter -->
        <div class="bg-[#F8F4EC] border-2 border-[#DCD1C4] rounded-none p-5 sm:p-7 shadow-[4px_4px_0px_0px_rgba(44,34,30,0.08)] relative overflow-hidden">
            <!-- Decorative Corner Borders (Vintage Frame Touch) -->
            <div class="absolute top-1 left-1 w-3 h-3 border-t-2 border-l-2 border-[#8C2D19]/40 pointer-events-none"></div>
            <div class="absolute top-1 right-1 w-3 h-3 border-t-2 border-r-2 border-[#8C2D19]/40 pointer-events-none"></div>
            <div class="absolute bottom-1 left-1 w-3 h-3 border-b-2 border-l-2 border-[#8C2D19]/40 pointer-events-none"></div>
            <div class="absolute bottom-1 right-1 w-3 h-3 border-b-2 border-r-2 border-[#8C2D19]/40 pointer-events-none"></div>

            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <!-- Info Utama -->
                <div class="space-y-1">
                    <span class="text-[10px] uppercase tracking-[0.2em] font-bold text-[#8C2D19] block">
                        Jurnal Presensi Akademik
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-bold font-serif-title text-[#2C221E] tracking-tight flex items-center gap-2">
                        <i class="fas fa-book-open text-[#8C2D19] text-xl"></i>
                        <span>{{ $jadwal->mapel?->nama_mapel }}</span>
                    </h1>
                    <div class="text-sm text-[#5A4D41] pt-1 flex items-center gap-2">
                        <span class="italic">Kelas Registered:</span>
                        <span class="font-semibold px-2 py-0.5 bg-[#EFE9DD] border border-[#D1C5B4] text-[#2C221E]">
                            {{ $jadwal->kelas?->nama_kelas }}
                        </span>
                    </div>
                </div>

                <!-- Form Filter & Action (Mobile-First Layout) -->
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 border-t border-[#DCD1C4] md:border-t-0 pt-4 md:pt-0">
                    <form method="GET" action="{{ route('guru.rekap-absen.detail', $jadwal->id) }}" class="flex items-center gap-2">
                        <div class="relative flex-1 sm:w-auto">
                            <input type="month" name="bulan" value="{{ $bulan }}" 
                                   class="w-full text-xs font-serif-body bg-[#FDFBF7] border border-[#B8A99A] text-[#2C221E] px-3 py-2 focus:outline-none focus:border-[#8C2D19] focus:ring-1 focus:ring-[#8C2D19] shadow-inner">
                        </div>
                        <button type="submit" 
                                class="px-4 py-2 text-xs font-bold uppercase tracking-wider text-[#FDFBF7] bg-[#2C221E] hover:bg-[#42332D] transition-colors border border-[#2C221E] shadow-sm flex items-center justify-center gap-1.5">
                            <i class="fas fa-filter text-[10px]"></i>
                            <span>Filter</span>
                        </button>
                    </form>

                    <a href="{{ route('guru.rekap-absen.export', [$jadwal->id, 'bulan' => $bulan]) }}" 
                       class="inline-flex items-center justify-center gap-2 px-4 py-2 text-xs font-bold uppercase tracking-wider text-[#2C221E] bg-[#E8DCC4] hover:bg-[#DECFA3] border border-[#B8A99A] transition-colors shadow-sm">
                        <i class="fas fa-file-excel text-[#2D5A27]"></i>
                        <span>Export Excel</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- TAMPILAN MOBILE: Card Stack List (Tampil hanya di layar HP / < md) -->
        <div class="block md:hidden space-y-4">
            <div class="flex items-center justify-between px-1 pb-1 border-b border-[#DCD1C4]">
                <span class="text-xs font-bold uppercase tracking-widest text-[#6B5E54]">Daftar Siswa ({{ count($siswas) }})</span>
                <span class="text-[11px] italic text-[#8C2D19]">Bulan: {{ $bulan }}</span>
            </div>

            @forelse($siswas as $idx => $s)
            <div class="bg-[#F8F4EC] border border-[#DCD1C4] p-4 relative shadow-sm">
                <!-- Badge Nomor Urut Klasik -->
                <div class="absolute top-0 right-0 bg-[#EFE9DD] border-b border-l border-[#DCD1C4] px-2.5 py-0.5 text-[11px] font-bold text-[#5A4D41]">
                    #{{ $idx + 1 }}
                </div>

                <!-- Informasi Siswa -->
                <div class="mb-3 pr-8">
                    <h3 class="font-serif-title font-bold text-lg text-[#2C221E] leading-snug">{{ $s['nama'] }}</h3>
                    <p class="text-xs text-[#6B5E54] tracking-wider">NISN: <span class="font-mono">{{ $s['nisn'] ?? '-' }}</span></p>
                </div>

                <!-- Grid Presensi Vintage Badge -->
                <div class="grid grid-cols-4 gap-2 pt-3 border-t border-[#EFE9DD] text-center">
                    <div class="bg-[#FDFBF7] p-2 border border-[#E3DAC9]">
                        <span class="block text-[9px] uppercase tracking-wider text-[#2D5A27] font-bold">Hadir</span>
                        <span class="text-base font-bold text-[#2D5A27]">{{ $s['hadir'] }}</span>
                    </div>
                    <div class="bg-[#FDFBF7] p-2 border border-[#E3DAC9]">
                        <span class="block text-[9px] uppercase tracking-wider text-[#B86B18] font-bold">Izin</span>
                        <span class="text-base font-bold text-[#B86B18]">{{ $s['izin'] }}</span>
                    </div>
                    <div class="bg-[#FDFBF7] p-2 border border-[#E3DAC9]">
                        <span class="block text-[9px] uppercase tracking-wider text-[#205493] font-bold">Sakit</span>
                        <span class="text-base font-bold text-[#205493]">{{ $s['sakit'] }}</span>
                    </div>
                    <div class="bg-[#FDFBF7] p-2 border border-[#E3DAC9]">
                        <span class="block text-[9px] uppercase tracking-wider text-[#8C2D19] font-bold">Alfa</span>
                        <span class="text-base font-bold text-[#8C2D19]">{{ $s['alfa'] }}</span>
                    </div>
                </div>

                <!-- Total Pertemuan Footer -->
                <div class="mt-3 pt-2 border-t border-dashed border-[#DCD1C4] flex justify-between items-center text-xs">
                    <span class="italic text-[#6B5E54]">Total Pertemuan:</span>
                    <span class="font-bold text-[#2C221E] bg-[#EFE9DD] px-2 py-0.5 border border-[#D1C5B4]">{{ $s['total'] }} Sesi</span>
                </div>
            </div>
            @empty
            <div class="bg-[#F8F4EC] border border-[#DCD1C4] p-8 text-center text-[#6B5E54] italic">
                Belum ada data presensi siswa untuk periode ini.
            </div>
            @endforelse
        </div>

        <!-- TAMPILAN DESKTOP/TABLET: Klasik Ledger Table (Tampil pada layar >= md) -->
        <div class="hidden md:block bg-[#F8F4EC] border-2 border-[#DCD1C4] shadow-[4px_4px_0px_0px_rgba(44,34,30,0.08)] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#2C221E] text-[#FDFBF7] text-[11px] uppercase tracking-[0.15em]">
                            <th class="py-3.5 px-4 w-12 text-center border-r border-[#42332D]">No</th>
                            <th class="py-3.5 px-6 border-r border-[#42332D]">Identitas Siswa</th>
                            <th class="py-3.5 px-4 text-center border-r border-[#42332D] text-[#A3E2A0]">Hadir (H)</th>
                            <th class="py-3.5 px-4 text-center border-r border-[#42332D] text-[#FCE196]">Izin (I)</th>
                            <th class="py-3.5 px-4 text-center border-r border-[#42332D] text-[#A6C8FF]">Sakit (S)</th>
                            <th class="py-3.5 px-4 text-center border-r border-[#42332D] text-[#FFA8A8]">Alfa (A)</th>
                            <th class="py-3.5 px-4 text-center">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#DCD1C4] text-sm">
                        @forelse($siswas as $idx => $s)
                        <tr class="hover:bg-[#F2EBDC] transition-colors odd:bg-[#F8F4EC] even:bg-[#F3EDDF]">
                            <td class="py-4 px-4 text-center text-[#6B5E54] font-mono text-xs border-r border-[#E5DACB]">
                                {{ sprintf('%02d', $idx + 1) }}
                            </td>
                            <td class="py-4 px-6 border-r border-[#E5DACB]">
                                <div class="font-serif-title font-bold text-[#2C221E] text-base leading-snug">{{ $s['nama'] }}</div>
                                <div class="text-xs text-[#6B5E54] font-mono">NISN: {{ $s['nisn'] ?? '-' }}</div>
                            </td>
                            <td class="py-4 px-4 text-center font-bold text-[#2D5A27] border-r border-[#E5DACB] bg-[#2D5A27]/5">
                                {{ $s['hadir'] }}
                            </td>
                            <td class="py-4 px-4 text-center font-bold text-[#B86B18] border-r border-[#E5DACB] bg-[#B86B18]/5">
                                {{ $s['izin'] }}
                            </td>
                            <td class="py-4 px-4 text-center font-bold text-[#205493] border-r border-[#E5DACB] bg-[#205493]/5">
                                {{ $s['sakit'] }}
                            </td>
                            <td class="py-4 px-4 text-center font-bold text-[#8C2D19] border-r border-[#E5DACB] bg-[#8C2D19]/5">
                                {{ $s['alfa'] }}
                            </td>
                            <td class="py-4 px-4 text-center font-serif-title font-bold text-[#2C221E]">
                                {{ $s['total'] }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-[#6B5E54] italic bg-[#F8F4EC]">
                                <i class="fas fa-folder-open block text-2xl mb-2 text-[#B8A99A]"></i>
                                Belum ada data presensi siswa yang dicatat untuk kelas ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Footer Ledger Sub-info -->
            <div class="bg-[#EFE9DD] border-t border-[#DCD1C4] px-6 py-3 text-xs text-[#6B5E54] flex justify-between items-center italic">
                <span>* Data diperbarui berdasarkan rekapitulasi bulanan</span>
                <span>Dokumen Resmi Presensi</span>
            </div>
        </div>

    </div>
</div>
@endsection