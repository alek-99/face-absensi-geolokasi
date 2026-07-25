@extends('layouts.admin')
@section('title', 'Data-Absensi')
@section('content')
    <div class="space-y-6">
        
        {{-- Header Halaman --}}
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-calendar-days text-[#7367f0]"></i>
                    Riwayat & Data Absensi Guru
                </h1>
                <p class="text-sm text-slate-500">Melihat, memantau, dan memfilter riwayat kehadiran seluruh guru.</p>
            </div>
            <a href="{{ route('admin.absensi.cetak-pdf', request()->all()) }}" target="_blank" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all">
                <i class="fa fa-file-pdf"></i> Cetak PDF
            </a>
        </div>

        {{-- Panel Filter Pencarian --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-xs font-bold uppercase text-slate-400 tracking-wider mb-4 flex items-center gap-1.5">
                <i class="fa-solid fa-sliders text-[#7367f0]"></i> Panel Filter Pencarian
            </h2>
          
            <form action="{{ route('admin.absensi.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                
                {{-- Pilih Guru --}}
                <div class="flex flex-col gap-1.5">
                    <label for="guru_id" class="text-xs font-semibold text-slate-600 flex items-center gap-1">
                        <i class="fa-solid fa-user-tie text-slate-400"></i> Pilih Guru
                    </label>
                    <select name="guru_id" id="guru_id" class="w-full text-sm rounded-xl bg-slate-50 border-slate-200 text-slate-700 focus:border-[#7367f0] focus:ring-[#7367f0] p-2.5">
                        <option value="">-- Semua Guru --</option>
                        @foreach($gurus as $guru)
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
                    <select name="status" id="status" class="w-full text-sm rounded-xl bg-slate-50 border-slate-200 text-slate-700 focus:border-[#7367f0] focus:ring-[#7367f0] p-2.5">
                        <option value="">-- Semua Status --</option>
                        <option value="hadir" {{ request('status') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                        <option value="izin" {{ request('status') == 'izin' ? 'selected' : '' }}>Izin</option>
                        <option value="sakit" {{ request('status') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                        <option value="alpa" {{ request('status') == 'alpa' ? 'selected' : '' }}>Alpa</option>
                    </select>
                </div>

                {{-- Tanggal Mulai --}}
                <div class="flex flex-col gap-1.5">
                    <label for="tanggal_awal" class="text-xs font-semibold text-slate-600 flex items-center gap-1">
                        <i class="fa-solid fa-calendar-minus text-slate-400"></i> Tanggal Mulai
                    </label>
                    <input type="date" name="tanggal_awal" id="tanggal_awal" value="{{ request('tanggal_awal') }}" class="w-full text-sm rounded-xl bg-slate-50 border-slate-200 text-slate-700 focus:border-[#7367f0] focus:ring-[#7367f0] p-2.5">
                </div>

                {{-- Tanggal Akhir --}}
                <div class="flex flex-col gap-1.5">
                    <label for="tanggal_akhir" class="text-xs font-semibold text-slate-600 flex items-center gap-1">
                        <i class="fa-solid fa-calendar-plus text-slate-400"></i> Tanggal Akhir
                    </label>
                    <input type="date" name="tanggal_akhir" id="tanggal_akhir" value="{{ request('tanggal_akhir') }}" class="w-full text-sm rounded-xl bg-slate-50 border-slate-200 text-slate-700 focus:border-[#7367f0] focus:ring-[#7367f0] p-2.5">
                </div>

                {{-- Tombol Aksi --}}
                <div class="sm:col-span-2 lg:col-span-4 flex justify-end items-center gap-2 mt-2 pt-4 border-t border-slate-100">
                    <a href="{{ route('admin.absensi.index') }}" class="px-4 py-2 text-sm font-semibold bg-slate-100 hover:bg-slate-200 text-slate-600 border border-slate-200 rounded-xl transition-all flex items-center gap-1.5">
                        <i class="fa-solid fa-rotate-left"></i> Reset Filter
                    </a>
                    <button type="submit" class="px-5 py-2 text-sm font-semibold bg-[#7367f0] hover:bg-[#6054e0] text-white rounded-xl shadow-sm transition-all flex items-center gap-1.5">
                        <i class="fa-solid fa-magnifying-glass"></i> Terapkan Filter
                    </button>
                </div>
            </form>
        </div>

        {{-- Tabel Data --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-xs font-semibold tracking-wider text-slate-500 uppercase">
                            <th class="px-6 py-4"><i class="fa-solid fa-hashtag mr-1"></i> No</th>
                            <th class="px-6 py-4"><i class="fa-solid fa-calendar-day mr-1"></i> Tanggal</th>
                            <th class="px-6 py-4"><i class="fa-solid fa-user-tie mr-1"></i> Nama Guru</th>
                            <th class="px-6 py-4"><i class="fa-solid fa-clipboard-check mr-1"></i> Status Kehadiran</th>
                            <th class="px-6 py-4"><i class="fa-solid fa-file-invoice mr-1"></i> Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm text-slate-600">
                        @forelse($riwayat as $index => $item)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-slate-400 font-medium">
                                    {{ $riwayat->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-semibold text-slate-700">
                                    {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-semibold text-slate-800">{{ $item->guru->user->name ?? 'N/A' }}</div>
                                    <div class="text-xs text-slate-400">NIP: {{ $item->guru->nip ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
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
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                                <i class="fa-solid fa-circle-exclamation text-[10px]"></i> Alpa
                                            </span>
                                            @break
                                        @default
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                                {{ $item->status }}
                                            </span>
                                    @endswitch
                                </td>
                                <td class="px-6 py-4 max-w-xs truncate text-slate-500">
                                    {{ $item->keterangan ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center text-slate-400">
                                    <i class="fa-regular fa-folder-open text-4xl mb-3 block text-slate-300"></i>
                                    Tidak ada data absensi yang sesuai dengan filter pencarian.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($riwayat->hasPages())
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">
                    {{ $riwayat->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection