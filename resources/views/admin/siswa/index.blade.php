@extends('layouts.admin') 
@section('title', 'Daftar Siswa')

@section('content')
<div class="space-y-6">
    <!-- Header Page -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-slate-800/80 p-6 rounded-2xl border border-slate-700/60 shadow-xl backdrop-blur-md">
        <div>
            <h1 class="text-2xl font-bold text-slate-100 flex items-center gap-3">
                <span class="p-2.5 bg-indigo-500/10 text-indigo-400 rounded-xl border border-indigo-500/20">
                    <i class="fa-solid fa-user-graduate text-xl"></i>
                </span>
                Manajemen Data Siswa
            </h1>
            <p class="text-sm text-slate-400 mt-1">Kelola data siswa, pencarian, dan pendaftaran siswa baru secara efisien.</p>
        </div>
        <button onclick="openModal('create')" class="inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 text-white font-medium px-5 py-2.5 rounded-xl shadow-lg shadow-indigo-600/20 transition-all duration-200 transform hover:-translate-y-0.5 cursor-pointer">
            <i class="fa-solid fa-plus mr-2 text-sm"></i>Tambah Siswa
        </button>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-slate-800/60 p-4 rounded-xl shadow-md border border-slate-700/50 backdrop-blur-sm">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-sm"></i>
                </span>
                <input type="text" id="searchInput" oninput="debounceFetchData()" placeholder="Cari Nama, NISN, NIK, NIPD..." class="w-full pl-10 pr-4 py-2.5 bg-slate-900/90 border border-slate-700 text-slate-200 placeholder-slate-500 rounded-lg focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 outline-none transition text-sm">
            </div>
            <div>
                <select id="filterKelas" onchange="fetchSiswa(1)" class="w-full py-2.5 px-3 bg-slate-900/90 border border-slate-700 text-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 outline-none transition text-sm">
                    <option value="">-- Semua Kelas --</option>
                    @foreach($kelas as $k)
                        <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select id="perPage" onchange="fetchSiswa(1)" class="w-full py-2.5 px-3 bg-slate-900/90 border border-slate-700 text-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 outline-none transition text-sm">
                    <option value="10">10 data per halaman</option>
                    <option value="25">25 data per halaman</option>
                    <option value="50">50 data per halaman</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Table Container -->
    <div class="bg-slate-800/80 rounded-xl shadow-xl border border-slate-700/60 overflow-hidden backdrop-blur-md">
        <div class="overflow-x-auto relative min-h-[300px]">
            <!-- Loading Overlay -->
            <div id="tableLoader" class="absolute inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center z-10 hidden transition-opacity">
                <div class="flex items-center space-x-3 px-4 py-2 bg-slate-800 border border-slate-700 rounded-xl shadow-xl">
                    <i class="fa-solid fa-circle-notch fa-spin text-indigo-400 text-lg"></i>
                    <span class="text-sm font-medium text-slate-200">Memuat data...</span>
                </div>
            </div>

            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-900/80 border-b border-slate-700/80 text-xs font-semibold uppercase tracking-wider text-slate-400">
                        <th class="py-3.5 px-4">No</th>
                        <th class="py-3.5 px-4">Siswa</th>
                        <th class="py-3.5 px-4">NISN / NIK</th>
                        <th class="py-3.5 px-4">Kelas</th>
                        <th class="py-3.5 px-4">L/P</th>
                        <th class="py-3.5 px-4">No. HP</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="siswaTableBody" class="divide-y divide-slate-700/40 text-sm">
                    <!-- Dynamic Data -->
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 border-t border-slate-700/60 flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-slate-400 bg-slate-900/30">
            <div id="paginationInfo" class="font-medium">Menampilkan 0 data</div>
            <div class="flex gap-1 overflow-x-auto max-w-full py-1" id="paginationNav"></div>
        </div>
    </div>
</div>

