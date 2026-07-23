@extends('layouts.admin') 
@section('title','Daftar Kelas')
@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-100">
                <i class="fa-solid fa-school text-indigo-400 mr-2"></i> Manajemen Data Kelas
            </h1>
            <p class="text-sm text-slate-400">Kelola data kelas, ruangan, dan wali kelas.</p>
        </div>
        <button onclick="openModal('modalTambah')" class="inline-flex items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-lg shadow-sm transition">
            <i class="fa-solid fa-plus mr-2"></i> Tambah Kelas
        </button>
    </div>

    <!-- Alert Container JS -->
    <div id="alertContainer" class="mb-4"></div>

    <!-- Tabel Data Kelas -->
    <div class="bg-slate-800 rounded-xl shadow-sm border border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="bg-slate-900/50 text-slate-400 uppercase text-xs border-b border-slate-700">
                    <tr>
                        <th class="px-6 py-3.5">No</th>
                        <th class="px-6 py-3.5">Nama Kelas</th>
                        <th class="px-6 py-3.5">Wali Kelas</th>
                        <th class="px-6 py-3.5">Ruangan</th>
                        <th class="px-6 py-3.5">Jumlah Siswa</th>
                        <th class="px-6 py-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody" class="divide-y divide-slate-700/50">
                    @forelse($kelases as $index => $kelas)
                    <tr id="row-{{ $kelas->id }}" class="hover:bg-slate-700/40 transition">
                        <td class="px-6 py-4 font-medium text-slate-300">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 font-semibold text-slate-100">{{ $kelas->nama_kelas }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center text-slate-300">
                                <i class="fa-solid fa-user-tie text-slate-400 mr-2"></i>
                                {{ $kelas->waliKelas->nama ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 bg-slate-900 border border-slate-700 text-slate-300 text-xs font-medium rounded-md">
                                <i class="fa-solid fa-door-open mr-1 text-slate-400"></i>{{ $kelas->ruangan }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 bg-indigo-950/80 border border-indigo-800 text-indigo-300 text-xs font-semibold rounded-md">
                                <i class="fa-solid fa-users mr-1"></i>{{ $kelas->siswas_count }} Siswa
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center space-x-1">
                            <button onclick="showDetail({{ $kelas->id }})" title="Detail" class="p-1.5 text-sky-400 hover:bg-sky-950/50 rounded-lg transition">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                            <button onclick="editKelas({{ $kelas->id }})" title="Edit" class="p-1.5 text-amber-400 hover:bg-amber-950/50 rounded-lg transition">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button onclick="deleteKelas({{ $kelas->id }})" title="Hapus" class="p-1.5 text-rose-400 hover:bg-rose-950/50 rounded-lg transition">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-slate-400">
                            <i class="fa-solid fa-folder-open text-3xl mb-2 block"></i>
                            Belum ada data kelas terdaftar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ==================== MODAL TAMBAH ==================== -->
<div id="modalTambah" class="fixed inset-0 z-50 hidden bg-slate-950/75 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-slate-800 rounded-xl shadow-2xl border border-slate-700 w-full max-w-md overflow-hidden transform transition-all">
        <div class="px-6 py-4 bg-indigo-600 text-white flex justify-between items-center">
            <h3 class="font-bold text-lg">
                <i class="fa-solid fa-square-plus mr-2"></i> Tambah Kelas Baru
            </h3>
            <button onclick="closeModal('modalTambah')" class="text-slate-200 hover:text-white text-2xl font-bold leading-none">&times;</button>
        </div>
        <form id="formTambah">
            @csrf
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">
                        <i class="fa-solid fa-chalkboard text-slate-400 mr-1"></i> Nama Kelas <span class="text-rose-400">*</span>
                    </label>
                    <input type="text" name="nama_kelas" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 text-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-sm" placeholder="Contoh: X IPA 1" required>
                    <p class="text-xs text-rose-400 mt-1 hidden" id="err-nama_kelas"></p>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">
                        <i class="fa-solid fa-user-tie text-slate-400 mr-1"></i> Wali Kelas <span class="text-rose-400">*</span>
                    </label>
                    <select name="wali_kelas" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 text-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-sm" required>
                        <option value="">-- Pilih Wali Kelas --</option>
                        @foreach($gurus as $guru)
                            <option value="{{ $guru->id }}">{{ $guru->nama }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-rose-400 mt-1 hidden" id="err-wali_kelas"></p>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">
                        <i class="fa-solid fa-door-closed text-slate-400 mr-1"></i> Ruangan <span class="text-rose-400">*</span>
                    </label>
                    <input type="text" name="ruangan" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 text-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-sm" placeholder="Contoh: R-101" required>
                    <p class="text-xs text-rose-400 mt-1 hidden" id="err-ruangan"></p>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">
                        <i class="fa-solid fa-note-sticky text-slate-400 mr-1"></i> Catatan
                    </label>
                    <textarea name="catatan" rows="3" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 text-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-sm" placeholder="Catatan tambahan (opsional)..."></textarea>
                </div>
            </div>
            <div class="flex justify-end space-x-3 px-6 py-4 bg-slate-900/50 border-t border-slate-700">
                <button type="button" onclick="closeModal('modalTambah')" class="px-4 py-2 border border-slate-600 rounded-lg text-slate-300 hover:bg-slate-700 text-sm font-medium transition">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-lg transition">
                    <i class="fa-solid fa-floppy-disk mr-1"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ==================== MODAL EDIT ==================== -->
<div id="modalEdit" class="fixed inset-0 z-50 hidden bg-slate-950/75 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-slate-800 rounded-xl shadow-2xl border border-slate-700 w-full max-w-md overflow-hidden transform transition-all">
        <div class="px-6 py-4 bg-amber-600 text-white flex justify-between items-center">
            <h3 class="font-bold text-lg">
                <i class="fa-solid fa-pen-to-square mr-2"></i> Edit Data Kelas
            </h3>
            <button onclick="closeModal('modalEdit')" class="text-slate-200 hover:text-white text-2xl font-bold leading-none">&times;</button>
        </div>
        <form id="formEdit">
            @csrf
            @method('PUT')
            <input type="hidden" id="edit_id" name="id">
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">
                        <i class="fa-solid fa-chalkboard text-slate-400 mr-1"></i> Nama Kelas <span class="text-rose-400">*</span>
                    </label>
                    <input type="text" id="edit_nama_kelas" name="nama_kelas" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 text-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-sm" required>
                    <p class="text-xs text-rose-400 mt-1 hidden" id="err-edit_nama_kelas"></p>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">
                        <i class="fa-solid fa-user-tie text-slate-400 mr-1"></i> Wali Kelas <span class="text-rose-400">*</span>
                    </label>
                    <select id="edit_wali_kelas" name="wali_kelas" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 text-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-sm" required>
                        <option value="">-- Pilih Wali Kelas --</option>
                        @foreach($gurus as $guru)
                            <option value="{{ $guru->id }}">{{ $guru->nama }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-rose-400 mt-1 hidden" id="err-edit_wali_kelas"></p>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">
                        <i class="fa-solid fa-door-closed text-slate-400 mr-1"></i> Ruangan <span class="text-rose-400">*</span>
                    </label>
                    <input type="text" id="edit_ruangan" name="ruangan" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 text-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-sm" required>
                    <p class="text-xs text-rose-400 mt-1 hidden" id="err-edit_ruangan"></p>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">
                        <i class="fa-solid fa-note-sticky text-slate-400 mr-1"></i> Catatan
                    </label>
                    <textarea id="edit_catatan" name="catatan" rows="3" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 text-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-sm"></textarea>
                </div>
            </div>
            <div class="flex justify-end space-x-3 px-6 py-4 bg-slate-900/50 border-t border-slate-700">
                <button type="button" onclick="closeModal('modalEdit')" class="px-4 py-2 border border-slate-600 rounded-lg text-slate-300 hover:bg-slate-700 text-sm font-medium transition">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-amber-600 hover:bg-amber-500 text-white text-sm font-medium rounded-lg transition">
                    <i class="fa-solid fa-rotate mr-1"></i> Perbarui
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ==================== MODAL SHOW ==================== -->
<div id="modalShow" class="fixed inset-0 z-50 hidden bg-slate-950/75 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-slate-800 rounded-xl shadow-2xl border border-slate-700 w-full max-w-md overflow-hidden transform transition-all">
        <div class="px-6 py-4 bg-sky-600 text-white flex justify-between items-center">
            <h3 class="font-bold text-lg">
                <i class="fa-solid fa-circle-info mr-2"></i> Detail Informasi Kelas
            </h3>
            <button onclick="closeModal('modalShow')" class="text-slate-200 hover:text-white text-2xl font-bold leading-none">&times;</button>
        </div>
        <div class="p-6 space-y-3">
            <div class="flex justify-between border-b border-slate-700 pb-2">
                <span class="text-xs text-slate-400 uppercase font-semibold"><i class="fa-solid fa-chalkboard mr-1.5"></i> Nama Kelas</span>
                <span id="show_nama_kelas" class="text-sm font-semibold text-slate-100">-</span>
            </div>
            <div class="flex justify-between border-b border-slate-700 pb-2">
                <span class="text-xs text-slate-400 uppercase font-semibold"><i class="fa-solid fa-user-tie mr-1.5"></i> Wali Kelas</span>
                <span id="show_wali_kelas" class="text-sm font-semibold text-slate-100">-</span>
            </div>
            <div class="flex justify-between border-b border-slate-700 pb-2">
                <span class="text-xs text-slate-400 uppercase font-semibold"><i class="fa-solid fa-door-open mr-1.5"></i> Ruangan</span>
                <span id="show_ruangan" class="text-sm font-semibold text-slate-100">-</span>
            </div>
            <div class="flex justify-between border-b border-slate-700 pb-2">
                <span class="text-xs text-slate-400 uppercase font-semibold"><i class="fa-solid fa-users mr-1.5"></i> Jumlah Siswa</span>
                <span id="show_jumlah_siswa" class="text-sm font-semibold text-indigo-400">-</span>
            </div>
            <div class="pt-1">
                <span class="text-xs text-slate-400 uppercase font-semibold block mb-1"><i class="fa-solid fa-note-sticky mr-1.5"></i> Catatan</span>
                <p id="show_catatan" class="text-sm text-slate-300 bg-slate-900 p-3 rounded-lg border border-slate-700/60">-</p>
            </div>
        </div>
        <div class="flex justify-end px-6 py-3 bg-slate-900/50 border-t border-slate-700">
            <button type="button" onclick="closeModal('modalShow')" class="px-4 py-2 bg-slate-700 text-slate-200 rounded-lg text-sm font-medium hover:bg-slate-600 transition">
                Tutup
            </button>
        </div>
    </div>
</div>


<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Helper Modal Toggle
    function openModal(id) {
        $('#' + id).removeClass('hidden').addClass('flex');
    }

    function closeModal(id) {
        $('#' + id).addClass('hidden').removeClass('flex');
        clearErrors();
    }

    function showAlert(type, message) {
        let bgColor = type === 'success' ? 'bg-emerald-950/80 text-emerald-300 border-emerald-800' : 'bg-rose-950/80 text-rose-300 border-rose-800';
        let icon = type === 'success' ? 'fa-circle-check text-emerald-400' : 'fa-circle-exclamation text-rose-400';
        
        let html = `<div class="flex items-center justify-between p-4 mb-4 text-sm rounded-lg border ${bgColor}" role="alert">
            <div class="flex items-center">
                <i class="fa-solid ${icon} text-lg mr-2"></i>
                <div>${message}</div>
            </div>
            <button type="button" onclick="$(this).parent().remove()" class="text-slate-400 hover:text-slate-200">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>`;
        $('#alertContainer').html(html);
    }

    function clearErrors() {
        $('input, select, textarea').removeClass('border-rose-500');
        $('[id^="err-"]').addClass('hidden').text('');
    }

    // 1. TAMBAH DATA (STORE)
    $('#formTambah').on('submit', function(e) {
        e.preventDefault();
        clearErrors();

        $.ajax({
            url: "{{ route('admin.kelas.store') }}",
            type: "POST",
            data: $(this).serialize(),
            success: function(res) {
                if(res.success) {
                    closeModal('modalTambah');
                    $('#formTambah')[0].reset();
                    showAlert('success', res.message);
                    location.reload();
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, val) {
                        $('[name="'+key+'"]').addClass('border-rose-500');
                        $('#err-'+key).removeClass('hidden').text(val[0]);
                    });
                }
            }
        });
    });

    // 2. SHOW DETAIL
    function showDetail(id) {
        $.get("{{ url('admin/kelas') }}/" + id, function(res) {
            if(res.success) {
                let data = res.data;
                $('#show_nama_kelas').text(data.nama_kelas);
                $('#show_wali_kelas').text(data.wali_kelas ? data.wali_kelas.nama : '-');
                $('#show_ruangan').text(data.ruangan);
                $('#show_jumlah_siswa').text((data.siswas ? data.siswas.length : 0) + ' Siswa');
                $('#show_catatan').text(data.catatan || '-');
                openModal('modalShow');
            }
        });
    }

    // 3. EDIT (FETCH DATA)
    function editKelas(id) {
        clearErrors();
        $.get("{{ url('admin/kelas') }}/" + id, function(res) {
            if(res.success) {
                let data = res.data;
                $('#edit_id').val(data.id);
                $('#edit_nama_kelas').val(data.nama_kelas);
                $('#edit_wali_kelas').val(data.wali_kelas);
                $('#edit_ruangan').val(data.ruangan);
                $('#edit_catatan').val(data.catatan);
                openModal('modalEdit');
            }
        });
    }

    // 4. UPDATE DATA
    $('#formEdit').on('submit', function(e) {
        e.preventDefault();
        clearErrors();
        let id = $('#edit_id').val();

        $.ajax({
            url: "{{ url('admin/kelas') }}/" + id,
            type: "PUT",
            data: $(this).serialize(),
            success: function(res) {
                if(res.success) {
                    closeModal('modalEdit');
                    showAlert('success', res.message);
                    location.reload();
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, val) {
                        $('#edit_'+key).addClass('border-rose-500');
                        $('#err-edit_'+key).removeClass('hidden').text(val[0]);
                    });
                }
            }
        });
    });

    // 5. HAPUS DATA
    function deleteKelas(id) {
        if(confirm('Apakah Anda yakin ingin menghapus data kelas ini?')) {
            $.ajax({
                url: "{{ url('admin/kelas') }}/" + id,
                type: "DELETE",
                success: function(res) {
                    if(res.success) {
                        $('#row-' + id).remove();
                        showAlert('success', res.message);
                    }
                },
                error: function(xhr) {
                    let res = xhr.responseJSON;
                    showAlert('danger', res.message || 'Terjadi kesalahan sistem.');
                }
            });
        }
    }
</script>
@endsection