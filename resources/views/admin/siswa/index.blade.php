@extends('layouts.admin')

@section('title', 'Manajemen Data Siswa')

@section('content')
<div class="space-y-6">
    <!-- Combined Header, Filter, & Action Card (Border Left Hijau, No Rounded) -->
    <div class="bg-white border-l-4 border-l-emerald-500 border border-gray-200 shadow-sm p-5 rounded-none">
        <!-- Section 1: Header & Button Tambah -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 pb-5 border-b border-gray-100">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Data Siswa</h1>
                <p class="text-sm text-gray-500">Kelola seluruh data siswa, pencarian, serta pembaruan profil.</p>
            </div>
            <button onclick="openModal('create')" class="inline-flex items-center justify-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg text-sm transition-all shadow-md shadow-emerald-100">
                <i class="fa-solid fa-plus mr-2"></i> Tambah Siswa
            </button>
        </div>

        <!-- Section 2: Filter & Search Bar -->
        <div class="pt-5">
            <form id="filterForm" class="grid grid-cols-1 md:grid-cols-12 gap-4">
                <!-- Search Input -->
                <div class="md:col-span-6 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>
                    <input type="text" id="searchInput" name="search" value="{{ request('search') }}" placeholder="Cari Nama, NISN, NIK, NIPD..." 
                        class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm text-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 placeholder-gray-400 transition-all">
                </div>

                <!-- Filter Kelas -->
                <div class="md:col-span-4">
                    <select id="filterKelas" name="kelas_id" class="w-full py-2 px-3 bg-gray-50 border border-gray-300 rounded-lg text-sm text-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all">
                        <option value="">-- Semua Kelas --</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas ?? $k->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Action Filter Buttons -->
                <div class="md:col-span-2 flex gap-2">
                    <button type="submit" class="w-full py-2 px-4 bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium rounded-lg transition-colors flex items-center justify-center shadow-sm">
                        <i class="fa-solid fa-filter mr-2"></i> Filter
                    </button>
                    <a href="{{ route('admin.siswa.index') }}" class="py-2 px-3 bg-white hover:bg-gray-100 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg transition-colors flex items-center justify-center shadow-sm" title="Reset Filter">
                        <i class="fa-solid fa-rotate"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Section (Tanpa Rounded) -->
    <div class="bg-white border border-gray-200 shadow-sm overflow-hidden rounded-none">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-600">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-center">No</th>
                        <th scope="col" class="px-4 py-3">Nama Lengkap</th>
                        <th scope="col" class="px-4 py-3">NISN / NIK</th>
                        <th scope="col" class="px-4 py-3">Kelas</th>
                        <th scope="col" class="px-4 py-3">L/P</th>
                        <th scope="col" class="px-4 py-3">No HP</th>
                        <th scope="col" class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="siswaTableBody" class="divide-y divide-gray-200">
                    <!-- Data di-render lewat AJAX atau Server Fallback -->
                    <tr id="loadingRow" class="hidden">
                        <td colspan="7" class="text-center py-8 text-gray-500">
                            <i class="fa-solid fa-spinner fa-spin text-2xl text-emerald-600 mb-2"></i>
                            <p>Memuat data...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination Container -->
        <div id="paginationContainer" class="p-4 border-t border-gray-200 bg-gray-50 flex flex-col md:flex-row items-center justify-between gap-4">
            <!-- Rendered by JavaScript -->
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL FORM (TAMBAH & EDIT) -->
<!-- ========================================== -->
<div id="formModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="relative w-full max-w-4xl bg-white border border-gray-200 rounded-xl shadow-2xl overflow-hidden">
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h3 id="modalTitle" class="text-lg font-semibold text-gray-800">Tambah Data Siswa</h3>
            <button onclick="closeModal('formModal')" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        <!-- Body Form -->
        <form id="siswaForm" onsubmit="submitForm(event)" class="p-6 space-y-6 max-h-[80vh] overflow-y-auto">
            <input type="hidden" id="siswa_id" name="id">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Identitas Utama -->
                <div class="md:col-span-3 border-b border-gray-200 pb-2">
                    <h4 class="text-sm font-semibold text-emerald-600 uppercase tracking-wider">Identitas Utama</h4>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" id="nama" name="nama" required class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm text-gray-800 focus:ring-1 focus:ring-emerald-500">
                    <span class="text-red-500 text-xs error-text nama_error"></span>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">NISN <span class="text-red-500">*</span></label>
                    <input type="text" id="nisn" name="nisn" required class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm text-gray-800 focus:ring-1 focus:ring-emerald-500">
                    <span class="text-red-500 text-xs error-text nisn_error"></span>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Kelas <span class="text-red-500">*</span></label>
                    <select id="kelas_id" name="kelas_id" required class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm text-gray-800 focus:ring-1 focus:ring-emerald-500">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kelas ?? $k->nama }}</option>
                        @endforeach
                    </select>
                    <span class="text-red-500 text-xs error-text kelas_id_error"></span>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">NIK</label>
                    <input type="text" id="nik" name="nik" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm text-gray-800 focus:ring-1 focus:ring-emerald-500">
                    <span class="text-red-500 text-xs error-text nik_error"></span>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">NIPD</label>
                    <input type="text" id="nipd" name="nipd" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm text-gray-800 focus:ring-1 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                    <select id="jenis_kelamin" name="jenis_kelamin" required class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm text-gray-800 focus:ring-1 focus:ring-emerald-500">
                        <option value="laki-laki">Laki-laki</option>
                        <option value="perempuan">Perempuan</option>
                    </select>
                </div>

                <!-- Biodata & Kelahiran -->
                <div class="md:col-span-3 border-b border-gray-200 pb-2 pt-2">
                    <h4 class="text-sm font-semibold text-emerald-600 uppercase tracking-wider">Biodata & Kelahiran</h4>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Tempat Lahir</label>
                    <input type="text" id="tempat_lahir" name="tempat_lahir" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm text-gray-800 focus:ring-1 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                    <input type="date" id="tanggal_lahir" name="tanggal_lahir" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm text-gray-800 focus:ring-1 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Agama</label>
                    <input type="text" id="agama" name="agama" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm text-gray-800 focus:ring-1 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">No. HP/WA</label>
                    <input type="text" id="no_hp" name="no_hp" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm text-gray-800 focus:ring-1 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Sekolah Asal</label>
                    <input type="text" id="sekolah_asal" name="sekolah_asal" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm text-gray-800 focus:ring-1 focus:ring-emerald-500">
                </div>

                <div class="md:col-span-3">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Alamat</label>
                    <textarea id="alamat" name="alamat" rows="2" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm text-gray-800 focus:ring-1 focus:ring-emerald-500"></textarea>
                </div>

                <!-- Data Orang Tua -->
                <div class="md:col-span-3 border-b border-gray-200 pb-2 pt-2">
                    <h4 class="text-sm font-semibold text-emerald-600 uppercase tracking-wider">Data Orang Tua</h4>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Nama Ayah</label>
                    <input type="text" id="nama_ayah" name="nama_ayah" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm text-gray-800 focus:ring-1 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Pekerjaan Ayah</label>
                    <input type="text" id="pekerjaan_ayah" name="pekerjaan_ayah" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm text-gray-800 focus:ring-1 focus:ring-emerald-500">
                </div>

                <div class="hidden md:block"></div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Nama Ibu</label>
                    <input type="text" id="nama_ibu" name="nama_ibu" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm text-gray-800 focus:ring-1 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Pekerjaan Ibu</label>
                    <input type="text" id="pekerjaan_ibu" name="pekerjaan_ibu" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm text-gray-800 focus:ring-1 focus:ring-emerald-500">
                </div>
            </div>

            <!-- Footer Action -->
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeModal('formModal')" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                    Batal
                </button>
                <button type="submit" id="btnSave" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-colors flex items-center shadow-sm">
                    <i class="fa-solid fa-floppy-disk mr-2"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL SHOW (DETAIL SISWA) -->
