@extends('layouts.guru')
@section('page_title', 'Form Absen Siswa')
@section('title', 'Form Absen Siswa')

@section('content')
<div class="min-h-screen bg-slate-50/60 pb-32 md:pb-12 text-slate-800">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Header Section -->
    <div class="bg-emerald-600 text-white shadow-md shadow-emerald-900/10 rounded-b-3xl md:rounded-2xl md:mt-4 max-w-7xl mx-auto px-4 py-6 sm:p-8">
        <div class="flex flex-col gap-4">
            <!-- Nav Back -->
            <div>
                <a href="{{ route('guru.absen-siswa.index') }}" class="inline-flex items-center gap-2 text-xs font-medium text-emerald-100 hover:text-white bg-white/10 hover:bg-white/20 px-3 py-1.5 rounded-lg transition-all">
                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar Jadwal
                </a>
            </div>

            <!-- Subject & Class Info -->
            <div class="space-y-2">
                <h1 class="text-xl sm:text-2xl md:text-3xl font-bold tracking-tight text-white flex items-center gap-2" id="titleMapel">
                    <!-- Skeleton Title Header -->
                    <span class="inline-block h-7 sm:h-9 w-64 bg-white/20 rounded-lg animate-pulse"></span>
                </h1>
                <div class="flex flex-wrap items-center gap-2 text-xs sm:text-sm text-emerald-50" id="subtitleInfo">
                    <!-- Skeleton Subtitle Info -->
                    <span class="inline-block h-6 w-24 bg-white/15 rounded-md animate-pulse"></span>
                    <span class="inline-block h-6 w-32 bg-white/15 rounded-md animate-pulse"></span>
                    <span class="inline-block h-6 w-40 bg-white/15 rounded-md animate-pulse"></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Container -->
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 mt-6">

        <!-- Alert Notification -->
        <div id="alertBox" class="hidden mb-6 p-4 rounded-2xl text-sm flex items-start justify-between border shadow-sm transition-all">
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

        <form id="formAbsen" onsubmit="simpanAbsen(event)">
            
            <!-- Quick Actions Toolbar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm mb-4">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                    <span class="text-sm font-semibold text-slate-700">Daftar Kehadiran Siswa</span>
                </div>
                
                <button type="button" onclick="setSemuaHadir()" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2 text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 rounded-xl transition-all border border-emerald-200/80 active:scale-95">
                    <i class="fas fa-check-double text-emerald-600"></i> Set Semua Hadir
                </button>
            </div>

            <!-- Main Content Container: Mobile Cards vs Desktop Table -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden mb-6">
                
                <!-- Desktop & Tablet Table (>= md screen) -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200/80 text-xs uppercase tracking-wider text-slate-500 font-semibold">
                                <th class="py-4 px-4 w-12 text-center">#</th>
                                <th class="py-4 px-4 w-1/3 min-w-[200px]">Nama Siswa</th>
                                <th class="py-4 px-4 text-center min-w-[320px]">Status Kehadiran</th>
                                <th class="py-4 px-4">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody id="siswaTbodyDesktop" class="divide-y divide-slate-100 text-sm">
                            <!-- Desktop Skeleton Loader (5 Rows) -->
                            @for ($i = 0; $i < 5; $i++)
                            <tr class="animate-pulse">
                                <td class="py-4 px-4 text-center"><div class="h-4 w-4 bg-slate-200 rounded mx-auto"></div></td>
                                <td class="py-4 px-4">
                                    <div class="h-4 w-40 bg-slate-200 rounded mb-1.5"></div>
                                    <div class="h-3 w-24 bg-slate-100 rounded"></div>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <div class="inline-flex gap-1.5 bg-slate-50 p-1 rounded-xl border border-slate-100">
                                        <div class="h-7 w-14 bg-slate-200 rounded-lg"></div>
                                        <div class="h-7 w-12 bg-slate-200 rounded-lg"></div>
                                        <div class="h-7 w-12 bg-slate-200 rounded-lg"></div>
                                        <div class="h-7 w-12 bg-slate-200 rounded-lg"></div>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="h-8 w-full bg-slate-100 rounded-xl"></div>
                                </td>
                            </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>

                <!-- Mobile View Cards (< md screen) -->
                <div id="siswaCardsMobile" class="block md:hidden divide-y divide-slate-100">
                    <!-- Mobile Skeleton Loader (3 Cards) -->
                    @for ($i = 0; $i < 3; $i++)
                    <div class="p-4 space-y-3 animate-pulse">
                        <div class="flex justify-between items-start">
                            <div class="space-y-1.5 w-full">
                                <div class="h-3 w-6 bg-slate-200 rounded"></div>
                                <div class="h-4 w-48 bg-slate-200 rounded"></div>
                                <div class="h-3 w-28 bg-slate-100 rounded"></div>
                            </div>
                        </div>
                        <div class="grid grid-cols-4 gap-1.5 bg-slate-50 p-1 rounded-xl border border-slate-100">
                            <div class="h-8 bg-slate-200 rounded-lg"></div>
                            <div class="h-8 bg-slate-200 rounded-lg"></div>
                            <div class="h-8 bg-slate-200 rounded-lg"></div>
                            <div class="h-8 bg-slate-200 rounded-lg"></div>
                        </div>
                        <div class="h-8 w-full bg-slate-100 rounded-xl"></div>
                    </div>
                    @endfor
                </div>

            </div>

            <!-- Floating Bottom Action Bar for Mobile / Fixed Bar for Desktop -->
            <div class="fixed bottom-0 left-0 right-0 p-4 bg-white/90 backdrop-blur-md border-t border-slate-200 shadow-lg md:relative md:bg-transparent md:backdrop-blur-none md:border-0 md:shadow-none md:p-0 md:flex md:justify-end z-30">
                <div class="max-w-7xl mx-auto w-full md:w-auto">
                    <button type="submit" id="btnSimpan" disabled
                        class="w-full md:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed text-white font-semibold shadow-lg shadow-emerald-600/20 active:scale-[0.98] transition-all text-sm sm:text-base">
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
        const errHtml = `<div class="p-6 text-center text-rose-500 font-medium"><i class="fas fa-exclamation-circle me-1"></i> ${escapeHTML(err.message)}</div>`;
        document.getElementById('siswaTbodyDesktop').innerHTML = `<tr><td colspan="4">${errHtml}</td></tr>`;
        document.getElementById('siswaCardsMobile').innerHTML = errHtml;
    }
}

