@extends('layouts.admin')
@section('title', 'Data Guru')
@section('content')
<div class="space-y-6">
    
    {{-- HEADER CARD --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-[#161d3f] p-6 rounded-2xl shadow-md border border-[#242b4d]">
        <div class="flex items-start gap-4">
            <div class="p-3 bg-[#7367f0]/20 text-[#7367f0] rounded-xl">
                <i class="fa-solid fa-users-gear text-2xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-white">Data Guru</h2>
                <p class="text-[#626885] text-sm mt-1">Kelola data profil dan akun login guru dalam satu panel.</p>
            </div>
        </div>
        <div>
            <a href="{{ route('admin.guru.create') }}" class="inline-flex items-center gap-2 bg-[#7367f0] hover:bg-[#9e95f5] text-white font-semibold px-5 py-2.5 rounded-xl shadow-md shadow-[#7367f0]/20 transition-all">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Tambah Guru</span>
            </a>
        </div>
    </div>

    {{-- FILTER FORM --}}
    <div class="bg-[#161d3f] p-4 rounded-2xl shadow-md border border-[#242b4d]">
        <form method="GET" action="{{ route('admin.guru.index') }}" class="flex flex-col sm:flex-row gap-4 items-center">
            <div class="flex-1 w-full relative">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-[#626885]"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari Guru Berdasarkan Nama..." class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-[#10163a] border border-[#242b4d] text-[#c2c6dc] focus:border-[#7367f0] focus:ring-[#7367f0]/20 focus:ring-4 transition-all text-sm">
            </div>
            
            <select name="status" class="w-full sm:w-auto px-4 py-2.5 rounded-xl bg-[#10163a] border border-[#242b4d] text-[#c2c6dc] focus:border-[#7367f0] focus:ring-[#7367f0]/20 focus:ring-4 transition-all text-sm">
                <option value="">Semua Status</option>
                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Nonaktif</option>
            </select>

            <div class="flex gap-2 w-full sm:w-auto">
                <button type="submit" class="w-full sm:w-auto bg-[#10163a] hover:bg-[#242b4d] border border-[#242b4d] text-white px-5 py-2.5 rounded-xl transition-colors text-sm font-semibold">Cari</button>
                <a href="{{ route('admin.guru.index') }}" class="w-full sm:w-auto text-center bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/20 px-5 py-2.5 rounded-xl transition-colors text-sm font-semibold">Reset</a>
            </div>
        </form>
    </div>

    {{-- ALERT MESSAGES --}}
    @if(session('success'))
        <div class="flex items-center gap-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-4 py-3 rounded-xl shadow-sm">
            <i class="fa-solid fa-circle-check text-lg"></i>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="flex items-center gap-3 bg-rose-500/10 border border-rose-500/20 text-rose-400 px-4 py-3 rounded-xl shadow-sm">
            <i class="fa-solid fa-circle-exclamation text-lg"></i>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif

    {{-- AREA KONTEN UTAMA (Tabel) --}}
    <div class="bg-[#161d3f] rounded-2xl shadow-md border border-[#242b4d] overflow-hidden">
        <div class="p-5 border-b border-[#242b4d] flex flex-col sm:flex-row justify-between items-center gap-4">
            <h3 class="text-white font-bold text-lg">Daftar Guru</h3>
        </div>
        
       <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-[#c2c6dc]">
                <thead class="text-xs text-[#c2c6dc] uppercase bg-[#10163a]/50 border-b border-[#242b4d]">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-center w-12">No</th>
                        <th scope="col" class="px-6 py-4">Foto & Nama</th>
                        <th scope="col" class="px-6 py-4">NIP</th>
                        <th scope="col" class="px-6 py-4">Kontak</th>
                        <th scope="col" class="px-6 py-4 text-center">Status Akun</th>
                        <th scope="col" class="px-6 py-4 text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#242b4d]">
                    @forelse($gurus as $index => $guru)
                        <tr class="hover:bg-[#10163a]/40 transition-colors">
                            <td class="px-6 py-4 text-center font-medium text-[#626885]">
                                {{ $gurus->firstItem() + $index }}
                            </td>
                            
                            {{-- Foto & Nama --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full overflow-hidden bg-[#10163a] border border-[#242b4d] flex-shrink-0">
                                        @if($guru->foto_profil)
                                            <img src="{{ asset('storage/' . $guru->foto_profil) }}" alt="Foto {{ $guru->nama }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-[#7367f0]/20 text-[#7367f0] font-bold uppercase text-sm">
                                                {{ substr($guru->nama, 0, 2) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-semibold text-white">{{ $guru->nama }}</div>
                                        <div class="text-xs text-[#626885]">{{ $guru->jenis_kelamin }}</div>
                                    </div>
                                </div>
                            </td>
                            
                            {{-- NIP --}}
                            <td class="px-6 py-4 font-mono text-sm text-[#c2c6dc]">
                                {{ $guru->nip }}
                            </td>
                            
                            {{-- Kontak --}}
                            <td class="px-6 py-4">
                                <div class="text-[#c2c6dc] text-xs flex flex-col gap-1">
                                    <span class="flex items-center gap-1.5"><i class="fa-solid fa-phone text-[#626885] w-3"></i> {{ $guru->no_hp }}</span>
                                    <span class="flex items-center gap-1.5"><i class="fa-solid fa-envelope text-[#626885] w-3"></i> {{ $guru->user->email ?? '-' }}</span>
                                </div>
                            </td>
                            
                            {{-- Status Aktif --}}
                            <td class="px-6 py-4 text-center">
                                @if($guru->is_active)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-rose-500/10 text-rose-400 border border-rose-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Nonaktif
                                    </span>
                                @endif
                            </td>
                            
                            {{-- Tombol Aksi --}}
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.guru.show', $guru->id) }}" 
                                       class="p-2 text-[#626885] hover:text-[#7367f0] hover:bg-[#7367f0]/10 rounded-lg transition-colors" 
                                       title="Detail Guru">
                                        <i class="fa-regular fa-eye text-sm"></i>
                                    </a>

                                    <a href="{{ route('admin.guru.edit', $guru->id) }}" 
                                       class="p-2 text-[#626885] hover:text-[#ff9f43] hover:bg-[#ff9f43]/10 rounded-lg transition-colors" 
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
                                                class="p-2 text-[#626885] hover:text-rose-400 hover:bg-rose-500/10 rounded-lg transition-colors" 
                                                title="Hapus Data">
                                            <i class="fa-regular fa-trash-can text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-[#626885]">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i class="fa-solid fa-inbox text-4xl text-[#242b4d]"></i>
                                    <p class="text-sm">Belum ada data guru terdaftar.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($gurus->hasPages())
            <div class="p-5 border-t border-[#242b4d] bg-[#10163a]/50">
                {{ $gurus->links() }}
            </div>
        @endif
    </div>

</div>
@endsection