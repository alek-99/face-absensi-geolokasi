@extends('layouts.guru')
@section('page_title','Form Absen Siswa')
@section('title','Form Absen Siswa')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Link Kembali -->
    <div class="mb-6">
        <a href="{{ route('guru.absen-siswa.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-indigo-600 transition-colors">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Jadwal
        </a>
    </div>

    <!-- Header Informasi Jadwal -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 mb-6">
        <h1 class="text-2xl font-bold text-slate-800 mb-2 flex items-center gap-2" id="titleMapel">
            <i class="fas fa-spinner fa-spin text-indigo-600"></i> Memuat data...
        </h1>
        <div class="text-sm text-slate-500 flex flex-wrap items-center gap-x-4 gap-y-1" id="subtitleInfo">
            <span>--</span>
        </div>
    </div>

    <!-- Alert Box -->
    <div id="alertBox" class="hidden mb-6 p-4 rounded-xl text-sm flex items-center justify-between">
        <div class="flex items-center gap-2">
            <i id="alertIcon" class="fas"></i>
            <span id="alertMessage"></span>
        </div>
        <button type="button" onclick="hideAlert()" class="opacity-70 hover:opacity-100">
            <i class="fas fa-xmark"></i>
        </button>
    </div>

    <!-- Form Absensi -->
    <form id="formAbsen" onsubmit="simpanAbsen(event)">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-xs uppercase tracking-wider text-slate-500 font-semibold">
                            <th class="py-4 px-6 w-12 text-center">#</th>
                            <th class="py-4 px-6">Siswa</th>
                            <th class="py-4 px-6 text-center w-80">Status Kehadiran</th>
                            <th class="py-4 px-6 w-72">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody id="siswaTbody" class="divide-y divide-slate-100 text-sm">
                        <tr>
                            <td colspan="4" class="py-12 text-center text-slate-400">
                                <i class="fas fa-circle-notch fa-spin text-2xl mb-2 text-indigo-600"></i>
                                <p>Memuat daftar siswa...</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tombol Simpan Floating / Bottom -->
        <div class="flex justify-end">
            <button type="submit" id="btnSimpan" disabled
                class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed text-white font-semibold shadow-sm transition-all">
                <i class="fas fa-floppy-disk"></i> Simpan Absensi
            </button>
        </div>
    </form>
</div>

<script>
const jadwalId = {{ $jadwal->id }};
const urlParams = new URLSearchParams(window.location.search);
const tanggalParam = urlParams.get('tanggal') || '{{ date("Y-m-d") }}';

document.addEventListener('DOMContentLoaded', () => {
    fetchDaftarSiswa();
});

