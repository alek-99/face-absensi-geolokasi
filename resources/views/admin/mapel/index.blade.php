@extends('layouts.admin')
@section('title','Daftar Matapelajaran')

@section('content')
<div class="space-y-6" x-data="{ modalTambah: false }">
    
    <!-- Header Page (Unified Header Card with Green Left Border) -->
    <div class="bg-white border border-slate-200 border-l-4 border-l-emerald-500 rounded-r-xl rounded-l-none shadow-sm p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-800 flex items-center gap-2.5">
                <i class="fa-solid fa-book-bookmark text-indigo-600"></i>
                Daftar Mata Pelajaran
            </h1>
            <p class="text-xs text-slate-500 mt-1">Kelola dan atur seluruh data mata pelajaran sekolah dari panel ini.</p>
        </div>
        <div>
            <!-- Button Trigger Modal Tambah -->
            <button @click="modalTambah = true" type="button" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2 rounded-lg shadow-sm transition-all text-xs focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 shrink-0">
                <i class="fa-solid fa-plus"></i>
                Tambah Mapel
            </button>
        </div>
    </div>

    <!-- Alert Notifikasi Sukses -->
    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center justify-between shadow-sm" role="alert">
            <div class="flex items-center gap-2.5">
                <i class="fa-solid fa-circle-check text-emerald-600"></i>
                <span class="text-xs font-medium">{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 p-1">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    <!-- Alert Notifikasi Error Validasi -->
    @if ($errors->any())
        <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl shadow-sm">
            <div class="flex items-center gap-2 mb-1.5 font-semibold text-xs text-rose-700">
                <i class="fa-solid fa-triangle-exclamation text-rose-600"></i>
                <span>Terjadi kesalahan pada inputan:</span>
            </div>
            <ul class="list-disc list-inside text-xs space-y-1 text-rose-600 pl-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Tabel Data Mapel -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-semibold uppercase text-xs tracking-wider">
                        <th class="py-3.5 px-4 text-center w-12">No</th>
                        <th class="py-3.5 px-4">Nama Mata Pelajaran</th>
                        <th class="py-3.5 px-4">Catatan</th>
                        <th class="py-3.5 px-4 text-center w-40">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse ($mapels as $index => $mapel)
                        <tr class="hover:bg-slate-50/80 transition-colors" x-data="{ modalEdit: false, modalDetail: false }">
                            <td class="py-3.5 px-4 text-center font-medium text-slate-400">
                                {{ $mapels->firstItem() + $index }}
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-slate-800">
                                {{ $mapel->nama_mapel }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-600">
                                {{ $mapel->catatan ?? '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <!-- Tombol Detail -->
                                    <button @click="modalDetail = true" type="button" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Detail">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>

                                    <!-- Tombol Edit -->
                                    <button @click="modalEdit = true" type="button" class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>

                                    <!-- Tombol Hapus -->
                                    <form action="{{ route('admin.mapel.destroy', $mapel->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>

                                <!-- MODAL DETAIL -->
                                <div x-show="modalDetail" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                                        <div x-show="modalDetail" x-transition.opacity @click="modalDetail = false" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity"></div>
                                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                        
                                        <div x-show="modalDetail" x-transition class="inline-block align-bottom bg-white border border-slate-100 rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                            <div class="bg-white px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                                                <h3 class="text-base font-semibold text-slate-800 flex items-center gap-2">
                                                    <i class="fa-solid fa-circle-info text-indigo-600"></i>
                                                    Detail Mata Pelajaran
                                                </h3>
                                                <button @click="modalDetail = false" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-100 transition-colors">
                                                    <i class="fa-solid fa-xmark"></i>
                                                </button>
                                            </div>
                                            <div class="p-6 space-y-4">
                                                <div>
                                                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1 flex items-center gap-1.5">
                                                        <i class="fa-solid fa-book text-slate-400"></i> Nama Mata Pelajaran
                                                    </label>
                                                    <p class="text-slate-900 font-semibold text-base">{{ $mapel->nama_mapel }}</p>
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1 flex items-center gap-1.5">
                                                        <i class="fa-solid fa-note-sticky text-slate-400"></i> Catatan
                                                    </label>
                                                    <p class="text-slate-600 text-sm bg-slate-50 p-3 rounded-lg border border-slate-200">{{ $mapel->catatan ?? 'Tidak ada catatan.' }}</p>
                                                </div>
                                                <div class="grid grid-cols-2 gap-4 pt-2 border-t border-slate-100 text-xs text-slate-500">
                                                    <div>
                                                        <span class="block font-medium text-slate-500 flex items-center gap-1">
                                                            <i class="fa-solid fa-calendar-plus text-slate-400"></i> Dibuat Pada:
                                                        </span>
                                                        <span class="text-slate-700 font-medium">{{ $mapel->created_at ? $mapel->created_at->format('d M Y, H:i') : '-' }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="block font-medium text-slate-500 flex items-center gap-1">
                                                            <i class="fa-solid fa-clock-rotate-left text-slate-400"></i> Diperbarui Pada:
                                                        </span>
                                                        <span class="text-slate-700 font-medium">{{ $mapel->updated_at ? $mapel->updated_at->format('d M Y, H:i') : '-' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="bg-slate-50 px-5 py-3 flex justify-end border-t border-slate-100">
                                                <button @click="modalDetail = false" type="button" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-medium rounded-lg transition-colors flex items-center gap-1.5 border border-slate-200">
                                                    <i class="fa-solid fa-xmark"></i> Tutup
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- MODAL EDIT -->
                                <div x-show="modalEdit" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
                                    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                                        <div x-show="modalEdit" x-transition.opacity @click="modalEdit = false" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity"></div>
                                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                                        
                                        <div x-show="modalEdit" x-transition class="inline-block align-bottom bg-white border border-slate-100 rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                            <form action="{{ route('admin.mapel.update', $mapel->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="bg-white px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                                                    <h3 class="text-base font-semibold text-slate-800 flex items-center gap-2">
                                                        <i class="fa-solid fa-pen-to-square text-amber-600"></i>
                                                        Edit Mata Pelajaran
                                                    </h3>
                                                    <button @click="modalEdit = false" type="button" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-100 transition-colors">
                                                        <i class="fa-solid fa-xmark"></i>
                                                    </button>
                                                </div>
                                                <div class="p-6 space-y-4">
                                                    <div>
                                                        <label for="nama_mapel_{{ $mapel->id }}" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1 flex items-center gap-1.5">
                                                            <i class="fa-solid fa-book text-slate-400"></i> Nama Mapel <span class="text-rose-500">*</span>
                                                        </label>
                                                        <input type="text" name="nama_mapel" id="nama_mapel_{{ $mapel->id }}" value="{{ old('nama_mapel', $mapel->nama_mapel) }}" required maxlength="50" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 text-slate-800 rounded-lg shadow-sm focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm placeholder-slate-400 outline-none transition-all">
                                                    </div>
                                                    <div>
                                                        <label for="catatan_{{ $mapel->id }}" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1 flex items-center gap-1.5">
                                                            <i class="fa-solid fa-note-sticky text-slate-400"></i> Catatan
                                                        </label>
                                                        <textarea name="catatan" id="catatan_{{ $mapel->id }}" rows="3" maxlength="255" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 text-slate-800 rounded-lg shadow-sm focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm placeholder-slate-400 outline-none transition-all">{{ old('catatan', $mapel->catatan) }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="bg-slate-50 px-5 py-3 flex justify-end gap-2 border-t border-slate-100">
                                                    <button @click="modalEdit = false" type="button" class="px-4 py-2 border border-slate-300 text-slate-700 hover:bg-slate-100 text-xs font-medium rounded-lg transition-colors flex items-center gap-1.5">
                                                        <i class="fa-solid fa-xmark"></i> Batal
                                                    </button>
                                                    <button type="submit" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold rounded-lg transition-colors flex items-center gap-1.5 shadow-sm">
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
                            <td colspan="4" class="py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i class="fa-solid fa-folder-open text-3xl text-slate-300"></i>
                                    <span>Belum ada data mata pelajaran.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-5 py-4 border-t border-slate-200 bg-slate-50/50">
            {{ $mapels->links() }}
        </div>
    </div>

    <!-- MODAL TAMBAH DATA -->
    <div x-show="modalTambah" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="modalTambah" x-transition.opacity @click="modalTambah = false" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            
            <div x-show="modalTambah" x-transition class="inline-block align-bottom bg-white border border-slate-100 rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('admin.mapel.store') }}" method="POST">
                    @csrf
                    <div class="bg-white px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="text-base font-semibold text-slate-800 flex items-center gap-2">
                            <i class="fa-solid fa-square-plus text-indigo-600"></i>
                            Tambah Mata Pelajaran
                        </h3>
                        <button @click="modalTambah = false" type="button" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-100 transition-colors">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label for="nama_mapel" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1 flex items-center gap-1.5">
                                <i class="fa-solid fa-book text-slate-400"></i> Nama Mapel <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="nama_mapel" id="nama_mapel" value="{{ old('nama_mapel') }}" placeholder="Contoh: Matematika" required maxlength="50" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 text-slate-800 rounded-lg shadow-sm focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm placeholder-slate-400 outline-none transition-all">
                        </div>
                        <div>
                            <label for="catatan" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1 flex items-center gap-1.5">
                                <i class="fa-solid fa-note-sticky text-slate-400"></i> Catatan
                            </label>
                            <textarea name="catatan" id="catatan" rows="3" placeholder="Tambahkan catatan (opsional)" maxlength="255" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 text-slate-800 rounded-lg shadow-sm focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm placeholder-slate-400 outline-none transition-all">{{ old('catatan') }}</textarea>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-5 py-3 flex justify-end gap-2 border-t border-slate-100">
                        <button @click="modalTambah = false" type="button" class="px-4 py-2 border border-slate-300 text-slate-700 hover:bg-slate-100 text-xs font-medium rounded-lg transition-colors flex items-center gap-1.5">
                            <i class="fa-solid fa-xmark"></i> Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition-colors flex items-center gap-1.5 shadow-sm">
                            <i class="fa-solid fa-floppy-disk"></i> Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection