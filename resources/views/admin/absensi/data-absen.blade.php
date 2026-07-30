@extends('layouts.admin')
@section('title', 'Data Absensi')

@section('content')
<div class="space-y-6">

    {{-- Header Halaman --}}
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 bg-white/70 backdrop-blur-md p-4  shadow-sm border-l-4 border-green-600">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-calendar-days text-green-500"></i>
                Riwayat & Data Absensi Guru
            </h1>
            <p class="text-sm text-slate-500">Melihat, memantau, dan memfilter riwayat rekapitulasi kehadiran seluruh guru.</p>
        </div>
    </div>

    {{-- Grid 2 Kolom: Kiri (Tabel Data), Kanan (Export & Filter) --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        {{-- KOLOM KIRI: Tabel Data Absensi (8/12 Grid) --}}
        <div class="lg:col-span-8 order-2 lg:order-1 bg-white/70 backdrop-blur-md rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 backdrop-blur-sm border-b border-slate-200/80 text-xs font-semibold tracking-wider text-slate-500 uppercase">
                            <th class="px-5 py-4"><i class="fa-solid fa-hashtag mr-1"></i> No</th>
                            <th class="px-5 py-4"><i class="fa-solid fa-calendar-day mr-1"></i> Tanggal</th>
                            <th class="px-5 py-4"><i class="fa-solid fa-user-tie mr-1"></i> Nama Guru</th>
                            <th class="px-5 py-4"><i class="fa-solid fa-clipboard-check mr-1"></i> Status</th>
                            <th class="px-5 py-4"><i class="fa-solid fa-file-invoice mr-1"></i> Ket.</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100/80 text-sm text-slate-600">
                        @forelse($riwayat as $index => $item)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-5 py-4 whitespace-nowrap text-slate-400 font-medium">
                                    {{ $riwayat->firstItem() + $index }}
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap font-semibold text-slate-700">
                                    {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="font-semibold text-slate-800">{{ $item->guru->user->name ?? $item->guru->nama ?? 'N/A' }}</div>
                                    <div class="text-xs text-slate-400">NIP: {{ $item->guru->nip ?? '-' }}</div>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    @switch(strtolower($item->status))
                                        @case('hadir')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <i class="fa-solid fa-circle-check text-[10px]"></i> Hadir
                                            </span>
                                            @break

                                        @case('izin')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                                <i class="fa-solid fa-envelope text-[10px]"></i> Izin
                                            </span>
                                            @break

                                        @case('sakit')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                                <i class="fa-solid fa-square-plus text-[10px]"></i> Sakit
                                            </span>
                                            @break

                                        @case('alpa')
                                        @case('alpha')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                                <i class="fa-solid fa-circle-exclamation text-[10px]"></i> Alpa
                                            </span>
                                            @break

                                        @default
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                                {{ ucfirst($item->status) }}
                                            </span>
                                    @endswitch
                                </td>
                                <td class="px-5 py-4 max-w-[180px] truncate text-slate-500" title="{{ $item->keterangan }}">
                                    {{ $item->keterangan ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center text-slate-400">
                                    <i class="fa-regular fa-folder-open text-4xl mb-3 block text-slate-300"></i>
                                    Tidak ada data absensi yang sesuai dengan kriteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($riwayat->hasPages())
                <div class="px-6 py-4 bg-slate-50/60 border-t border-slate-200/80">
                    {{ $riwayat->links() }}
                </div>
            @endif
        </div>

        {{-- KOLOM KANAN: Panel Export & Filter (4/12 Grid) --}}
        <div class="lg:col-span-4 order-1 lg:order-2 space-y-6">

            {{-- Panel Export Excel --}}
            <div class="bg-emerald-50/60 backdrop-blur-md rounded-2xl shadow-sm border border-emerald-200/80 p-6">
                <h2 class="text-xs font-bold uppercase text-emerald-800 tracking-wider mb-2 flex items-center gap-1.5">
                    <i class="fa-solid fa-file-excel text-emerald-600 text-base"></i> Cetak Rekapitulasi Excel
                </h2>
                <p class="text-xs text-emerald-700 mb-4">
                    Unduh file Excel statistik lengkap beserta KOP & Rekap bulanan.
                </p>

                <form action="{{ route('admin.absensi.export-excel') }}" method="GET" class="space-y-3">
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="text-[11px] font-semibold text-emerald-900 mb-1 block">Bulan</label>
                            <select name="bulan" class="w-full text-xs rounded-xl bg-white border-emerald-200 text-slate-700 focus:border-emerald-500 focus:ring-emerald-500 p-2.5">
                                @for ($m = 1; $m <= 12; $m++)
                                    <option value="{{ sprintf('%02d', $m) }}" {{ request('bulan', date('m')) == sprintf('%02d', $m) ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-semibold text-emerald-900 mb-1 block">Tahun</label>
                            <select name="tahun" class="w-full text-xs rounded-xl bg-white border-emerald-200 text-slate-700 focus:border-emerald-500 focus:ring-emerald-500 p-2.5">
                                @for ($y = date('Y'); $y >= date('Y') - 3; $y--)
                                    <option value="{{ $y }}" {{ request('tahun', date('Y')) == $y ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    {{-- Pertahankan filter guru dan status jika sedang aktif --}}
                    <input type="hidden" name="guru_id" value="{{ request('guru_id') }}">
                    <input type="hidden" name="status" value="{{ request('status') }}">

                    <button type="submit" class="w-full py-2.5 text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl shadow-sm transition-all flex items-center justify-center gap-2">
                        <i class="fa-solid fa-download"></i> Unduh Rekap Excel
                    </button>
                </form>
            </div>

            {{-- Panel Filter --}}
            <div class="bg-white/70 backdrop-blur-md rounded-2xl shadow-sm border border-slate-200/80 p-6">
                <h2 class="text-xs font-bold uppercase text-slate-400 tracking-wider mb-4 flex items-center gap-1.5">
                    <i class="fa-solid fa-sliders text-[#7367f0]"></i> Filter Pencarian
                </h2>

                <form action="{{ route('admin.absensi.index') }}" method="GET" class="flex flex-col gap-4">

                    {{-- Pilih Guru --}}
                    <div class="flex flex-col gap-1.5">
                        <label for="guru_id" class="text-xs font-semibold text-slate-600 flex items-center gap-1">
                            <i class="fa-solid fa-user-tie text-slate-400"></i> Pilih Guru
                        </label>
                        <select name="guru_id" id="guru_id" class="w-full text-sm rounded-xl bg-slate-50/80 border-slate-200 text-slate-700 focus:border-[#7367f0] focus:ring-[#7367f0] p-2.5">
                            <option value="">-- Semua Guru --</option>
                            @foreach ($gurus as $guru)
                                <option value="{{ $guru->id }}" {{ request('guru_id') == $guru->id ? 'selected' : '' }}>
                                    {{ $guru->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Status Kehadiran --}}
                    <div class="flex flex-col gap-1.5">
                        <label for="status" class="text-xs font-semibold text-slate-600 flex items-center gap-1">
                            <i class="fa-solid fa-circle-question text-slate-400"></i> Kehadiran
                        </label>
                        <select name="status" id="status" class="w-full text-sm rounded-xl bg-slate-50/80 border-slate-200 text-slate-700 focus:border-[#7367f0] focus:ring-[#7367f0] p-2.5">
                            <option value="">-- Semua Status --</option>
                            <option value="hadir" {{ request('status') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                            <option value="izin" {{ request('status') == 'izin' ? 'selected' : '' }}>Izin</option>
                            <option value="sakit" {{ request('status') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                            <option value="alpa" {{ request('status') == 'alpa' || request('status') == 'alpha' ? 'selected' : '' }}>Alpa</option>
                        </select>
                    </div>

                    {{-- Rentang Tanggal --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 xl:grid-cols-2 gap-3">
                        <div class="flex flex-col gap-1.5">
                            <label for="tanggal_awal" class="text-xs font-semibold text-slate-600 flex items-center gap-1">
                                <i class="fa-solid fa-calendar-minus text-slate-400"></i> Tanggal Mulai
                            </label>
                            <input type="date" name="tanggal_awal" id="tanggal_awal" value="{{ request('tanggal_awal') }}" class="w-full text-sm rounded-xl bg-slate-50/80 border-slate-200 text-slate-700 focus:border-[#7367f0] focus:ring-[#7367f0] p-2.5">
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label for="tanggal_akhir" class="text-xs font-semibold text-slate-600 flex items-center gap-1">
                                <i class="fa-solid fa-calendar-plus text-slate-400"></i> Tanggal Akhir
                            </label>
                            <input type="date" name="tanggal_akhir" id="tanggal_akhir" value="{{ request('tanggal_akhir') }}" class="w-full text-sm rounded-xl bg-slate-50/80 border-slate-200 text-slate-700 focus:border-[#7367f0] focus:ring-[#7367f0] p-2.5">
                        </div>
                    </div>

                    {{-- Tombol Filter --}}
                    <div class="flex flex-col gap-2 mt-2 pt-4 border-t border-slate-100">
                        <button type="submit" class="w-full py-2.5 text-sm font-semibold bg-[#7367f0] hover:bg-[#6054e0] text-white rounded-xl shadow-sm transition-all flex items-center justify-center gap-1.5">
                            <i class="fa-solid fa-magnifying-glass"></i> Terapkan Filter
                        </button>
                        <a href="{{ route('admin.absensi.index') }}" class="w-full py-2 text-sm font-semibold bg-slate-100/80 hover:bg-slate-200/80 text-slate-600 border border-slate-200/80 rounded-xl transition-all flex items-center justify-center gap-1.5">
                            <i class="fa-solid fa-rotate-left"></i> Reset Filter
                        </a>
                    </div>
                </form>
            </div>

        </div>

    </div>
</div>
@endsection