<!-- ================= MODAL FORM (TAMBAH / EDIT) ================= -->
<div id="formModal" class="fixed inset-0 z-50 hidden bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4 transition-all">
    <div class="bg-slate-800 rounded-2xl shadow-2xl border border-slate-700/80 w-full max-w-4xl max-h-[90vh] flex flex-col overflow-hidden transform transition-all scale-95 opacity-0 duration-200" id="formModalCard">
        <!-- Header Modal (Fixed) -->
        <div class="px-6 py-4 bg-slate-900 border-b border-slate-700/80 flex justify-between items-center shrink-0">
            <h3 class="font-bold text-lg text-slate-100 flex items-center gap-2.5" id="modalFormTitle">
                <i class="fa-solid fa-user-plus text-indigo-400"></i>
                <span>Tambah Data Siswa</span>
            </h3>
            <button onclick="closeModal('formModal')" class="text-slate-400 hover:text-white hover:bg-slate-800 p-1.5 rounded-lg transition text-xl leading-none cursor-pointer">&times;</button>
        </div>
        
        <!-- Form Area (Scrollable Inner Body) -->
        <form id="siswaForm" onsubmit="submitForm(event)" class="flex flex-col overflow-hidden flex-1">
            <div class="p-6 overflow-y-auto space-y-6 flex-1 custom-scrollbar">
                <input type="hidden" id="siswaId">
                
                <!-- Section 1: Data Utama -->
                <div>
                    <h4 class="text-xs font-semibold text-indigo-400 uppercase tracking-wider mb-3 flex items-center gap-2">
                        <i class="fa-solid fa-id-card"></i> Informasi Identitas Utama
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-3">
                            <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Nama Lengkap <span class="text-rose-400">*</span></label>
                            <input type="text" id="nama" name="nama" required class="w-full px-3.5 py-2 bg-slate-900/90 border border-slate-700 text-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500/50 outline-none text-sm">
                            <span class="text-xs text-rose-400 error-msg mt-0.5 block" id="err_nama"></span>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">NISN <span class="text-rose-400">*</span></label>
                            <input type="text" id="nisn" name="nisn" required class="w-full px-3.5 py-2 bg-slate-900/90 border border-slate-700 text-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500/50 outline-none text-sm">
                            <span class="text-xs text-rose-400 error-msg mt-0.5 block" id="err_nisn"></span>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">NIK</label>
                            <input type="text" id="nik" name="nik" class="w-full px-3.5 py-2 bg-slate-900/90 border border-slate-700 text-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500/50 outline-none text-sm">
                            <span class="text-xs text-rose-400 error-msg mt-0.5 block" id="err_nik"></span>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">NIPD</label>
                            <input type="text" id="nipd" name="nipd" class="w-full px-3.5 py-2 bg-slate-900/90 border border-slate-700 text-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500/50 outline-none text-sm">
                            <span class="text-xs text-rose-400 error-msg mt-0.5 block" id="err_nipd"></span>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Kelas <span class="text-rose-400">*</span></label>
                            <select id="kelas_id" name="kelas_id" required class="w-full px-3.5 py-2 bg-slate-900/90 border border-slate-700 text-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500/50 outline-none text-sm">
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($kelas as $k)
                                    <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                                @endforeach
                            </select>
                            <span class="text-xs text-rose-400 error-msg mt-0.5 block" id="err_kelas_id"></span>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Jenis Kelamin <span class="text-rose-400">*</span></label>
                            <select id="jenis_kelamin" name="jenis_kelamin" required class="w-full px-3.5 py-2 bg-slate-900/90 border border-slate-700 text-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500/50 outline-none text-sm">
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                            <span class="text-xs text-rose-400 error-msg mt-0.5 block" id="err_jenis_kelamin"></span>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">No HP / WhatsApp</label>
                            <input type="text" id="no_hp" name="no_hp" class="w-full px-3.5 py-2 bg-slate-900/90 border border-slate-700 text-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500/50 outline-none text-sm">
                            <span class="text-xs text-rose-400 error-msg mt-0.5 block" id="err_no_hp"></span>
                        </div>
                    </div>
                </div>

                <hr class="border-slate-700/60">

                <!-- Section 2: Informasi Pribadi & Orang Tua -->
                <div>
                    <h4 class="text-xs font-semibold text-indigo-400 uppercase tracking-wider mb-3 flex items-center gap-2">
                        <i class="fa-solid fa-user"></i> Informasi Pribadi & Orang Tua
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Tempat Lahir</label>
                            <input type="text" id="tempat_lahir" name="tempat_lahir" class="w-full px-3.5 py-2 bg-slate-900/90 border border-slate-700 text-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500/50 outline-none text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Tanggal Lahir</label>
                            <input type="date" id="tanggal_lahir" name="tanggal_lahir" class="w-full px-3.5 py-2 bg-slate-900/90 border border-slate-700 text-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500/50 outline-none text-sm [color-scheme:dark]">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Agama</label>
                            <input type="text" id="agama" name="agama" class="w-full px-3.5 py-2 bg-slate-900/90 border border-slate-700 text-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500/50 outline-none text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Sekolah Asal</label>
                            <input type="text" id="sekolah_asal" name="sekolah_asal" class="w-full px-3.5 py-2 bg-slate-900/90 border border-slate-700 text-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500/50 outline-none text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Nama Ayah</label>
                            <input type="text" id="nama_ayah" name="nama_ayah" class="w-full px-3.5 py-2 bg-slate-900/90 border border-slate-700 text-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500/50 outline-none text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Pekerjaan Ayah</label>
                            <input type="text" id="pekerjaan_ayah" name="pekerjaan_ayah" class="w-full px-3.5 py-2 bg-slate-900/90 border border-slate-700 text-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500/50 outline-none text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Nama Ibu</label>
                            <input type="text" id="nama_ibu" name="nama_ibu" class="w-full px-3.5 py-2 bg-slate-900/90 border border-slate-700 text-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500/50 outline-none text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Pekerjaan Ibu</label>
                            <input type="text" id="pekerjaan_ibu" name="pekerjaan_ibu" class="w-full px-3.5 py-2 bg-slate-900/90 border border-slate-700 text-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500/50 outline-none text-sm">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Alamat Tempat Tinggal</label>
                            <textarea id="alamat" name="alamat" rows="2" class="w-full px-3.5 py-2 bg-slate-900/90 border border-slate-700 text-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500/50 outline-none text-sm resize-none"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer (Fixed at Bottom) -->
            <div class="p-4 bg-slate-900 border-t border-slate-700/80 flex justify-end gap-3 shrink-0">
                <button type="button" onclick="closeModal('formModal')" class="px-4 py-2 border border-slate-600 rounded-lg text-slate-300 hover:bg-slate-800 text-sm font-medium transition cursor-pointer">Batal</button>
                <button type="submit" id="submitBtn" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg text-sm font-medium shadow-lg shadow-indigo-600/20 transition flex items-center gap-2 cursor-pointer">
                    <span id="submitSpinner" class="hidden"><i class="fa-solid fa-circle-notch fa-spin"></i></span>
                    <span id="submitBtnText">Simpan Data</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ================= MODAL DETAIL SHOW ================= -->
