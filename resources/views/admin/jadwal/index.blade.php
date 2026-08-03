@extends('layouts.admin')
@section('title', 'Daftar Jadwal Pelajaran')

@section('content')
<div class="space-y-6">

    {{-- Flash Notification --}}
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-emerald-600"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation text-rose-600"></i>
                <span>{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    {{-- Combined Header, Actions & Stats Card --}}
    <div class="bg-white border-y border-r border-gray-200 border-l-4 border-l-emerald-600 rounded-r-xl p-5 shadow-sm space-y-6">
        
        {{-- Top Row: Header & Action Buttons --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-gray-100 pb-5">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                    <i class="fa-solid fa-calendar-days text-green-600"></i> DATA JADWAL PELAJARAN
                </h1>
                <p class="text-sm text-gray-500 mt-0.5">Atur dan pantau jadwal pelajaran sekolah secara terpusat.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.jadwal.exportExcel') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium transition shadow-sm flex items-center gap-2">
                    <i class="fa-solid fa-file-excel"></i> Export Excel
                </a>
                <a href="{{ route('admin.jadwal.pdf') }}" target="_blank" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white  text-sm font-medium transition shadow-sm flex items-center gap-2">
                    <i class="fa-solid fa-file-pdf"></i> Cetak PDF
                </a>
                <button onclick="openCreateModal()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium transition shadow-sm flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i> Tambah Jadwal
                </button>
            </div>
        </div>

        {{-- Bottom Row: Statistik Hari --}}
        <div>
            <h2 class="text-sm font-semibold uppercase tracking-wider text-black mb-3">Ringkasan Sesi per Hari</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
                @php
                    $hariOrder = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                @endphp
                @foreach($hariOrder as $hari)
                    @php
                        $stat = isset($statistik) ? $statistik->firstWhere('hari', $hari) : null;
                    @endphp
                    <div class="bg-green-500 border border-gray-200/80 p-3 hover:bg-green-600 hover:border-green-300 hover:shadow-sm transition">
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-xs font-bold uppercase tracking-wider text-white">{{ $hari }}</span>
                            <i class="fa-regular fa-clock text-white text-xs"></i>
                        </div>
                        <div class="text-lg font-bold text-white mb-1">
                            {{ $stat ? $stat->jumlah_jadwal : 0 }} <span class="text-xs font-normal text-white">Sesi</span>
                        </div>
                        <div class="text-[11px] text-white space-y-0.5">
                            <p class="truncate"><i class="fa-solid fa-user-tie text-[9px] w-3 text-white"></i> {{ $stat ? $stat->jumlah_guru : 0 }} Guru</p>
                            <p class="truncate"><i class="fa-solid fa-book text-[9px] w-3 text-white"></i> {{ $stat ? $stat->jumlah_mapel : 0 }} Mapel</p>
                            <p class="truncate"><i class="fa-solid fa-door-open text-[9px] w-3 text-white"></i> {{ $stat ? $stat->jumlah_kelas : 0 }} Kelas</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    {{-- Filter & Search Section --}}
    <div class="bg-white border border-gray-200 p-4 shadow-sm flex flex-col md:flex-row gap-3 justify-between items-center">
        <form action="{{ route('admin.jadwal.index') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            <div class="relative w-full md:w-64">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari mapel, guru, kelas..." class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <select name="hari" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">-- Semua Hari --</option>
                @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $h)
                    <option value="{{ $h }}" {{ request('hari') == $h ? 'selected' : '' }}>{{ $h }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition">
                Filter
            </button>
            @if(request('search') || request('hari'))
                <a href="{{ route('admin.jadwal.index') }}" class="text-sm text-rose-600 hover:underline">Reset Filter</a>
            @endif
        </form>
    </div>

    {{-- Table Section --}}
    <div class="bg-white border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-green-600 text-white text-sm uppercase tracking-wider border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3.5 font-semibold">Hari & Waktu</th>
                        <th class="px-4 py-3.5 font-semibold">Mata Pelajaran</th>
                        <th class="px-4 py-3.5 font-semibold">Guru Pengajar</th>
                        <th class="px-4 py-3.5 font-semibold">Kelas</th>
                        <th class="px-4 py-3.5 font-semibold">Status</th>
                        <th class="px-4 py-3.5 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($jadwals as $jadwal)
                        <tr class="hover:bg-gray-50/80 transition">
                            <td class="px-4 py-3 font-medium">
                                <span class="px-2 py-0.5 rounded text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                    {{ $jadwal->hari }}
                                </span>
                                <div class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                    <i class="fa-regular fa-clock text-gray-400"></i>
                                    {{ ($jadwal->jam_masuk) }} - {{($jadwal->jam_pulang) }}
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-semibold text-gray-900">{{ $jadwal->mapel->nama_mapel ?? '-' }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-gray-700">{{ $jadwal->guru->nama ?? $jadwal->guru->user->name ?? '-' }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded bg-gray-100 border border-gray-200 text-gray-700 text-xs font-medium">
                                    {{ $jadwal->kelas->nama_kelas ?? '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @if($jadwal->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick='openDetailModal(@json($jadwal))' class="p-1.5 bg-gray-100 hover:bg-gray-200 text-blue-600 rounded-lg transition" title="Detail">
                                        <i class="fa-solid fa-eye w-4 h-4"></i>
                                    </button>
                                    <button onclick='openEditModal(@json($jadwal))' class="p-1.5 bg-gray-100 hover:bg-gray-200 text-amber-600 rounded-lg transition" title="Edit">
                                        <i class="fa-solid fa-pen-to-square w-4 h-4"></i>
                                    </button>
                                    <form action="{{ route('admin.jadwal.destroy', $jadwal->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 bg-gray-100 hover:bg-gray-200 text-rose-600 rounded-lg transition" title="Hapus">
                                            <i class="fa-solid fa-trash-can w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-400">
                                <i class="fa-solid fa-calendar-xmark text-3xl mb-2"></i>
                                <p class="text-sm text-gray-500">Belum ada data jadwal pelajaran.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        @if($jadwals->hasPages())
            <div class="p-4 border-t border-gray-200 bg-gray-50">
                {{ $jadwals->links() }}
            </div>
        @endif
    </div>
</div>

{{-- MODAL TAMBAH --}}
<div id="modalCreate" class="fixed inset-0 z-50 hidden bg-gray-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white border border-gray-200 rounded-xl shadow-xl w-full max-w-lg overflow-hidden transform transition-all">
        <div class="p-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <i class="fa-solid fa-plus text-indigo-600"></i> Tambah Jadwal Pelajaran
            </h3>
            <button onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        <form action="{{ route('admin.jadwal.store') }}" method="POST" class="p-4 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Hari</label>
                <select name="hari" required class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">-- Pilih Hari --</option>
                    @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $h)
                        <option value="{{ $h }}">{{ $h }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Jam Masuk</label>
                    <input type="time" name="jam_masuk" required class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Jam Pulang</label>
                    <input type="time" name="jam_pulang" required class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Guru Pengajar</label>
                <select name="guru_id" required class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">-- Pilih Guru --</option>
                    @foreach($gurus as $guru)
                        <option value="{{ $guru->id }}">{{ $guru->nama ?? $guru->user->name ?? 'Guru ID: ' . $guru->id }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Mata Pelajaran</label>
                    <select name="mapel_id" required class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">-- Pilih Mapel --</option>
                        @foreach($mapels as $mapel)
                            <option value="{{ $mapel->id }}">{{ $mapel->nama_mapel }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Kelas</label>
                    <select name="kelas_id" required class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelases as $kelas)
                            <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex items-center gap-2 pt-2">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" id="create_is_active" value="1" checked class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                <label for="create_is_active" class="text-sm font-medium text-gray-700">Setel sebagai Jadwal Aktif</label>
            </div>
            <div class="pt-4 flex justify-end gap-2 border-t border-gray-200">
                <button type="button" onclick="closeCreateModal()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium">Batal</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium shadow-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT --}}
<div id="modalEdit" class="fixed inset-0 z-50 hidden bg-gray-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white border border-gray-200 rounded-xl shadow-xl w-full max-w-lg overflow-hidden transform transition-all">
        <div class="p-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square text-amber-600"></i> Edit Jadwal Pelajaran
            </h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        <form id="formEdit" method="POST" class="p-4 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Hari</label>
                <select name="hari" id="edit_hari" required class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $h)
                        <option value="{{ $h }}">{{ $h }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Jam Masuk</label>
                    <input type="time" name="jam_masuk" id="edit_jam_masuk" required class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Jam Pulang</label>
                    <input type="time" name="jam_pulang" id="edit_jam_pulang" required class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Guru Pengajar</label>
                <select name="guru_id" id="edit_guru_id" required class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @foreach($gurus as $guru)
                        <option value="{{ $guru->id }}">{{ $guru->nama ?? $guru->user->name ?? 'Guru ID: ' . $guru->id }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Mata Pelajaran</label>
                    <select name="mapel_id" id="edit_mapel_id" required class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @foreach($mapels as $mapel)
                            <option value="{{ $mapel->id }}">{{ $mapel->nama_mapel }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Kelas</label>
                    <select name="kelas_id" id="edit_kelas_id" required class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @foreach($kelases as $kelas)
                            <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex items-center gap-2 pt-2">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" id="edit_is_active" value="1" class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                <label for="edit_is_active" class="text-sm font-medium text-gray-700">Setel sebagai Jadwal Aktif</label>
            </div>
            <div class="pt-4 flex justify-end gap-2 border-t border-gray-200">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium">Batal</button>
                <button type="submit" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-sm font-medium shadow-sm">Perbarui</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL DETAIL --}}
<div id="modalDetail" class="fixed inset-0 z-50 hidden bg-gray-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white border border-gray-200 rounded-xl shadow-xl w-full max-w-md overflow-hidden transform transition-all">
        <div class="p-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <i class="fa-solid fa-circle-info text-blue-600"></i> Detail Jadwal
            </h3>
            <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        <div class="p-5 space-y-4 text-sm">
            <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                <span class="text-gray-500 font-medium">Hari & Waktu</span>
                <span class="text-gray-900 font-semibold text-right" id="detail_waktu">-</span>
            </div>
            <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                <span class="text-gray-500 font-medium">Mata Pelajaran</span>
                <span class="text-gray-900 font-semibold text-right" id="detail_mapel">-</span>
            </div>
            <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                <span class="text-gray-500 font-medium">Guru Pengajar</span>
                <span class="text-gray-900 font-semibold text-right" id="detail_guru">-</span>
            </div>
            <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                <span class="text-gray-500 font-medium">Kelas</span>
                <span class="text-gray-900 font-semibold text-right" id="detail_kelas">-</span>
            </div>
            <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                <span class="text-gray-500 font-medium">Status</span>
                <span id="detail_status">-</span>
            </div>
            <div class="pt-2 flex justify-end">
                <button type="button" onclick="closeDetailModal()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg text-sm w-full">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    function openCreateModal() {
        document.getElementById('modalCreate').classList.remove('hidden');
    }
    function closeCreateModal() {
        document.getElementById('modalCreate').classList.add('hidden');
    }

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
            statusEl.innerHTML = `<span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">Aktif</span>`;
        } else {
            statusEl.innerHTML = `<span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">Nonaktif</span>`;
        }

        document.getElementById('modalDetail').classList.remove('hidden');
    }
    function closeDetailModal() {
        document.getElementById('modalDetail').classList.add('hidden');
    }
</script>
@endsection