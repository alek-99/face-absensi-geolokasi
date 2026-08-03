@extends('layouts.admin')
@section('title', 'Data Guru')
@section('content')
<div class="space-y-6 font-sans">
    
    {{-- HEADER & CONTROL CARD (HEADER, SEARCH, & ACTION TOGETHER) --}}
    <div class="bg-white p-6 rounded-r-2xl rounded-l-none border-l-4 border-l-emerald-500 shadow-xs border-y border-r border-slate-200/80 space-y-5">
        
        {{-- Row 1: Judul Page & Tombol Utama --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <div class="p-3 bg-indigo-50 text-green-600 rounded-xl border border-indigo-100/50">
                    <i class="fa-solid fa-users-gear text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold tracking-tight text-slate-800">DATA GURU</h2>
                    <p class="text-slate-500 text-sm mt-0.5">Kelola data profil dan akun login guru dalam satu panel.</p>
                </div>
            </div>

            <div>
                <a href="{{ route('admin.guru.create') }}" class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-bold px-5 py-2.5 shadow-xs transition-all text-xl cursor-pointer">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Tambah Guru</span>
                </a>
            </div>
        </div>

        <hr class="border-slate-100">

        {{-- Row 2: Form Filter & Pencarian --}}
        <form method="GET" action="{{ route('admin.guru.index') }}" class="flex flex-col sm:flex-row gap-3 items-center">
            <div class="flex-1 w-full relative">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari Guru Berdasarkan Nama..." class="w-full pl-10 pr-4 py-2 rounded-xl bg-slate-50/80 border border-slate-200/80 text-slate-700 focus:border-indigo-500 focus:ring-indigo-500/20 focus:outline-none focus:ring-4 transition-all text-sm">
            </div>
            
            <select name="status" class="w-full sm:w-auto px-4 py-2 rounded-xl bg-slate-50/80 border border-slate-200/80 text-slate-700 focus:border-indigo-500 focus:ring-indigo-500/20 focus:outline-none focus:ring-4 transition-all text-sm cursor-pointer font-medium">
                <option value="">Semua Status</option>
                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Nonaktif</option>
            </select>

            <div class="flex gap-2 w-full sm:w-auto">
                <button type="submit" class="w-full sm:w-auto bg-slate-800 hover:bg-slate-900 text-white px-4 py-2 rounded-xl transition-colors text-xs font-semibold cursor-pointer shadow-2xs">Cari</button>
                <a href="{{ route('admin.guru.index') }}" class="w-full sm:w-auto text-center bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200/60 px-4 py-2 rounded-xl transition-colors text-xs font-semibold">Reset</a>
            </div>
        </form>
    </div>

    {{-- ALERT MESSAGES --}}
    @if(session('success'))
        <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200/60 text-emerald-700 px-4 py-3.5 rounded-2xl text-sm shadow-2xs animate-fade-in">
            <i class="fa-solid fa-circle-check text-lg text-emerald-500"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="flex items-center gap-3 bg-rose-50 border border-rose-200/60 text-rose-700 px-4 py-3.5 rounded-2xl text-sm shadow-2xs animate-fade-in">
            <i class="fa-solid fa-circle-exclamation text-lg text-rose-500"></i>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    {{-- AREA KONTEN UTAMA (Tabel) --}}
    <div class="bg-white shadow-xs border border-slate-200/80 overflow-hidden">
        {{-- <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-4">
            <h3 class="text-slate-800 font-bold text-base">Daftar Guru</h3>
        </div>
         --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-white">
                <thead class="text-sm text-white uppercase bg-green-600 border-b border-slate-200/80 font-bold tracking-wider">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-center w-12">No</th>
                        <th scope="col" class="px-6 py-4">Foto & Nama</th>
                        <th scope="col" class="px-6 py-4">NIP</th>
                        <th scope="col" class="px-6 py-4">Kontak</th>
                        <th scope="col" class="px-6 py-4 text-center">Status Akun</th>
                        <th scope="col" class="px-6 py-4 text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($gurus as $index => $guru)
                        <tr class="hover:bg-indigo-50/30 transition-colors">
                            <td class="px-6 py-4 text-center font-medium text-slate-400">
                                {{ $gurus->firstItem() + $index }}
                            </td>
                            
                            {{-- Foto & Nama --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full overflow-hidden bg-slate-100 border border-slate-200 flex-shrink-0 shadow-2xs">
                                        @if($guru->foto_profil)
                                            <img src="{{ asset('storage/' . $guru->foto_profil) }}" alt="Foto {{ $guru->nama }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-indigo-50 text-indigo-600 font-bold uppercase text-xs">
                                                {{ substr($guru->nama, 0, 2) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-800">{{ $guru->nama }}</div>
                                        <div class="text-xs text-slate-400">{{ $guru->jenis_kelamin }}</div>
                                    </div>
                                </div>
                            </td>
                            
                            {{-- NIP --}}
                            <td class="px-6 py-4 font-mono text-sm text-slate-700">
                                {{ $guru->nip }}
                            </td>
                            
                            {{-- Kontak --}}
                            <td class="px-6 py-4">
                                <div class="text-slate-600 text-xs flex flex-col gap-1">
                                    <span class="flex items-center gap-1.5"><i class="fa-solid fa-phone text-slate-400 w-3"></i> {{ $guru->no_hp }}</span>
                                    <span class="flex items-center gap-1.5"><i class="fa-solid fa-envelope text-slate-400 w-3"></i> {{ $guru->user->email ?? '-' }}</span>
                                </div>
                            </td>
                            
                            {{-- Status Aktif --}}
                            <td class="px-6 py-4 text-center">
                                @if($guru->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60 shadow-2xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200/60 shadow-2xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Nonaktif
                                    </span>
                                @endif
                            </td>
                            
                            {{-- Tombol Aksi --}}
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('admin.guru.show', $guru->id) }}" 
                                       class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-colors" 
                                       title="Detail Guru">
                                        <i class="fa-regular fa-eye text-sm"></i>
                                    </a>

                                    <a href="{{ route('admin.guru.edit', $guru->id) }}" 
                                       class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-xl transition-colors" 
                                       title="Ubah Data">
                                        <i class="fa-regular fa-pen-to-square text-sm"></i>
                                    </a>

                                    <form action="{{ route('admin.guru.destroy', $guru->id) }}" 
                                          method="POST" 
                                          class="inline"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus data guru ini? Semua data login terkait juga akan dihapus secara permanen.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-colors cursor-pointer" 
                                                title="Hapus Data">
                                            <i class="fa-regular fa-trash-can text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-3">
                                    <div class="p-4 rounded-full bg-slate-50 text-slate-300">
                                        <i class="fa-solid fa-inbox text-4xl"></i>
                                    </div>
                                    <p class="text-sm font-medium text-slate-500">Belum ada data guru terdaftar.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($gurus->hasPages())
            <div class="p-5 border-t border-slate-100 bg-slate-50/50">
                {{ $gurus->links() }}
            </div>
        @endif
    </div>

</div>
@endsection