<div id="showModal" class="fixed inset-0 z-50 hidden bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4 transition-all">
    <div class="bg-slate-800 rounded-2xl shadow-2xl border border-slate-700/80 w-full max-w-2xl max-h-[90vh] flex flex-col overflow-hidden transform transition-all scale-95 opacity-0 duration-200" id="showModalCard">
        <!-- Header -->
        <div class="px-6 py-4 bg-slate-900 border-b border-slate-700/80 flex justify-between items-center shrink-0">
            <h3 class="font-bold text-lg text-slate-100 flex items-center gap-2.5">
                <i class="fa-solid fa-address-card text-emerald-400"></i>
                <span>Detail Data Siswa</span>
            </h3>
            <button onclick="closeModal('showModal')" class="text-slate-400 hover:text-white hover:bg-slate-800 p-1.5 rounded-lg transition text-xl leading-none cursor-pointer">&times;</button>
        </div>
        
        <!-- Detail Body (Scrollable) -->
        <div class="p-6 overflow-y-auto flex-1 space-y-4" id="showModalBody">
            <!-- Dynamic Content -->
        </div>

        <!-- Footer -->
        <div class="p-4 bg-slate-900 border-t border-slate-700/80 flex justify-end shrink-0">
            <button type="button" onclick="closeModal('showModal')" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-200 rounded-lg text-sm font-medium transition cursor-pointer">Tutup</button>
        </div>
    </div>
</div>

<!-- Custom Scrollbar & Utility CSS -->
<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: rgba(15, 23, 42, 0.6);
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(51, 65, 85, 0.8);
        border-radius: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(71, 85, 105, 1);
    }
</style>

