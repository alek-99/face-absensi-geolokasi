@extends('layouts.guru')
@section('page_title', 'Form Absen Siswa')
@section('title', 'Form Absen Siswa')

@section('content')
<div class="min-h-screen bg-slate-50/50 pb-28 md:pb-12">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-emerald-600 to-teal-700 text-white shadow-lg shadow-emerald-900/10 rounded-b-3xl md:rounded-2xl md:mt-4 max-w-7xl mx-auto">
        <div class="px-4 py-5 sm:p-6 md:p-8">
            <!-- Nav Back -->
            <a href="{{ route('guru.absen-siswa.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-emerald-100 hover:text-white bg-white/10 hover:bg-white/20 px-3 py-1.5 rounded-full transition-all mb-4">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Jadwal
            </a>

            <!-- Subject & Class Info -->
            <div class="space-y-2">
                <h1 class="text-xl sm:text-2xl md:text-3xl font-bold tracking-tight text-white flex items-center gap-2" id="titleMapel">
                    <i class="fas fa-circle-notch fa-spin text-emerald-300"></i> Memuat data...
                </h1>
                <div class="flex flex-wrap items-center gap-2 text-xs sm:text-sm text-emerald-100/90" id="subtitleInfo">
                    <span class="inline-block bg-white/10 px-2.5 py-1 rounded-md">--</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">

        <!-- Dynamic Alert Box -->
        <div id="alertBox" class="hidden mb-6 p-4 rounded-2xl text-sm flex items-start justify-between border shadow-sm transition-all animate-fade-in">
            <div class="flex items-start gap-3">
                <i id="alertIcon" class="fas text-lg mt-0.5 shrink-0"></i>
                <div>
                    <span id="alertMessage" class="leading-relaxed font-medium"></span>
                </div>
            </div>
            <button type="button" onclick="hideAlert()" class="text-slate-400 hover:text-slate-600 p-1">
                <i class="fas fa-xmark text-base"></i>
            </button>
        </div>

        <!-- Form Absensi -->
        <form id="formAbsen" onsubmit="simpanAbsen(event)">
            
            <!-- Quick Actions & Table Header Info -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-3.5 sm:p-4 rounded-2xl border border-slate-200/80 shadow-sm mb-4">
                <div class="flex items-center justify-between sm:justify-start w-full sm:w-auto gap-2">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                        <span class="text-xs sm:text-sm font-semibold text-slate-700">Tabel Presensi Siswa</span>
                    </div>
                    <!-- Indikator Geser khusus Layar Seluler -->
                    <span class="inline-flex items-center gap-1 text-[11px] font-medium text-slate-400 sm:hidden bg-slate-100 px-2 py-0.5 rounded">
                        <i class="fas fa-arrows-left-right text-slate-400"></i> Geser Tabel
                    </span>
                </div>
                
                <button type="button" onclick="setSemuaHadir()" class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 rounded-xl transition-all border border-emerald-200/60 active:scale-95">
                    <i class="fas fa-check-double text-emerald-600"></i> Set Semua Hadir
                </button>
            </div>

            <!-- Table Card Container -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden mb-6">
                <div class="overflow-x-auto scrollbar-thin">
                    <table class="w-full text-left border-collapse min-w-[640px]">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-200/80 text-xs uppercase tracking-wider text-slate-500 font-semibold">
                                <th class="py-4 px-4 w-10 text-center bg-slate-50/80">#</th>
                                <th class="py-4 px-4 min-w-[180px] sticky left-0 bg-slate-50 z-10 border-r border-slate-100">Siswa</th>
                                <th class="py-4 px-4 text-center min-w-[280px]">Status Kehadiran</th>
                                <th class="py-4 px-4 min-w-[200px]">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody id="siswaTbody" class="divide-y divide-slate-100 text-sm">
                            <!-- Loading Skeleton Row -->
                            <tr id="loadingRow">
                                <td colspan="4" class="py-12 text-center text-slate-400">
                                    <i class="fas fa-circle-notch fa-spin text-2xl mb-2 text-emerald-600"></i>
                                    <p class="text-sm font-medium">Memuat data siswa...</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Bottom Floating Action Bar (Mobile First) -->
            <div class="fixed bottom-0 left-0 right-0 p-4 bg-white/90 backdrop-blur-md border-t border-slate-200/80 shadow-lg md:relative md:bg-transparent md:backdrop-blur-none md:border-0 md:shadow-none md:p-0 md:flex md:justify-end z-20">
                <div class="max-w-7xl mx-auto w-full md:w-auto">
                    <button type="submit" id="btnSimpan" disabled
                        class="w-full md:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed text-white font-semibold shadow-md shadow-emerald-600/20 active:scale-[0.98] transition-all text-sm sm:text-base">
                        <i class="fas fa-floppy-disk"></i> Simpan Absensi
                    </button>
                </div>
            </div>
        </form>
    </div>
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
    
    box.className = `mb-6 p-4 rounded-2xl text-sm flex items-start justify-between border shadow-sm ${
        type === 'success' ? 'bg-emerald-50 border-emerald-200 text-emerald-800' :
        type === 'warning' ? 'bg-amber-50 border-amber-200 text-amber-800' :
        'bg-rose-50 border-rose-200 text-rose-800'
    }`;
    
    icon.className = `fas mt-0.5 ${
        type === 'success' ? 'fa-circle-check text-emerald-600' :
        type === 'warning' ? 'fa-triangle-exclamation text-amber-600' :
        'fa-circle-xmark text-rose-600'
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
        document.getElementById('siswaTbody').innerHTML = `<tr><td colspan="4" class="py-8 text-center text-rose-500 font-medium"><i class="fas fa-exclamation-circle me-1"></i> ${escapeHTML(err.message)}</td></tr>`;
    }
}

function renderForm(data) {
    const info = data.jadwal;
    document.getElementById('titleMapel').innerHTML = `<i class="fas fa-book-open text-emerald-300 me-1"></i> ${escapeHTML(info.nama_mapel)}`;
    document.getElementById('subtitleInfo').innerHTML = `
        <span class="inline-flex items-center gap-1 bg-white/10 px-2.5 py-1 rounded-lg"><i class="fas fa-door-open"></i> ${escapeHTML(info.nama_kelas)}</span>
        <span class="inline-flex items-center gap-1 bg-white/10 px-2.5 py-1 rounded-lg"><i class="far fa-clock"></i> ${escapeHTML(info.jam_masuk)} - ${escapeHTML(info.jam_pulang)}</span>
        <span class="inline-flex items-center gap-1 bg-white/10 px-2.5 py-1 rounded-lg"><i class="far fa-calendar"></i> ${escapeHTML(data.tanggal)} (${escapeHTML(info.hari)})</span>
    `;

    const listSiswa = data.siswa || [];
    const tbody = document.getElementById('siswaTbody');

    if (listSiswa.length === 0) {
        tbody.innerHTML = `<tr><td colspan="4" class="py-8 text-center text-slate-400">Tidak ada siswa terdaftar di kelas ini.</td></tr>`;
        return;
    }

    const statuses = data.status_valid || ['Hadir', 'Izin', 'Sakit', 'Alfa'];
    let html = '';

    listSiswa.forEach((s, idx) => {
        const reqKeterangan = (s.status === 'Izin' || s.status === 'Sakit') ? '' : 'disabled';

        html += `
            <tr class="hover:bg-slate-50/60 transition-colors">
                <td class="py-3.5 px-4 text-center text-slate-400 font-medium text-xs">${idx + 1}</td>
                
                <!-- Sticky Column Nama Siswa -->
                <td class="py-3.5 px-4 sticky left-0 bg-white border-r border-slate-100 z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.05)]">
                    <div class="font-bold text-slate-800 text-sm leading-snug">${escapeHTML(s.nama)}</div>
                    <div class="text-[11px] text-slate-400 mt-0.5">NISN: ${escapeHTML(s.nisn || '-')}</div>
                    <input type="hidden" name="kehadiran[${idx}][siswa_id]" value="${s.siswa_id}">
                </td>

                <!-- Segmented Control Radio Buttons -->
                <td class="py-3.5 px-4 text-center">
                    <div class="inline-grid grid-cols-4 gap-1 bg-slate-100/80 p-1 rounded-xl border border-slate-200/60 w-full max-w-xs">
                        ${statuses.map(st => `
                            <label class="relative flex items-center justify-center py-2 text-xs font-semibold rounded-lg cursor-pointer transition-all has-[:checked]:bg-emerald-600 has-[:checked]:text-white text-slate-600 active:scale-95">
                                <input type="radio" 
                                    name="kehadiran[${idx}][status]" 
                                    value="${st}" 
                                    ${(s.status === st) ? 'checked' : ''}
                                    onchange="toggleKeterangan(${idx}, '${st}')"
                                    class="sr-only status-radio-${idx}">
                                ${st}
                            </label>
                        `).join('')}
                    </div>
                </td>

                <!-- Input Keterangan -->
                <td class="py-3.5 px-4">
                    <input type="text" 
                        id="ket_${idx}" 
                        name="kehadiran[${idx}][keterangan]" 
                        value="${escapeHTML(s.keterangan || '')}" 
                        placeholder="Keterangan..." 
                        ${reqKeterangan}
                        class="w-full text-xs px-3 py-2 rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 disabled:bg-slate-50 disabled:text-slate-300 transition-all">
                    <p class="text-xs text-rose-500 mt-1 hidden" id="err_ket_${idx}"></p>
                </td>
            </tr>`;
    });

    tbody.innerHTML = html;
    document.getElementById('btnSimpan').removeAttribute('disabled');
}

function toggleKeterangan(idx, status) {
    const inputKet = document.getElementById(`ket_${idx}`);
    if (!inputKet) return;

    if (status === 'Izin' || status === 'Sakit') {
        inputKet.removeAttribute('disabled');
        inputKet.focus();
    } else {
        inputKet.value = '';
        inputKet.setAttribute('disabled', 'true');
        inputKet.classList.remove('border-rose-500');
        document.getElementById(`err_ket_${idx}`)?.classList.add('hidden');
    }
}

function setSemuaHadir() {
    const allHadirRadios = document.querySelectorAll('input[type="radio"][value="Hadir"]');
    allHadirRadios.forEach(radio => {
        radio.checked = true;
        radio.dispatchEvent(new Event('change'));
    });
}

async function simpanAbsen(e) {
    e.preventDefault();
    hideAlert();

    const btn = document.getElementById('btnSimpan');
    btn.setAttribute('disabled', 'true');
    btn.innerHTML = `<i class="fas fa-spinner fa-spin"></i> Menyimpan...`;

    // Clear previous input errors
    document.querySelectorAll('.border-rose-500').forEach(el => el.classList.remove('border-rose-500'));
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
                            ketInput.classList.add('border-rose-500');
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