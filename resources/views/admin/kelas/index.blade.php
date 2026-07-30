@extends('layouts.admin') 
@section('title', 'Daftar Kelas')

@section('content')
<div class="space-y-8">
    
    <!-- Header Page -->
    <div class="flex flex-col md:flex-row md:items-center p-4 border-l-4 border-green-500 md:justify-between mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Data Kelas</h1>
            <p class="text-sm text-slate-500">Kelola daftar kelas, wali kelas, dan informasi ruangan sekolah.</p>
        </div>
        <button onclick="openModalCreate()" class="inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm transition-all focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            <i class="fa-solid fa-plus mr-2"></i> Tambah Kelas Baru
        </button>
    </div>

    <!-- Notification Toast -->
    <div id="toast" class="hidden mb-4 p-4 rounded-lg text-sm font-medium transition-all" role="alert"></div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-xs font-semibold uppercase text-slate-500 tracking-wider">
                        <th class="py-3.5 px-4">No</th>
                        <th class="py-3.5 px-4">Nama Kelas</th>
                        <th class="py-3.5 px-4">Wali Kelas</th>
                        <th class="py-3.5 px-4">Ruangan</th>
                        <th class="py-3.5 px-4 text-center">Jumlah Siswa</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-sm" id="tableBody">
                    @forelse ($kelases as $index => $kelas)
                        <tr id="row-{{ $kelas->id }}" class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3.5 px-4 font-medium text-slate-400">{{ $index + 1 }}</td>
                            <td class="py-3.5 px-4 font-semibold text-slate-800 cell-nama">{{ $kelas->nama_kelas }}</td>
                            <td class="py-3.5 px-4 text-slate-600 cell-wali">
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-user-tie text-slate-400"></i>
                                    <span>{{ $kelas->waliKelas->nama ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-slate-600 cell-ruangan">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                    <i class="fa-solid fa-door-closed mr-1.5 text-slate-400"></i>{{ $kelas->ruangan }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                    <i class="fa-solid fa-users mr-1.5 text-indigo-500"></i>{{ $kelas->siswas_count ?? 0 }} Siswa
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button onclick="showKelas({{ $kelas->id }})" title="Detail" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                    <button onclick="editKelas({{ $kelas->id }})" title="Edit" class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <button onclick="deleteKelas({{ $kelas->id }})" title="Hapus" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="emptyRow">
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                <i class="fa-solid fa-folder-open text-4xl mb-3 block text-slate-300"></i>
                                Belum ada data kelas yang terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ================= MODAL FORM (TAMBAH / EDIT) ================= -->
<div id="modalForm" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modalFormTitle" role="dialog" aria-modal="true">
    <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
        <div onclick="closeModalForm()" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity"></div>

        <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl border border-slate-100 transition-all sm:my-8 sm:w-full sm:max-w-lg">
            <div class="bg-white px-6 pt-6 pb-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2" id="modalFormTitle">
                        <i class="fa-solid fa-chalkboard text-indigo-600"></i> <span>Tambah Kelas</span>
                    </h3>
                    <button onclick="closeModalForm()" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-100 transition-colors">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <form id="formKelas" onsubmit="submitForm(event)">
                    <input type="hidden" id="kelas_id" name="kelas_id">

                    <!-- Input Nama Kelas -->
                    <div class="mb-4">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1" for="nama_kelas">Nama Kelas <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                <i class="fa-solid fa-graduation-cap"></i>
                            </span>
                            <input type="text" id="nama_kelas" name="nama_kelas" placeholder="Contoh: XII IPA 1" class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-300 rounded-lg text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                        </div>
                        <p class="text-xs text-rose-500 mt-1 hidden" id="error-nama_kelas"></p>
                    </div>

                    <!-- Select Wali Kelas -->
                    <div class="mb-4">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1" for="wali_kelas">Wali Kelas <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                <i class="fa-solid fa-user-tie"></i>
                            </span>
                            <select id="wali_kelas" name="wali_kelas" class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-300 rounded-lg text-sm text-slate-800 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all appearance-none">
                                <option value="" class="text-slate-400">-- Pilih Wali Kelas --</option>
                                @foreach ($gurus as $guru)
                                    <option value="{{ $guru->id }}" class="text-slate-800">{{ $guru->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <p class="text-xs text-rose-500 mt-1 hidden" id="error-wali_kelas"></p>
                    </div>

                    <!-- Input Ruangan -->
                    <div class="mb-4">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1" for="ruangan">Ruangan <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                <i class="fa-solid fa-door-closed"></i>
                            </span>
                            <input type="text" id="ruangan" name="ruangan" placeholder="Contoh: Lab Komputer 1 / R-201" class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-300 rounded-lg text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                        </div>
                        <p class="text-xs text-rose-500 mt-1 hidden" id="error-ruangan"></p>
                    </div>

                    <!-- Input Catatan -->
                    <div class="mb-4">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1" for="catatan">Catatan</label>
                        <textarea id="catatan" name="catatan" rows="3" placeholder="Tambahkan catatan opsional..." class="w-full p-2.5 bg-slate-50 border border-slate-300 rounded-lg text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"></textarea>
                        <p class="text-xs text-rose-500 mt-1 hidden" id="error-catatan"></p>
                    </div>

                    <!-- Modal Actions -->
                    <div class="flex items-center justify-end gap-2 border-t border-slate-100 pt-4 mt-6">
                        <button type="button" onclick="closeModalForm()" class="px-4 py-2 border border-slate-300 text-slate-700 hover:bg-slate-100 rounded-lg text-sm font-medium transition-colors">
                            Batal
                        </button>
                        <button type="submit" id="btnSubmit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-semibold transition-colors flex items-center shadow-sm">
                            <i class="fa-solid fa-floppy-disk mr-2"></i> <span>Simpan Data</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ================= MODAL DETAIL ================= -->
<div id="modalDetail" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modalDetailTitle" role="dialog" aria-modal="true">
    <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
        <div onclick="closeModalDetail()" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity"></div>

        <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl border border-slate-100 transition-all sm:my-8 sm:w-full sm:max-w-lg">
            <div class="bg-white px-6 pt-6 pb-6">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                        <i class="fa-solid fa-circle-info text-indigo-600"></i> Detail Kelas
                    </h3>
                    <button onclick="closeModalDetail()" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-100 transition-colors">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <div class="space-y-4">
                    <div class="p-4 bg-slate-50 rounded-lg border border-slate-200">
                        <span class="text-xs font-semibold uppercase text-slate-500 block mb-1">Nama Kelas</span>
                        <span id="detail-nama" class="text-lg font-bold text-slate-900"></span>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-3 border border-slate-200 rounded-lg bg-slate-50/50">
                            <span class="text-xs font-semibold uppercase text-slate-500 block mb-1">Wali Kelas</span>
                            <span id="detail-wali" class="text-sm font-medium text-slate-800"></span>
                        </div>
                        <div class="p-3 border border-slate-200 rounded-lg bg-slate-50/50">
                            <span class="text-xs font-semibold uppercase text-slate-500 block mb-1">Ruangan</span>
                            <span id="detail-ruangan" class="text-sm font-medium text-slate-800"></span>
                        </div>
                    </div>

                    <div class="p-3 border border-slate-200 rounded-lg bg-slate-50/50">
                        <span class="text-xs font-semibold uppercase text-slate-500 block mb-1">Catatan</span>
                        <p id="detail-catatan" class="text-sm text-slate-600 whitespace-pre-line"></p>
                    </div>

                    <div>
                        <span class="text-xs font-semibold uppercase text-slate-500 block mb-2">Daftar Siswa Terdaftar</span>
                        <div id="detail-siswas" class="max-h-40 overflow-y-auto border border-slate-200 rounded-lg divide-y divide-slate-100 p-2 bg-slate-50/50">
                            <!-- List siswa dinamis dari Javascript -->
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end border-t border-slate-100 pt-4 mt-6">
                    <button type="button" onclick="closeModalDetail()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-sm font-medium transition-colors">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JS Scripts -->
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    function openModalCreate() {
        resetForm();
        document.getElementById('modalFormTitle').innerHTML = '<i class="fa-solid fa-chalkboard text-indigo-600"></i> <span>Tambah Kelas</span>';
        document.getElementById('modalForm').classList.remove('hidden');
    }

    function closeModalForm() {
        document.getElementById('modalForm').classList.add('hidden');
    }

    function closeModalDetail() {
        document.getElementById('modalDetail').classList.add('hidden');
    }

    function resetForm() {
        document.getElementById('formKelas').reset();
        document.getElementById('kelas_id').value = '';
        clearErrors();
    }

    function clearErrors() {
        document.querySelectorAll('[id^="error-"]').forEach(el => {
            el.innerText = '';
            el.classList.add('hidden');
        });
    }

    function showToast(message, isSuccess = true) {
        const toast = document.getElementById('toast');
        toast.innerText = message;
        toast.className = `mb-4 p-4 rounded-lg text-sm font-medium transition-all ${
            isSuccess 
                ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' 
                : 'bg-rose-50 text-rose-800 border border-rose-200'
        }`;
        toast.classList.remove('hidden');

        setTimeout(() => {
            toast.classList.add('hidden');
        }, 4000);
    }

    // Action Handlers
    async function submitForm(e) {
        e.preventDefault();
        clearErrors();

        const btnSubmit = document.getElementById('btnSubmit');
        btnSubmit.disabled = true;
        btnSubmit.querySelector('span').innerText = 'Memproses...';

        const id = document.getElementById('kelas_id').value;
        const isEdit = !!id;
        const url = isEdit ? `/admin/kelas/${id}` : '/admin/kelas';
        
        const payload = {
            nama_kelas: document.getElementById('nama_kelas').value,
            wali_kelas: document.getElementById('wali_kelas').value,
            ruangan: document.getElementById('ruangan').value,
            catatan: document.getElementById('catatan').value,
        };

        if (isEdit) {
            payload._method = 'PUT';
        }

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            const result = await response.json();

            if (response.status === 422) {
                for (const [key, messages] of Object.entries(result.errors)) {
                    const errEl = document.getElementById(`error-${key}`);
                    if (errEl) {
                        errEl.innerText = messages[0];
                        errEl.classList.remove('hidden');
                    }
                }
            } else if (response.ok && result.success) {
                showToast(result.message, true);
                closeModalForm();
                setTimeout(() => window.location.reload(), 800);
            } else {
                showToast(result.message || 'Terjadi kesalahan sistem.', false);
            }
        } catch (err) {
            showToast('Gagal memproses permintaan.', false);
        } finally {
            btnSubmit.disabled = false;
            btnSubmit.querySelector('span').innerText = 'Simpan Data';
        }
    }

    async function editKelas(id) {
        resetForm();
        try {
            const response = await fetch(`/admin/kelas/${id}`, {
                headers: { 'Accept': 'application/json' }
            });
            const result = await response.json();

            if (result.success) {
                const data = result.data;
                document.getElementById('kelas_id').value = data.id;
                document.getElementById('nama_kelas').value = data.nama_kelas;
                document.getElementById('wali_kelas').value = data.wali_kelas_id || data.guru_id || data.wali_kelas;
                document.getElementById('ruangan').value = data.ruangan;
                document.getElementById('catatan').value = data.catatan || '';

                document.getElementById('modalFormTitle').innerHTML = '<i class="fa-solid fa-pen-to-square text-amber-600"></i> <span>Edit Kelas</span>';
                document.getElementById('modalForm').classList.remove('hidden');
            } else {
                showToast(result.message, false);
            }
        } catch (err) {
            showToast('Gagal mengambil data kelas.', false);
        }
    }

    async function showKelas(id) {
        try {
            const response = await fetch(`/admin/kelas/${id}`, {
                headers: { 'Accept': 'application/json' }
            });
            const result = await response.json();

            if (result.success) {
                const data = result.data;
                document.getElementById('detail-nama').innerText = data.nama_kelas;
                document.getElementById('detail-wali').innerText = data.wali_kelas?.nama || data.wali_kelas_nama || '-';
                document.getElementById('detail-ruangan').innerText = data.ruangan;
                document.getElementById('detail-catatan').innerText = data.catatan || 'Tidak ada catatan.';

                const listSiswa = document.getElementById('detail-siswas');
                if (data.siswas && data.siswas.length > 0) {
                    listSiswa.innerHTML = data.siswas.map((s, idx) => `
                        <div class="py-1.5 px-2 text-xs text-slate-700 flex items-center justify-between hover:bg-slate-100 rounded transition-colors">
                            <span>${idx + 1}. ${s.nama || s.nama_siswa}</span>
                            <span class="text-slate-400 font-mono">${s.nis || ''}</span>
                        </div>
                    `).join('');
                } else {
                    listSiswa.innerHTML = '<p class="text-xs text-slate-400 italic p-2 text-center">Belum ada siswa di kelas ini.</p>';
                }

                document.getElementById('modalDetail').classList.remove('hidden');
            } else {
                showToast(result.message, false);
            }
        } catch (err) {
            showToast('Gagal memuat detail kelas.', false);
        }
    }

    async function deleteKelas(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus data kelas ini?')) return;

        try {
            const response = await fetch(`/admin/kelas/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();

            if (response.ok && result.success) {
                showToast(result.message, true);
                const row = document.getElementById(`row-${id}`);
                if (row) row.remove();
            } else {
                showToast(result.message || 'Gagal menghapus data.', false);
            }
        } catch (err) {
            showToast('Terjadi kesalahan saat menghapus data.', false);
        }
    }
</script>
@endsection