<!-- JavaScript Handlers -->
<script>
    const API_URL = "{{ route('admin.siswa.index') }}";
    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    let debounceTimer;

    document.addEventListener('DOMContentLoaded', () => {
        fetchSiswa();
    });

    async function fetchSiswa(page = 1) {
        showLoader(true);
        const search = document.getElementById('searchInput').value;
        const kelasId = document.getElementById('filterKelas').value;
        const perPage = document.getElementById('perPage').value;

        const url = `${API_URL}?page=${page}&search=${encodeURIComponent(search)}&kelas_id=${kelasId}&per_page=${perPage}`;

        try {
            const res = await fetch(url, { 
                headers: { 
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                } 
            });
            const json = await res.json();

            if (json.status === 'success') {
                renderTable(json.data.data, json.data.from);
                renderPagination(json.data);
            }
        } catch (err) {
            console.error("Gagal mengambil data:", err);
        } finally {
            showLoader(false);
        }
    }

    function debounceFetchData() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => fetchSiswa(1), 400);
    }

    function showLoader(show) {
        const loader = document.getElementById('tableLoader');
        if (show) loader.classList.remove('hidden');
        else loader.classList.add('hidden');
    }

    function renderTable(data, startingNumber) {
        const tbody = document.getElementById('siswaTableBody');
        tbody.innerHTML = '';

        if (!data || data.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="py-12 text-center text-slate-500">
                        <i class="fa-solid fa-folder-open text-3xl mb-2 text-slate-600 block"></i>
                        Data siswa tidak ditemukan.
                    </td>
                </tr>`;
            return;
        }

        data.forEach((siswa, index) => {
            const no = (startingNumber || 1) + index;
            const namaKelas = siswa.kelas ? siswa.kelas.nama_kelas : '-';
            const genderBadge = siswa.jenis_kelamin === 'L' 
                ? `<span class="bg-blue-950/80 text-blue-400 border border-blue-800/80 px-2 py-0.5 rounded text-xs font-semibold">L</span>`
                : `<span class="bg-pink-950/80 text-pink-400 border border-pink-800/80 px-2 py-0.5 rounded text-xs font-semibold">P</span>`;

            tbody.innerHTML += `
                <tr class="hover:bg-slate-700/30 transition border-b border-slate-700/40">
                    <td class="py-3 px-4 font-medium text-slate-400">${no}</td>
                    <td class="py-3 px-4 font-semibold text-slate-100">${siswa.nama}</td>
                    <td class="py-3 px-4 text-xs">
                        <div class="text-slate-300 font-medium"><i class="fa-solid fa-id-card text-slate-500 mr-1.5"></i>${siswa.nisn}</div>
                        ${siswa.nik ? `<div class="text-slate-500 pl-5">${siswa.nik}</div>` : ''}
                    </td>
                    <td class="py-3 px-4"><span class="bg-indigo-950/80 text-indigo-300 border border-indigo-800/80 px-2.5 py-1 rounded-md text-xs font-medium">${namaKelas}</span></td>
                    <td class="py-3 px-4">${genderBadge}</td>
                    <td class="py-3 px-4 text-slate-300">${siswa.no_hp || '-'}</td>
                    <td class="py-3 px-4 text-center space-x-1">
                        <button onclick="showSiswa(${siswa.id})" title="Detail" class="p-2 text-emerald-400 hover:bg-emerald-950/50 rounded-lg transition cursor-pointer"><i class="fa-solid fa-eye"></i></button>
                        <button onclick="editSiswa(${siswa.id})" title="Edit" class="p-2 text-amber-400 hover:bg-amber-950/50 rounded-lg transition cursor-pointer"><i class="fa-solid fa-pen-to-square"></i></button>
                        <button onclick="deleteSiswa(${siswa.id})" title="Hapus" class="p-2 text-rose-400 hover:bg-rose-950/50 rounded-lg transition cursor-pointer"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
            `;
        });
    }

    function renderPagination(meta) {
        const info = document.getElementById('paginationInfo');
        const nav = document.getElementById('paginationNav');
        info.innerText = `Menampilkan ${meta.from || 0} - ${meta.to || 0} dari total ${meta.total} siswa`;
        nav.innerHTML = '';

        if (!meta.links) return;

        meta.links.forEach(link => {
            if (link.url) {
                const pageNum = new URL(link.url).searchParams.get('page');
                const isActive = link.active ? 'bg-indigo-600 text-white shadow-md' : 'bg-slate-700/80 hover:bg-slate-600 text-slate-300';
                nav.innerHTML += `<button onclick="fetchSiswa(${pageNum})" class="px-3 py-1.5 text-xs font-medium rounded-lg transition ${isActive} cursor-pointer">${link.label}</button>`;
            } else {
                nav.innerHTML += `<span class="px-3 py-1.5 text-xs font-medium rounded-lg text-slate-600 bg-slate-800/40 cursor-not-allowed">${link.label}</span>`;
            }
        });
    }

    function openModal(type) {
        clearErrors();
        if (type === 'create') {
            document.getElementById('siswaForm').reset();
            document.getElementById('siswaId').value = '';
            document.getElementById('modalFormTitle').innerHTML = '<i class="fa-solid fa-user-plus text-indigo-400"></i><span>Tambah Data Siswa</span>';
            
            const modal = document.getElementById('formModal');
            const card = document.getElementById('formModalCard');
            modal.classList.remove('hidden');
            setTimeout(() => {
                card.classList.remove('scale-95', 'opacity-0');
                card.classList.add('scale-100', 'opacity-100');
            }, 10);
        }
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        const card = document.getElementById(modalId === 'formModal' ? 'formModalCard' : 'showModalCard');
        
        card.classList.remove('scale-100', 'opacity-100');
        card.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 150);
    }

    async function submitForm(e) {
        e.preventDefault();
        clearErrors();
        setBtnLoading(true);

        const id = document.getElementById('siswaId').value;
        const isEdit = Boolean(id);
        const url = isEdit ? `${API_URL}/${id}` : API_URL;
        const method = isEdit ? 'PUT' : 'POST';

        const formData = new FormData(document.getElementById('siswaForm'));
        const payload = Object.fromEntries(formData.entries());

        try {
            const res = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: JSON.stringify(payload)
            });

            const json = await res.json();

            if (res.status === 422) {
                Object.keys(json.errors).forEach(key => {
                    const errEl = document.getElementById(`err_${key}`);
                    if (errEl) errEl.innerText = json.errors[key][0];
                });
            } else if (json.status === 'success') {
                closeModal('formModal');
                fetchSiswa();
            } else {
                alert(json.message || 'Terjadi kesalahan server.');
            }
        } catch (err) {
            console.error("Error submitting form:", err);
        } finally {
            setBtnLoading(false);
        }
    }

    function setBtnLoading(isLoading) {
        const btn = document.getElementById('submitBtn');
        const spinner = document.getElementById('submitSpinner');
        const text = document.getElementById('submitBtnText');

        if (isLoading) {
            btn.disabled = true;
            spinner.classList.remove('hidden');
            text.innerText = 'Menyimpan...';
        } else {
            btn.disabled = false;
            spinner.classList.add('hidden');
            text.innerText = 'Simpan Data';
        }
    }

    async function showSiswa(id) {
        try {
            const res = await fetch(`${API_URL}/${id}`, { 
                headers: { 
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                } 
            });
            const json = await res.json();

            if (json.status === 'success') {
                const data = json.data;
                const container = document.getElementById('showModalBody');
                container.innerHTML = `
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div class="md:col-span-2 border-b border-slate-700/80 pb-3">
                            <p class="text-xs text-slate-400 uppercase font-semibold">Nama Siswa</p>
                            <p class="text-lg font-bold text-slate-100 mt-0.5">${data.nama}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 uppercase font-semibold">NISN / NIK</p>
                            <p class="font-medium text-slate-200 mt-0.5">${data.nisn || '-'} / ${data.nik || '-'}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 uppercase font-semibold">Kelas</p>
                            <p class="font-medium text-slate-200 mt-0.5">${data.kelas ? data.kelas.nama_kelas : '-'}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 uppercase font-semibold">Jenis Kelamin</p>
                            <p class="font-medium text-slate-200 mt-0.5">${data.jenis_kelamin === 'L' ? 'Laki-Laki' : 'Perempuan'}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 uppercase font-semibold">No. HP / WA</p>
                            <p class="font-medium text-slate-200 mt-0.5">${data.no_hp || '-'}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 uppercase font-semibold">Tempat, Tanggal Lahir</p>
                            <p class="font-medium text-slate-200 mt-0.5">${data.tempat_lahir || '-'}${data.tanggal_lahir ? ', ' + data.tanggal_lahir : ''}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 uppercase font-semibold">Agama</p>
                            <p class="font-medium text-slate-200 mt-0.5">${data.agama || '-'}</p>
                        </div>
                        <div class="md:col-span-2 border-t border-slate-700/80 pt-3">
                            <p class="text-xs text-indigo-400 uppercase font-semibold mb-1">Informasi Orang Tua</p>
                            <p class="font-medium text-slate-200">Ayah: ${data.nama_ayah || '-'} <span class="text-slate-400 text-xs">(${data.pekerjaan_ayah || '-'})</span></p>
                            <p class="font-medium text-slate-200 mt-1">Ibu: ${data.nama_ibu || '-'} <span class="text-slate-400 text-xs">(${data.pekerjaan_ibu || '-'})</span></p>
                        </div>
                        <div class="md:col-span-2 border-t border-slate-700/80 pt-3">
                            <p class="text-xs text-slate-400 uppercase font-semibold">Alamat</p>
                            <p class="font-medium text-slate-200 mt-0.5">${data.alamat || '-'}</p>
                        </div>
                    </div>
                `;

                const modal = document.getElementById('showModal');
                const card = document.getElementById('showModalCard');
                modal.classList.remove('hidden');
                setTimeout(() => {
                    card.classList.remove('scale-95', 'opacity-0');
                    card.classList.add('scale-100', 'opacity-100');
                }, 10);
            }
        } catch (err) {
            console.error("Gagal mengambil detail:", err);
        }
    }

    async function editSiswa(id) {
        clearErrors();
        try {
            const res = await fetch(`${API_URL}/${id}`, { 
                headers: { 
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                } 
            });
            const json = await res.json();

            if (json.status === 'success') {
                const data = json.data;
                document.getElementById('siswaId').value = data.id;
                document.getElementById('nama').value = data.nama || '';
                document.getElementById('nisn').value = data.nisn || '';
                document.getElementById('nik').value = data.nik || '';
                document.getElementById('nipd').value = data.nipd || '';
                document.getElementById('kelas_id').value = data.kelas_id || '';
                document.getElementById('jenis_kelamin').value = data.jenis_kelamin || 'L';
                document.getElementById('no_hp').value = data.no_hp || '';
                document.getElementById('tempat_lahir').value = data.tempat_lahir || '';
                document.getElementById('tanggal_lahir').value = data.tanggal_lahir || '';
                document.getElementById('agama').value = data.agama || '';
                document.getElementById('sekolah_asal').value = data.sekolah_asal || '';
                document.getElementById('nama_ayah').value = data.nama_ayah || '';
                document.getElementById('pekerjaan_ayah').value = data.pekerjaan_ayah || '';
                document.getElementById('nama_ibu').value = data.nama_ibu || '';
                document.getElementById('pekerjaan_ibu').value = data.pekerjaan_ibu || '';
                document.getElementById('alamat').value = data.alamat || '';

                document.getElementById('modalFormTitle').innerHTML = '<i class="fa-solid fa-pen-to-square text-amber-400"></i><span>Edit Data Siswa</span>';
                
                const modal = document.getElementById('formModal');
                const card = document.getElementById('formModalCard');
                modal.classList.remove('hidden');
                setTimeout(() => {
                    card.classList.remove('scale-95', 'opacity-0');
                    card.classList.add('scale-100', 'opacity-100');
                }, 10);
            }
        } catch (err) {
            console.error("Gagal mengambil data edit:", err);
        }
    }

    async function deleteSiswa(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus data siswa ini?')) return;

        try {
            const res = await fetch(`${API_URL}/${id}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                }
            });

            const json = await res.json();
            if (json.status === 'success') {
                fetchSiswa();
            } else {
                alert(json.message || 'Gagal menghapus data.');
            }
        } catch (err) {
            console.error("Gagal menghapus data:", err);
        }
    }

    function clearErrors() {
        document.querySelectorAll('.error-msg').forEach(el => el.innerText = '');
    }
</script>
@endsection