<!-- ========================================== -->
<div id="showModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="relative w-full max-w-3xl bg-white border border-gray-200 rounded-xl shadow-2xl overflow-hidden">
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Detail Informasi Siswa</h3>
            <button onclick="closeModal('showModal')" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        <!-- Body Detail -->
        <div class="p-6 space-y-6 max-h-[80vh] overflow-y-auto">
            <div class="flex items-center gap-4 bg-emerald-50/50 p-4 rounded-lg border border-emerald-100">
                <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-2xl font-bold border border-emerald-200">
                    <i class="fa-solid fa-user-graduate"></i>
                </div>
                <div>
                    <h2 id="show_nama" class="text-xl font-bold text-gray-900">-</h2>
                    <p class="text-sm font-medium text-emerald-600" id="show_kelas">-</p>
                    <p class="text-xs text-gray-500" id="show_nisn_nik">-</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                    <span class="text-xs text-gray-500 block">NIPD</span>
                    <span id="show_nipd" class="font-medium text-gray-800">-</span>
                </div>
                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                    <span class="text-xs text-gray-500 block">Jenis Kelamin</span>
                    <span id="show_jk" class="font-medium text-gray-800">-</span>
                </div>
                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                    <span class="text-xs text-gray-500 block">TTL</span>
                    <span id="show_ttl" class="font-medium text-gray-800">-</span>
                </div>
                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                    <span class="text-xs text-gray-500 block">Agama</span>
                    <span id="show_agama" class="font-medium text-gray-800">-</span>
                </div>
                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                    <span class="text-xs text-gray-500 block">No. HP</span>
                    <span id="show_nohp" class="font-medium text-gray-800">-</span>
                </div>
                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                    <span class="text-xs text-gray-500 block">Sekolah Asal</span>
                    <span id="show_sekolah_asal" class="font-medium text-gray-800">-</span>
                </div>
                <div class="md:col-span-2 bg-gray-50 p-3 rounded-lg border border-gray-200">
                    <span class="text-xs text-gray-500 block">Alamat</span>
                    <span id="show_alamat" class="font-medium text-gray-800">-</span>
                </div>
                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                    <span class="text-xs text-gray-500 block">Ayah (Pekerjaan)</span>
                    <span id="show_ayah" class="font-medium text-gray-800">-</span>
                </div>
                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                    <span class="text-xs text-gray-500 block">Ibu (Pekerjaan)</span>
                    <span id="show_ibu" class="font-medium text-gray-800">-</span>
                </div>
            </div>
        </div>

        <div class="flex justify-end p-4 border-t border-gray-200 bg-gray-50">
            <button type="button" onclick="closeModal('showModal')" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
    const csrfToken = '{{ csrf_token() }}';
    let currentUrl = '{{ route("admin.siswa.index") }}';

    document.addEventListener('DOMContentLoaded', () => {
        loadData(currentUrl);

        // Event listener filter form
        document.getElementById('filterForm').addEventListener('submit', (e) => {
            e.preventDefault();
            const search = document.getElementById('searchInput').value;
            const kelas_id = document.getElementById('filterKelas').value;
            const url = new URL('{{ route("admin.siswa.index") }}');
            if (search) url.searchParams.append('search', search);
            if (kelas_id) url.searchParams.append('kelas_id', kelas_id);
            
            loadData(url.toString());
        });
    });

    // Fetch Data Siswa AJAX
    async function loadData(url) {
        showLoading(true);
        try {
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            const result = await response.json();

            if (result.status === 'success') {
                renderTable(result.data.data, result.data.from);
                renderPagination(result.data);
            }
        } catch (error) {
            console.error("Error loading data:", error);
        } finally {
            showLoading(false);
        }
    }

    function renderTable(siswas, fromIndex) {
        const tbody = document.getElementById('siswaTableBody');
        tbody.innerHTML = '';

        if (!siswas || siswas.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center py-8 text-gray-400">Tidak ada data siswa ditemukan.</td>
                </tr>`;
            return;
        }

        siswas.forEach((siswa, index) => {
            const row = `
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 text-center text-gray-500 font-medium">${(fromIndex || 1) + index}</td>
                    <td class="px-4 py-3 font-semibold text-gray-800">${siswa.nama}</td>
                    <td class="px-4 py-3">
                        <div class="text-xs text-gray-800 font-medium">NISN: ${siswa.nisn || '-'}</div>
                        <div class="text-[11px] text-gray-500">NIK: ${siswa.nik || '-'}</div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-md text-xs font-medium">
                            ${siswa.kelas ? (siswa.kelas.nama_kelas || siswa.kelas.nama) : '-'}
                        </span>
                    </td>
                    <td class="px-4 py-3 uppercase text-xs font-medium text-gray-600">${siswa.jenis_kelamin === 'laki-laki' ? 'L' : 'P'}</td>
                    <td class="px-4 py-3 text-xs text-gray-600">${siswa.no_hp || '-'}</td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex items-center justify-center gap-1.5">
                            <button onclick="showDetail(${siswa.id})" class="p-2 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-lg transition-colors border border-blue-100" title="Detail">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                            <button onclick="editSiswa(${siswa.id})" class="p-2 bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white rounded-lg transition-colors border border-amber-100" title="Edit">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button onclick="deleteSiswa(${siswa.id})" class="p-2 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-lg transition-colors border border-red-100" title="Hapus">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
            tbody.insertAdjacentHTML('beforeend', row);
        });
    }

    function renderPagination(data) {
        const container = document.getElementById('paginationContainer');
        if (!data.links || data.links.length <= 3) {
            container.innerHTML = `<span class="text-xs text-gray-500">Menampilkan ${data.total} total data</span>`;
            return;
        }

        let linksHtml = '';
        data.links.forEach(link => {
            if (link.url) {
                const activeClass = link.active ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-gray-700 hover:bg-gray-100 border-gray-300';
                linksHtml += `<button onclick="loadData('${link.url}')" class="px-3 py-1 text-xs rounded-md border ${activeClass} transition-colors">${link.label}</button>`;
            } else {
                linksHtml += `<span class="px-3 py-1 text-xs rounded-md border border-gray-200 text-gray-400 bg-gray-50">${link.label}</span>`;
            }
        });

        container.innerHTML = `
            <div class="text-xs text-gray-500">
                Menampilkan <span class="font-semibold text-gray-800">${data.from || 0}</span> - <span class="font-semibold text-gray-800">${data.to || 0}</span> dari <span class="font-semibold text-gray-800">${data.total}</span> data
            </div>
            <div class="flex items-center gap-1">${linksHtml}</div>
        `;
    }

    // Handlers Modal
    function openModal(mode) {
        clearErrors();
        document.getElementById('siswaForm').reset();
        document.getElementById('siswa_id').value = '';
        
        if (mode === 'create') {
            document.getElementById('modalTitle').innerText = 'Tambah Data Siswa Baru';
        }
        document.getElementById('formModal').classList.remove('hidden');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }

    // Submit Store / Update
    async function submitForm(e) {
        e.preventDefault();
        clearErrors();

        const id = document.getElementById('siswa_id').value;
        const isEdit = !!id;
        const url = isEdit ? `{{ url('admin/siswa') }}/${id}` : '{{ route("admin.siswa.store") }}';
        
        const form = document.getElementById('siswaForm');
        const formData = new FormData(form);

        if (isEdit) {
            formData.append('_method', 'PUT');
        }

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            });

            const result = await response.json();

            if (response.status === 422) {
                // Validation error
                Object.keys(result.errors).forEach(key => {
                    const errorEl = document.querySelector(`.${key}_error`);
                    if (errorEl) errorEl.innerText = result.errors[key][0];
                });
            } else if (result.status === 'success') {
                closeModal('formModal');
                loadData(currentUrl);
                alert(result.message);
            } else {
                alert(result.message || 'Terjadi kesalahan sistem.');
            }
        } catch (error) {
            console.error('Submit error:', error);
        }
    }

    // Show Detail Modal
    async function showDetail(id) {
        try {
            const response = await fetch(`{{ url('admin/siswa') }}/${id}`, {
                headers: { 'Accept': 'application/json' }
            });
            const result = await response.json();

            if (result.status === 'success') {
                const d = result.data;
                document.getElementById('show_nama').innerText = d.nama || '-';
                document.getElementById('show_kelas').innerText = d.kelas ? (d.kelas.nama_kelas || d.kelas.nama) : '-';
                document.getElementById('show_nisn_nik').innerText = `NISN: ${d.nisn || '-'} | NIK: ${d.nik || '-'}`;
                document.getElementById('show_nipd').innerText = d.nipd || '-';
                document.getElementById('show_jk').innerText = d.jenis_kelamin || '-';
                document.getElementById('show_ttl').innerText = `${d.tempat_lahir || '-'}, ${d.tanggal_lahir || '-'}`;
                document.getElementById('show_agama').innerText = d.agama || '-';
                document.getElementById('show_nohp').innerText = d.no_hp || '-';
                document.getElementById('show_sekolah_asal').innerText = d.sekolah_asal || '-';
                document.getElementById('show_alamat').innerText = d.alamat || '-';
                document.getElementById('show_ayah').innerText = `${d.nama_ayah || '-'} (${d.pekerjaan_ayah || '-'})`;
                document.getElementById('show_ibu').innerText = `${d.nama_ibu || '-'} (${d.pekerjaan_ibu || '-'})`;

                document.getElementById('showModal').classList.remove('hidden');
            }
        } catch (err) {
            console.error(err);
        }
    }

    // Edit Siswa Mode
    async function editSiswa(id) {
        try {
            const response = await fetch(`{{ url('admin/siswa') }}/${id}`, {
                headers: { 'Accept': 'application/json' }
            });
            const result = await response.json();

            if (result.status === 'success') {
                openModal('edit');
                document.getElementById('modalTitle').innerText = 'Edit Data Siswa';
                const d = result.data;

                document.getElementById('siswa_id').value = d.id;
                document.getElementById('nama').value = d.nama || '';
                document.getElementById('nisn').value = d.nisn || '';
                document.getElementById('kelas_id').value = d.kelas_id || '';
                document.getElementById('nik').value = d.nik || '';
                document.getElementById('nipd').value = d.nipd || '';
                document.getElementById('jenis_kelamin').value = d.jenis_kelamin || 'laki-laki';
                document.getElementById('tempat_lahir').value = d.tempat_lahir || '';
                document.getElementById('tanggal_lahir').value = d.tanggal_lahir || '';
                document.getElementById('agama').value = d.agama || '';
                document.getElementById('no_hp').value = d.no_hp || '';
                document.getElementById('sekolah_asal').value = d.sekolah_asal || '';
                document.getElementById('alamat').value = d.alamat || '';
                document.getElementById('nama_ayah').value = d.nama_ayah || '';
                document.getElementById('pekerjaan_ayah').value = d.pekerjaan_ayah || '';
                document.getElementById('nama_ibu').value = d.nama_ibu || '';
                document.getElementById('pekerjaan_ibu').value = d.pekerjaan_ibu || '';
            }
        } catch (err) {
            console.error(err);
        }
    }

    // Delete Siswa
    async function deleteSiswa(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus data siswa ini?')) return;

        try {
            const response = await fetch(`{{ url('admin/siswa') }}/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });
            const result = await response.json();

            if (result.status === 'success') {
                loadData(currentUrl);
                alert(result.message);
            } else {
                alert(result.message || 'Gagal menghapus data.');
            }
        } catch (err) {
            console.error(err);
        }
    }

    function clearErrors() {
        document.querySelectorAll('.error-text').forEach(el => el.innerText = '');
    }

    function showLoading(state) {
        const loadingRow = document.getElementById('loadingRow');
        if (state) {
            loadingRow.classList.remove('hidden');
        } else {
            loadingRow.classList.add('hidden');
        }
    }
</script>
@endsection