function renderForm(data) {
    const info = data.jadwal;
    document.getElementById('titleMapel').innerHTML = `<i class="fas fa-book-open text-emerald-300 me-1"></i> ${escapeHTML(info.nama_mapel)}`;
    document.getElementById('subtitleInfo').innerHTML = `
        <span class="inline-flex items-center gap-1.5 bg-white/10 px-2.5 py-1 rounded-lg"><i class="fas fa-door-open text-emerald-300"></i> ${escapeHTML(info.nama_kelas)}</span>
        <span class="inline-flex items-center gap-1.5 bg-white/10 px-2.5 py-1 rounded-lg"><i class="far fa-clock text-emerald-300"></i> ${escapeHTML(info.jam_masuk)} - ${escapeHTML(info.jam_pulang)}</span>
        <span class="inline-flex items-center gap-1.5 bg-white/10 px-2.5 py-1 rounded-lg"><i class="far fa-calendar text-emerald-300"></i> ${escapeHTML(data.tanggal)} (${escapeHTML(info.hari)})</span>
    `;

    const listSiswa = data.siswa || [];
    const desktopTbody = document.getElementById('siswaTbodyDesktop');
    const mobileContainer = document.getElementById('siswaCardsMobile');

    if (listSiswa.length === 0) {
        const emptyHtml = `<div class="p-8 text-center text-slate-400 font-medium">Tidak ada siswa terdaftar di kelas ini.</div>`;
        desktopTbody.innerHTML = `<tr><td colspan="4">${emptyHtml}</td></tr>`;
        mobileContainer.innerHTML = emptyHtml;
        return;
    }

    const statuses = data.status_valid || ['Hadir', 'Izin', 'Sakit', 'Alfa'];
    
    let htmlDesktop = '';
    let htmlMobile = '';

    const statusColors = {
        'Hadir': 'peer-checked:bg-emerald-600 peer-checked:text-white hover:bg-emerald-50 text-emerald-700 border-emerald-200',
        'Izin':  'peer-checked:bg-amber-500 peer-checked:text-white hover:bg-amber-50 text-amber-700 border-amber-200',
        'Sakit': 'peer-checked:bg-sky-500 peer-checked:text-white hover:bg-sky-50 text-sky-700 border-sky-200',
        'Alfa':  'peer-checked:bg-rose-600 peer-checked:text-white hover:bg-rose-50 text-rose-700 border-rose-200'
    };

    listSiswa.forEach((s, idx) => {
        const isReqKeterangan = (s.status === 'Izin' || s.status === 'Sakit');
        const reqKeteranganAttr = isReqKeterangan ? '' : 'disabled';

        // 1. DESKTOP ROW HTML
        htmlDesktop += `
            <tr class="hover:bg-slate-50/80 transition-colors">
                <td class="py-3.5 px-4 text-center text-slate-400 font-semibold text-xs">${idx + 1}</td>
                
                <td class="py-3.5 px-4">
                    <div class="font-bold text-slate-800 text-sm">${escapeHTML(s.nama)}</div>
                    <div class="text-[11px] text-slate-400 mt-0.5">NISN: ${escapeHTML(s.nisn || '-')}</div>
                    <input type="hidden" name="kehadiran[${idx}][siswa_id]" value="${s.siswa_id}">
                </td>

                <td class="py-3.5 px-4 text-center">
                    <div class="inline-flex gap-1.5 bg-slate-100 p-1 rounded-xl border border-slate-200/80">
                        ${statuses.map(st => `
                            <label class="relative cursor-pointer">
                                <input type="radio" 
                                    name="kehadiran[${idx}][status]" 
                                    value="${st}" 
                                    ${(s.status === st) ? 'checked' : ''}
                                    onchange="toggleKeterangan(${idx}, '${st}')"
                                    class="peer sr-only">
                                <span class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all inline-block border border-transparent ${statusColors[st] || 'peer-checked:bg-slate-800 peer-checked:text-white'}">
                                    ${st}
                                </span>
                            </label>
                        `).join('')}
                    </div>
                </td>

                <td class="py-3.5 px-4">
                    <input type="text" 
                        id="ket_${idx}" 
                        name="kehadiran[${idx}][keterangan]" 
                        value="${escapeHTML(s.keterangan || '')}" 
                        placeholder="Catatan/Alasan..." 
                        ${reqKeteranganAttr}
                        class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 disabled:bg-slate-50 disabled:text-slate-300 disabled:border-slate-100 transition-all">
                    <p class="text-xs text-rose-500 mt-1 hidden" id="err_ket_${idx}"></p>
                </td>
            </tr>`;

        // 2. MOBILE CARD HTML
        htmlMobile += `
            <div class="p-4 space-y-3">
                <div class="flex justify-between items-start">
                    <div>
                        <span class="text-xs font-semibold text-slate-400">#${idx + 1}</span>
                        <h4 class="font-bold text-slate-800 text-sm leading-snug">${escapeHTML(s.nama)}</h4>
                        <p class="text-[11px] text-slate-400">NISN: ${escapeHTML(s.nisn || '-')}</p>
                    </div>
                </div>

                <!-- Status Selector -->
                <div class="grid grid-cols-4 gap-1.5 bg-slate-100/80 p-1 rounded-xl border border-slate-200/60">
                    ${statuses.map(st => `
                        <label class="relative cursor-pointer text-center">
                            <input type="radio" 
                                name="kehadiran[${idx}][status_mob_sync]" 
                                data-idx="${idx}"
                                value="${st}" 
                                ${(s.status === st) ? 'checked' : ''}
                                onchange="syncMobileToDesktop(${idx}, '${st}')"
                                class="peer sr-only">
                            <span class="block w-full py-2 rounded-lg text-xs font-semibold transition-all border border-transparent ${statusColors[st] || 'peer-checked:bg-slate-800 peer-checked:text-white'}">
                                ${st}
                            </span>
                        </label>
                    `).join('')}
                </div>

                <!-- Mobile Input Keterangan -->
                <div>
                    <input type="text" 
                        id="ket_mob_${idx}" 
                        value="${escapeHTML(s.keterangan || '')}" 
                        placeholder="Keterangan..." 
                        ${reqKeteranganAttr}
                        oninput="syncMobileInputToDesktop(${idx}, this.value)"
                        class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 disabled:bg-slate-50 disabled:text-slate-300 disabled:border-slate-100 transition-all">
                    <p class="text-xs text-rose-500 mt-1 hidden" id="err_ket_mob_${idx}"></p>
                </div>
            </div>
        `;
    });

    desktopTbody.innerHTML = htmlDesktop;
    mobileContainer.innerHTML = htmlMobile;
    document.getElementById('btnSimpan').removeAttribute('disabled');
}

