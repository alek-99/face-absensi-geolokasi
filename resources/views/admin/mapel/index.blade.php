@extends('layouts.admin')
@section('title','Daftar Matapelajaran')

@section('content')
<div class="container mx-auto px-4 py-6 min-h-screen text-slate-100" x-data="{ modalTambah: false }">
    
    <!-- Header Page -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-book-bookmark text-indigo-400"></i>
                Daftar Mata Pelajaran
            </h1>
            <p class="text-sm text-slate-400 mt-1">Kelola data mata pelajaran sekolah di sini.</p>
        </div>
        <div>
            <!-- Button Trigger Modal Tambah -->
            <button @click="modalTambah = true" type="button" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-500 text-white font-medium px-4 py-2.5 rounded-lg shadow-sm transition-all text-sm">
                <i class="fa-solid fa-plus"></i>
                Tambah Mapel
            </button>
        </div>
    </div>

    <!-- Alert Notifikasi Sukses -->
    @if (session('success'))
        <div class="mb-4 bg-emerald-950/80 border border-emerald-700/60 text-emerald-200 px-4 py-3 rounded-lg flex items-center justify-between shadow-sm" role="alert">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-emerald-400"></i>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-emerald-200">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    <!-- Alert Notifikasi Error Validasi -->
    @if ($errors->any())
        <div class="mb-4 bg-rose-950/80 border border-rose-700/60 text-rose-200 px-4 py-3 rounded-lg shadow-sm">
            <div class="flex items-center gap-2 mb-1 font-semibold text-sm">
                <i class="fa-solid fa-triangle-exclamation text-rose-400"></i>
                <span>Terjadi kesalahan pada inputan:</span>
            </div>
            <ul class="list-disc list-inside text-xs space-y-1 text-rose-300">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Tabel Data Mapel -->
    <div class="bg-slate-800 rounded-xl shadow-lg border border-slate-700/60 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-900/60 border-b border-slate-700/80 text-slate-300 font-semibold uppercase text-xs tracking-wider">
                        <th class="py-3.5 px-4 text-center w-12">No</th>
                        <th class="py-3.5 px-4">Nama Mata Pelajaran</th>
                        <th class="py-3.5 px-4">Catatan</th>
                        <th class="py-3.5 px-4 text-center w-48">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50">
                    @forelse ($mapels as $index => $mapel)
                        <tr class="hover:bg-slate-700/30 transition-colors" x-data="{ modalEdit: false, modalDetail: false }">
                            <td class="py-3.5 px-4 text-center font-medium text-slate-400">
                                {{ $mapels->firstItem() + $index }}
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-slate-100">
                                {{ $mapel->nama_mapel }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-300">
                                {{ $mapel->catatan ?? '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <!-- Tombol Detail -->
                                    <button @click="modalDetail = true" type="button" class="p-2 text-sky-400 hover:bg-sky-500/10 rounded-lg transition-colors" title="Detail">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>

                                    <!-- Tombol Edit -->
                                    <button @click="modalEdit = true" type="button" class="p-2 text-amber-400 hover:bg-amber-500/10 rounded-lg transition-colors" title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>

                                    <!-- Tombol Hapus -->
                                    <form action="{{ route('admin.mapel.destroy', $mapel->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-rose-400 hover:bg-rose-500/10 rounded-lg transition-colors" title="Hapus">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>

                                <!-- MODAL DETAIL -->
                                <div x-show="modalDetail" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                                        <div x-show="modalDetail" x-transition.opacity @click="modalDetail = false" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm transition-opacity"></div>
                                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                        
                                        <div x-show="modalDetail" x-transition class="inline-block align-bottom bg-slate-800 border border-slate-700/80 rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                            <div class="bg-slate-900/80 px-5 py-4 border-b border-slate-700/80 flex items-center justify-between">
                                                <h3 class="text-base font-semibold text-slate-100 flex items-center gap-2">
                                                    <i class="fa-solid fa-circle-info text-sky-400"></i>
                                                    Detail Mata Pelajaran
                                                </h3>
                                                <button @click="modalDetail = false" class="text-slate-400 hover:text-slate-200">
                                                    <i class="fa-solid fa-xmark"></i>
                                                </button>
                                            </div>
                                            <div class="p-6 space-y-4">
                                                <div>
                                                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1 flex items-center gap-1.5">
                                                        <i class="fa-solid fa-book text-slate-400"></i> Nama Mata Pelajaran
                                                    </label>
                                                    <p class="text-white font-medium text-base">{{ $mapel->nama_mapel }}</p>
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1 flex items-center gap-1.5">
                                                        <i class="fa-solid fa-note-sticky text-slate-400"></i> Catatan
                                                    </label>
                                                    <p class="text-slate-300 text-sm bg-slate-900/60 p-3 rounded-lg border border-slate-700/50">{{ $mapel->catatan ?? 'Tidak ada catatan.' }}</p>
                                                </div>
                                                <div class="grid grid-cols-2 gap-4 pt-2 border-t border-slate-700/60 text-xs text-slate-400">
                                                    <div>
                                                        <span class="block font-medium text-slate-400 flex items-center gap-1">
                                                            <i class="fa-solid fa-calendar-plus"></i> Dibuat Pada:
                                                        </span>
                                                        {{ $mapel->created_at ? $mapel->created_at->format('d M Y, H:i') : '-' }}
                                                    </div>
                                                    <div>
                                                        <span class="block font-medium text-slate-400 flex items-center gap-1">
                                                            <i class="fa-solid fa-clock-rotate-left"></i> Diperbarui Pada:
                                                        </span>
                                                        {{ $mapel->updated_at ? $mapel->updated_at->format('d M Y, H:i') : '-' }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="bg-slate-900/50 px-5 py-3 flex justify-end border-t border-slate-700/50">
                                                <button @click="modalDetail = false" type="button" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-200 text-xs font-medium rounded-lg transition-colors flex items-center gap-1.5">
                                                    <i class="fa-solid fa-xmark"></i> Tutup
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- MODAL EDIT -->
                                <div x-show="modalEdit" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
                                    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                                        <div x-show="modalEdit" x-transition.opacity @click="modalEdit = false" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm transition-opacity"></div>
                                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                                        
                                        <div x-show="modalEdit" x-transition class="inline-block align-bottom bg-slate-800 border border-slate-700/80 rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                            <form action="{{ route('admin.mapel.update', $mapel->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="bg-slate-900/80 px-5 py-4 border-b border-slate-700/80 flex items-center justify-between">
                                                    <h3 class="text-base font-semibold text-slate-100 flex items-center gap-2">
                                                        <i class="fa-solid fa-pen-to-square text-amber-400"></i>
                                                        Edit Mata Pelajaran
                                                    </h3>
                                                    <button @click="modalEdit = false" type="button" class="text-slate-400 hover:text-slate-200">
                                                        <i class="fa-solid fa-xmark"></i>
                                                    </button>
                                                </div>
                                                <div class="p-6 space-y-4">
                                                    <div>
                                                        <label for="nama_mapel_{{ $mapel->id }}" class="block text-sm font-medium text-slate-300 mb-1 flex items-center gap-1.5">
                                                            <i class="fa-solid fa-book text-slate-400"></i> Nama Mapel <span class="text-rose-400">*</span>
                                                        </label>
                                                        <input type="text" name="nama_mapel" id="nama_mapel_{{ $mapel->id }}" value="{{ old('nama_mapel', $mapel->nama_mapel) }}" required maxlength="50" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 text-slate-100 rounded-lg shadow-sm focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 text-sm placeholder-slate-500">
                                                    </div>
                                                    <div>
                                                        <label for="catatan_{{ $mapel->id }}" class="block text-sm font-medium text-slate-300 mb-1 flex items-center gap-1.5">
                                                            <i class="fa-solid fa-note-sticky text-slate-400"></i> Catatan
                                                        </label>
                                                        <textarea name="catatan" id="catatan_{{ $mapel->id }}" rows="3" maxlength="255" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 text-slate-100 rounded-lg shadow-sm focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 text-sm placeholder-slate-500">{{ old('catatan', $mapel->catatan) }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="bg-slate-900/50 px-5 py-3 flex justify-end gap-2 border-t border-slate-700/50">
                                                    <button @click="modalEdit = false" type="button" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-200 text-xs font-medium rounded-lg transition-colors flex items-center gap-1.5">
                                                        <i class="fa-solid fa-xmark"></i> Batal
                                                    </button>
                                                    <button type="submit" class="px-4 py-2 bg-amber-600 hover:bg-amber-500 text-white text-xs font-medium rounded-lg transition-colors flex items-center gap-1.5">
                                                        <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-slate-500">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i class="fa-solid fa-folder-open text-3xl"></i>
                                    <span>Belum ada data mata pelajaran.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-5 py-4 border-t border-slate-700/80 bg-slate-800/50">
            {{ $mapels->links() }}
        </div>
    </div>

    <!-- MODAL TAMBAH DATA -->
    <div x-show="modalTambah" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="modalTambah" x-transition.opacity @click="modalTambah = false" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm transition-opacity"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            
            <div x-show="modalTambah" x-transition class="inline-block align-bottom bg-slate-800 border border-slate-700/80 rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('admin.mapel.store') }}" method="POST">
                    @csrf
                    <div class="bg-slate-900/80 px-5 py-4 border-b border-slate-700/80 flex items-center justify-between">
                        <h3 class="text-base font-semibold text-slate-100 flex items-center gap-2">
                            <i class="fa-solid fa-square-plus text-indigo-400"></i>
                            Tambah Mata Pelajaran
                        </h3>
                        <button @click="modalTambah = false" type="button" class="text-slate-400 hover:text-slate-200">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label for="nama_mapel" class="block text-sm font-medium text-slate-300 mb-1 flex items-center gap-1.5">
                                <i class="fa-solid fa-book text-slate-400"></i> Nama Mapel <span class="text-rose-400">*</span>
                            </label>
                            <input type="text" name="nama_mapel" id="nama_mapel" value="{{ old('nama_mapel') }}" placeholder="Contoh: Matematika" required maxlength="50" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 text-slate-100 rounded-lg shadow-sm focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 text-sm placeholder-slate-500">
                        </div>
                        <div>
                            <label for="catatan" class="block text-sm font-medium text-slate-300 mb-1 flex items-center gap-1.5">
                                <i class="fa-solid fa-note-sticky text-slate-400"></i> Catatan
                            </label>
                            <textarea name="catatan" id="catatan" rows="3" placeholder="Tambahkan catatan (opsional)" maxlength="255" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 text-slate-100 rounded-lg shadow-sm focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 text-sm placeholder-slate-500">{{ old('catatan') }}</textarea>
                        </div>
                    </div>
                    <div class="bg-slate-900/50 px-5 py-3 flex justify-end gap-2 border-t border-slate-700/50">
                        <button @click="modalTambah = false" type="button" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-200 text-xs font-medium rounded-lg transition-colors flex items-center gap-1.5">
                            <i class="fa-solid fa-xmark"></i> Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-medium rounded-lg transition-colors flex items-center gap-1.5">
                            <i class="fa-solid fa-floppy-disk"></i> Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection