@extends('layouts.admin')
@section('title','Daftar Jadwal Pelajaran')
@section('content')
<div class="space-y-8">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-calendar-days text-indigo-400"></i> Kelola Jadwal Pelajaran
            </h1>
            <p class="text-sm text-gray-400">Atur dan pantau jadwal pelajaran sekolah secara terpusat.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.jadwal.pdf') }}" target="_blank" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition flex items-center gap-2">
                <i class="fa-solid fa-file-pdf"></i> Cetak PDF
            </a>
            <button onclick="openCreateModal()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> Tambah Jadwal
            </button>
        </div>
    </div>

    {{-- Flash Notification --}}
    @if(session('success'))
        <div class="mb-4 p-4 bg-emerald-900/50 border border-emerald-500/50 text-emerald-300 rounded-lg flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-emerald-200"><i class="fa-solid fa-xmark"></i></button>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-rose-900/50 border border-rose-500/50 text-rose-300 rounded-lg flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span>{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-rose-400 hover:text-rose-200"><i class="fa-solid fa-xmark"></i></button>
        </div>
    @endif

    {{-- Statistik Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
        @php
            $hariOrder = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        @endphp
        @foreach($hariOrder as $hari)
            @php
                $stat = $statitsik->firstWhere('hari', $hari);
            @endphp
            <div class="bg-gray-800 border border-gray-700/60 rounded-xl p-4 shadow-sm hover:border-indigo-500/50 transition">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold uppercase tracking-wider text-indigo-400">{{ $hari }}</span>
                    <i class="fa-regular fa-clock text-gray-500 text-xs"></i>
                </div>
                <div class="text-xl font-bold text-white mb-1">
                    {{ $stat ? $stat->jumlah_jadwal : 0 }} <span class="text-xs font-normal text-gray-400">Sesi</span>
                </div>
                <div class="text-xs text-gray-400 space-y-0.5">
                    <p><i class="fa-solid fa-user-tie text-[10px] w-3"></i> {{ $stat ? $stat->jumlah_guru : 0 }} Guru</p>
                    <p><i class="fa-solid fa-book text-[10px] w-3"></i> {{ $stat ? $stat->jumlah_mapel : 0 }} Mapel</p>
                    <p><i class="fa-solid fa-door-open text-[10px] w-3"></i> {{ $stat ? $stat->jumlah_kelas : 0 }} Kelas</p>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Table Section --}}
    <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-300">
                <thead class="bg-gray-900/80 text-gray-400 text-xs uppercase tracking-wider border-b border-gray-700">
                    <tr>
                        <th class="px-4 py-3.5">Hari & Waktu</th>
                        <th class="px-4 py-3.5">Mata Pelajaran</th>
                        <th class="px-4 py-3.5">Guru Pengajar</th>
                        <th class="px-4 py-3.5">Kelas</th>
                        <th class="px-4 py-3.5">Status</th>
                        <th class="px-4 py-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700/60">
                    @forelse($jadwals as $jadwal)
                        <tr class="hover:bg-gray-750/50 transition">
                            <td class="px-4 py-3 font-medium">
                                <span class="px-2 py-0.5 rounded text-xs font-semibold bg-indigo-900/60 text-indigo-300 border border-indigo-700/50">
                                    {{ $jadwal->hari }}
                                </span>
                                <div class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                                    <i class="fa-regular fa-clock"></i>
                                    {{ substr($jadwal->jam_masuk, 0, 5) }} - {{ substr($jadwal->jam_pulang, 0, 5) }}
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-semibold text-white">{{ $jadwal->mapel->nama_mapel ?? '-' }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-gray-200">{{ $jadwal->guru->nama ?? $jadwal->guru->user->name ?? '-' }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded bg-gray-700 text-gray-200 text-xs font-medium">
                                    {{ $jadwal->kelas->nama_kelas ?? '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @if($jadwal->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-950 text-emerald-400 border border-emerald-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-700 text-gray-400 border border-gray-600">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick='openDetailModal(@json($jadwal))' class="p-1.5 bg-gray-700 hover:bg-gray-600 text-blue-400 rounded-lg transition" title="Detail">
                                        <i class="fa-solid fa-eye w-4 h-4"></i>
                                    </button>
                                    <button onclick='openEditModal(@json($jadwal))' class="p-1.5 bg-gray-700 hover:bg-gray-600 text-amber-400 rounded-lg transition" title="Edit">
                                        <i class="fa-solid fa-pen-to-square w-4 h-4"></i>
                                    </button>
                                    <form action="{{ route('admin.jadwal.destroy', $jadwal->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 bg-gray-700 hover:bg-gray-600 text-rose-400 rounded-lg transition" title="Hapus">
                                            <i class="fa-solid fa-trash-can w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                <i class="fa-solid fa-calendar-xmark text-3xl mb-2"></i>
                                <p>Belum ada data jadwal pelajaran.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        @if($jadwals->hasPages())
            <div class="p-4 border-t border-gray-700 bg-gray-800">
                {{ $jadwals->links() }}
            </div>
        @endif
    </div>
</div>

{{-- MODAL TAMBAH --}}
<div id="modalCreate" class="fixed inset-0 z-50 hidden bg-black/70 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all">
        <div class="p-4 border-b border-gray-700 flex justify-between items-center bg-gray-900/50">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-plus text-indigo-400"></i> Tambah Jadwal Pelajaran
            </h3>
            <button onclick="closeCreateModal()" class="text-gray-400 hover:text-white"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        <form action="{{ route('admin.jadwal.store') }}" method="POST" class="p-4 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-gray-300 uppercase mb-1">Hari</label>
                <select name="hari" required class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-indigo-500">
                    <option value="">-- Pilih Hari --</option>
                    @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $h)
                        <option value="{{ $h }}">{{ $h }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-300 uppercase mb-1">Jam Masuk</label>
                    <input type="time" name="jam_masuk" required class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-300 uppercase mb-1">Jam Pulang</label>
                    <input type="time" name="jam_pulang" required class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-indigo-500">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-300 uppercase mb-1">Guru Pengajar</label>
                <select name="guru_id" required class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-indigo-500">
                    <option value="">-- Pilih Guru --</option>
                    @foreach($gurus as $guru)
                        <option value="{{ $guru->id }}">{{ $guru->nama ?? $guru->user->name ?? 'Guru ID: ' . $guru->id }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-300 uppercase mb-1">Mata Pelajaran</label>
                    <select name="mapel_id" required class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-indigo-500">
                        <option value="">-- Pilih Mapel --</option>
                        @foreach($mapels as $mapel)
                            <option value="{{ $mapel->id }}">{{ $mapel->nama_mapel }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-300 uppercase mb-1">Kelas</label>
                    <select name="kelas_id" required class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-indigo-500">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelases as $kelas)
                            <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex items-center gap-2 pt-2">
                <input type="checkbox" name="is_active" id="create_is_active" value="1" checked class="w-4 h-4 text-indigo-600 bg-gray-900 border-gray-700 rounded focus:ring-indigo-500">
                <label for="create_is_active" class="text-sm text-gray-300">Setel sebagai Jadwal Aktif</label>
            </div>
            <div class="pt-4 flex justify-end gap-2 border-t border-gray-700">
                <button type="button" onclick="closeCreateModal()" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-gray-200 rounded-lg text-sm">Batal</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT --}}
<div id="modalEdit" class="fixed inset-0 z-50 hidden bg-black/70 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all">
        <div class="p-4 border-b border-gray-700 flex justify-between items-center bg-gray-900/50">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square text-amber-400"></i> Edit Jadwal Pelajaran
            </h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-white"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        <form id="formEdit" method="POST" class="p-4 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-gray-300 uppercase mb-1">Hari</label>
                <select name="hari" id="edit_hari" required class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-indigo-500">
                    @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $h)
                        <option value="{{ $h }}">{{ $h }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-300 uppercase mb-1">Jam Masuk</label>
                    <input type="time" name="jam_masuk" id="edit_jam_masuk" required class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-300 uppercase mb-1">Jam Pulang</label>
                    <input type="time" name="jam_pulang" id="edit_jam_pulang" required class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-indigo-500">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-300 uppercase mb-1">Guru Pengajar</label>
                <select name="guru_id" id="edit_guru_id" required class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-indigo-500">
                    @foreach($gurus as $guru)
                        <option value="{{ $guru->id }}">{{ $guru->nama ?? $guru->user->name ?? 'Guru ID: ' . $guru->id }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-300 uppercase mb-1">Mata Pelajaran</label>
                    <select name="mapel_id" id="edit_mapel_id" required class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-indigo-500">
                        @foreach($mapels as $mapel)
                            <option value="{{ $mapel->id }}">{{ $mapel->nama_mapel }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-300 uppercase mb-1">Kelas</label>
                    <select name="kelas_id" id="edit_kelas_id" required class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-indigo-500">
                        @foreach($kelases as $kelas)
                            <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex items-center gap-2 pt-2">
                <input type="checkbox" name="is_active" id="edit_is_active" value="1" class="w-4 h-4 text-indigo-600 bg-gray-900 border-gray-700 rounded focus:ring-indigo-500">
                <label for="edit_is_active" class="text-sm text-gray-300">Setel sebagai Jadwal Aktif</label>
            </div>
            <div class="pt-4 flex justify-end gap-2 border-t border-gray-700">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-gray-200 rounded-lg text-sm">Batal</button>
                <button type="submit" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-sm font-medium">Perbarui</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL DETAIL --}}
<div id="modalDetail" class="fixed inset-0 z-50 hidden bg-black/70 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all">
        <div class="p-4 border-b border-gray-700 flex justify-between items-center bg-gray-900/50">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-circle-info text-blue-400"></i> Detail Jadwal
            </h3>
            <button onclick="closeDetailModal()" class="text-gray-400 hover:text-white"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        <div class="p-5 space-y-4 text-sm">
            <div class="flex justify-between items-center border-b border-gray-700/60 pb-2">
                <span class="text-gray-400">Hari & Waktu</span>
                <span class="text-white font-medium text-right" id="detail_waktu">-</span>
            </div>
            <div class="flex justify-between items-center border-b border-gray-700/60 pb-2">
                <span class="text-gray-400">Mata Pelajaran</span>
                <span class="text-white font-medium text-right" id="detail_mapel">-</span>
            </div>
            <div class="flex justify-between items-center border-b border-gray-700/60 pb-2">
                <span class="text-gray-400">Guru Pengajar</span>
                <span class="text-white font-medium text-right" id="detail_guru">-</span>
            </div>
            <div class="flex justify-between items-center border-b border-gray-700/60 pb-2">
                <span class="text-gray-400">Kelas</span>
                <span class="text-white font-medium text-right" id="detail_kelas">-</span>
            </div>
            <div class="flex justify-between items-center border-b border-gray-700/60 pb-2">
                <span class="text-gray-400">Status</span>
                <span id="detail_status">-</span>
            </div>
            <div class="pt-2 flex justify-end">
                <button type="button" onclick="closeDetailModal()" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-gray-200 rounded-lg text-sm w-full">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Functions for Modal Create
    function openCreateModal() {
        document.getElementById('modalCreate').classList.remove('hidden');
    }
    function closeCreateModal() {
        document.getElementById('modalCreate').classList.add('hidden');
    }

    // Functions for Modal Edit
    function openEditModal(jadwal) {
        document.getElementById('formEdit').action = `/admin/jadwal/${jadwal.id}`;
        document.getElementById('edit_hari').value = jadwal.hari;
        document.getElementById('edit_jam_masuk').value = jadwal.jam_masuk ? jadwal.jam_masuk.substring(0, 5) : '';
        document.getElementById('edit_jam_pulang').value = jadwal.jam_pulang ? jadwal.jam_pulang.substring(0, 5) : '';
        document.getElementById('edit_guru_id').value = jadwal.guru_id;
        document.getElementById('edit_mapel_id').value = jadwal.mapel_id;
        document.getElementById('edit_kelas_id').value = jadwal.kelas_id;
        document.getElementById('edit_is_active').checked = !!jadwal.is_active;

        document.getElementById('modalEdit').classList.remove('hidden');
    }
    function closeEditModal() {
        document.getElementById('modalEdit').classList.add('hidden');
    }

    // Functions for Modal Detail
    function openDetailModal(jadwal) {
        const jamMasuk = jadwal.jam_masuk ? jadwal.jam_masuk.substring(0, 5) : '';
        const jamPulang = jadwal.jam_pulang ? jadwal.jam_pulang.substring(0, 5) : '';
        
        document.getElementById('detail_waktu').innerText = `${jadwal.hari}, ${jamMasuk} - ${jamPulang}`;
        document.getElementById('detail_mapel').innerText = jadwal.mapel ? jadwal.mapel.nama_mapel : '-';
        
        let namaGuru = '-';
        if (jadwal.guru) {
            namaGuru = jadwal.guru.nama || (jadwal.guru.user ? jadwal.guru.user.name : '-');
        }
        document.getElementById('detail_guru').innerText = namaGuru;
        document.getElementById('detail_kelas').innerText = jadwal.kelas ? jadwal.kelas.nama_kelas : '-';
        
        const statusEl = document.getElementById('detail_status');
        if (jadwal.is_active) {
            statusEl.innerHTML = `<span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-950 text-emerald-400 border border-emerald-800">Aktif</span>`;
        } else {
            statusEl.innerHTML = `<span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-700 text-gray-400 border border-gray-600">Nonaktif</span>`;
        }

        document.getElementById('modalDetail').classList.remove('hidden');
    }
    function closeDetailModal() {
        document.getElementById('modalDetail').classList.add('hidden');
    }
</script>
@endsection