function syncMobileToDesktop(idx, status) {
    const desktopRadios = document.querySelectorAll(`input[name="kehadiran[${idx}][status]"]`);
    desktopRadios.forEach(radio => {
        if (radio.value === status) {
            radio.checked = true;
        }
    });
    toggleKeterangan(idx, status);
}

function syncMobileInputToDesktop(idx, val) {
    const desktopInput = document.getElementById(`ket_${idx}`);
    if (desktopInput) {
        desktopInput.value = val;
    }
}

function toggleKeterangan(idx, status) {
    const inputKet = document.getElementById(`ket_${idx}`);
    const inputKetMob = document.getElementById(`ket_mob_${idx}`);

    const isRequired = (status === 'Izin' || status === 'Sakit');

    [inputKet, inputKetMob].forEach(input => {
        if (!input) return;
        if (isRequired) {
            input.removeAttribute('disabled');
        } else {
            input.value = '';
            input.setAttribute('disabled', 'true');
            input.classList.remove('border-rose-500');
        }
    });

    if (isRequired && inputKetMob && window.innerWidth < 768) {
        inputKetMob.focus();
    } else if (isRequired && inputKet) {
        inputKet.focus();
    }

    document.getElementById(`err_ket_${idx}`)?.classList.add('hidden');
    document.getElementById(`err_ket_mob_${idx}`)?.classList.add('hidden');
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
                        
                        ['ket_', 'ket_mob_'].forEach(prefix => {
                            const ketInput = document.getElementById(`${prefix}${idx}`);
                            const errP = document.getElementById(`err_${prefix}${idx}`);
                            if (ketInput && errP) {
                                ketInput.classList.add('border-rose-500');
                                errP.innerText = result.errors[errKey][0];
                                errP.classList.remove('hidden');
                            }
                        });
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