function escapeHTML(str) {
    if (str === null || str === undefined) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function showAlert(type, msg) {
    const box = document.getElementById('alertBox');
    const icon = document.getElementById('alertIcon');
    
    box.className = `mb-6 p-4 rounded-xl text-sm flex items-center justify-between border ${
        type === 'success' ? 'bg-emerald-50 border-emerald-200 text-emerald-800' :
        type === 'warning' ? 'bg-amber-50 border-amber-200 text-amber-800' :
        'bg-red-50 border-red-200 text-red-800'
    }`;
    
    icon.className = `fas ${
        type === 'success' ? 'fa-circle-check text-emerald-500' :
        type === 'warning' ? 'fa-triangle-exclamation text-amber-500' :
        'fa-circle-xmark text-red-500'
    }`;

    document.getElementById('alertMessage').innerText = msg;
    box.classList.remove('hidden');
}

function hideAlert() {
    document.getElementById('alertBox').classList.add('hidden');
}

async function fetchDaftarSiswa() {
    try {
        const response = await fetch(`{{ route('guru.absen-siswa.api.daftar-siswa', ':id') }}`.replace(':id', jadwalId) + `?tanggal=${encodeURIComponent(tanggalParam)}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || 'Gagal memuat data.');
        }

        renderForm(result.data);
    } catch (err) {
        showAlert('danger', err.message);
        document.getElementById('siswaTbody').innerHTML = `<tr><td colspan="4" class="py-8 text-center text-red-500"><i class="fas fa-exclamation-circle me-1"></i> ${escapeHTML(err.message)}</td></tr>`;
    }
}

function renderForm(data) {
    const info = data.jadwal;
    document.getElementById('titleMapel').innerHTML = `<i class="fas fa-book-open text-indigo-600 me-2"></i> ${escapeHTML(info.nama_mapel)}`;
    document.getElementById('subtitleInfo').innerHTML = `
        <span><i class="fas fa-school text-slate-400 me-1"></i> ${escapeHTML(info.nama_kelas)}</span>
        <span>&bull;</span>
        <span><i class="far fa-clock text-slate-400 me-1"></i> ${escapeHTML(info.jam_masuk)} - ${escapeHTML(info.jam_pulang)}</span>
        <span>&bull;</span>
        <span><i class="far fa-calendar text-slate-400 me-1"></i> ${escapeHTML(data.tanggal)} (${escapeHTML(info.hari)})</span>
    `;

    const listSiswa = data.siswa || [];
    const tbody = document.getElementById('siswaTbody');

    if (listSiswa.length === 0) {
        tbody.innerHTML = `<tr><td colspan="4" class="py-8 text-center text-slate-400">Tidak ada siswa terdaftar di kelas ini.</td></tr>`;
        return;
    }

    let html = '';
    listSiswa.forEach((s, idx) => {
        const statuses = data.status_valid || ['Hadir', 'Izin', 'Sakit', 'Alfa'];
        let radiosHtml = '';

        statuses.forEach(st => {
            const checked = (s.status === st) ? 'checked' : '';
            radiosHtml += `
                <label class="inline-flex items-center gap-1.5 cursor-pointer text-xs font-semibold text-slate-700 hover:text-indigo-600">
                    <input type="radio" 
                        name="kehadiran[${idx}][status]" 
                        value="${st}" ${checked}
                        onchange="toggleKeterangan(${idx}, '${st}')"
                        class="text-indigo-600 focus:ring-indigo-500 border-slate-300">
                    ${st}
                </label>`;
        });

        const reqKeterangan = (s.status === 'Izin' || s.status === 'Sakit') ? '' : 'disabled';

        html += `
            <tr class="hover:bg-slate-50/50 transition-colors">
                <td class="py-4 px-6 text-center text-slate-400 font-medium">${idx + 1}</td>
                <td class="py-4 px-6">
                    <div class="font-bold text-slate-800">${escapeHTML(s.nama)}</div>
                    <div class="text-xs text-slate-400">NISN: ${escapeHTML(s.nisn || '-')}</div>
                    <input type="hidden" name="kehadiran[${idx}][siswa_id]" value="${s.siswa_id}">
                </td>
                <td class="py-4 px-6 text-center">
                    <div class="flex items-center justify-center gap-4 bg-slate-50 py-2 px-3 rounded-xl border border-slate-100">
                        ${radiosHtml}
                    </div>
                </td>
                <td class="py-4 px-6">
                    <input type="text" 
                        id="ket_${idx}" 
                        name="kehadiran[${idx}][keterangan]" 
                        value="${escapeHTML(s.keterangan || '')}" 
                        placeholder="Alasan Izin/Sakit" 
                        ${reqKeterangan}
                        class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 disabled:bg-slate-50 disabled:text-slate-300">
                    <p class="text-xs text-red-500 mt-1 hidden" id="err_ket_${idx}"></p>
                </td>
            </tr>`;
    });

    tbody.innerHTML = html;
    document.getElementById('btnSimpan').removeAttribute('disabled');
}

function toggleKeterangan(idx, status) {
    const inputKet = document.getElementById(`ket_${idx}`);
    if (status === 'Izin' || status === 'Sakit') {
        inputKet.removeAttribute('disabled');
        inputKet.focus();
    } else {
        inputKet.value = '';
        inputKet.setAttribute('disabled', 'true');
        inputKet.classList.remove('border-red-500');
        document.getElementById(`err_ket_${idx}`)?.classList.add('hidden');
    }
}

async function simpanAbsen(e) {
    e.preventDefault();
    hideAlert();

    const btn = document.getElementById('btnSimpan');
    btn.setAttribute('disabled', 'true');
    btn.innerHTML = `<i class="fas fa-spinner fa-spin"></i> Menyimpan...`;

    // Clear previous input errors
    document.querySelectorAll('.border-red-500').forEach(el => el.classList.remove('border-red-500'));
    document.querySelectorAll('[id^="err_ket_"]').forEach(el => el.classList.add('hidden'));

    const form = document.getElementById('formAbsen');
    const formData = new FormData(form);
    
    const payload = {
        tanggal: tanggalParam,
        kehadiran: []
    };

    const tempMap = {};
    for (let [key, val] of formData.entries()) {
        const match = key.match(/^kehadiran\[(\d+)\]\[(\w+)\]$/);
        if (match) {
            const idx = match[1];
            const field = match[2];
            if (!tempMap[idx]) tempMap[idx] = {};
            tempMap[idx][field] = val;
        }
    }
    payload.kehadiran = Object.values(tempMap);

    try {
        const response = await fetch(`{{ route('guru.absen-siswa.api.simpan', ':id') }}`.replace(':id', jadwalId), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(payload)
        });

        const result = await response.json();

        if (response.status === 422) {
            showAlert('warning', result.message || 'Harap periksa kembali isian Anda.');
            if (result.errors) {
                Object.keys(result.errors).forEach(errKey => {
                    const parts = errKey.split('.');
                    if (parts[0] === 'kehadiran' && parts[2] === 'keterangan') {
                        const idx = parts[1];
                        const ketInput = document.getElementById(`ket_${idx}`);
                        const errP = document.getElementById(`err_ket_${idx}`);
                        if (ketInput && errP) {
                            ketInput.classList.add('border-red-500');
                            errP.innerText = result.errors[errKey][0];
                            errP.classList.remove('hidden');
                        }
                    }
                });
            }
            return;
        }

        if (!response.ok) {
            throw new Error(result.message || 'Gagal menyimpan absensi.');
        }

        showAlert('success', result.message);
        setTimeout(() => {
            window.location.href = "{{ route('guru.absen-siswa.index') }}";
        }, 1200);

    } catch (err) {
        showAlert('danger', err.message);
    } finally {
        btn.removeAttribute('disabled');
        btn.innerHTML = `<i class="fas fa-floppy-disk"></i> Simpan Absensi`;
    }
}
</script>
@endsection