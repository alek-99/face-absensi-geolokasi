@extends('layouts.guru')
@section('page_title', 'Form Absen Siswa')
@section('title', 'Form Absen Siswa')

@section('content')
<!-- pb-44 di mobile agar kartu siswa terbawah tidak tertutup floating bar & bottom nav -->
<div class="min-h-screen bg-slate-50/60 pb-44 md:pb-16 text-slate-800 relative">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-emerald-600 to-teal-700 text-white shadow-lg shadow-emerald-900/10 rounded-b-3xl md:rounded-2xl md:mt-4 max-w-7xl mx-auto px-4 py-6 sm:p-8">
        <div class="flex flex-col gap-4">
            <!-- Nav Back -->
            <div>
                <a href="{{ route('guru.absen-siswa.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-emerald-100 hover:text-white bg-white/10 hover:bg-white/20 backdrop-blur-md px-3.5 py-2 rounded-xl transition-all border border-white/10 active:scale-95">
                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar Jadwal
                </a>
            </div>

            <!-- Subject & Class Info -->
            <div class="space-y-2">
                <h1 class="text-lg xs:text-xl sm:text-2xl md:text-3xl font-extrabold tracking-tight text-white flex items-center gap-2 break-words" id="titleMapel">
                    <span class="inline-block h-7 sm:h-9 w-full max-w-xs bg-white/20 rounded-xl animate-pulse"></span>
                </h1>
                <div class="flex flex-wrap items-center gap-2 text-xs sm:text-sm text-emerald-50" id="subtitleInfo">
                    <span class="inline-block h-6 w-24 bg-white/15 rounded-lg animate-pulse"></span>
                    <span class="inline-block h-6 w-32 bg-white/15 rounded-lg animate-pulse"></span>
                    <span class="inline-block h-6 w-40 bg-white/15 rounded-lg animate-pulse"></span>
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
            <button type="button" onclick="hideAlert()" class="text-slate-400 hover:text-slate-600 p-1 shrink-0">
                <i class="fas fa-xmark text-base"></i>
            </button>
        </div>

        <form id="formAbsen" onsubmit="simpanAbsen(event)">

            <!-- Quick Actions Toolbar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm mb-4">
                <div class="flex items-center gap-2.5">
                    <span class="relative flex h-3 w-3">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                    </span>
                    <span class="text-sm font-bold text-slate-700">Daftar Kehadiran Siswa</span>
                    <span id="jumlahSiswa" class="text-xs font-semibold text-slate-400"></span>
                </div>

                <button type="button" onclick="setSemuaHadir()" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100/80 rounded-xl transition-all border border-emerald-200/80 active:scale-95 cursor-pointer shadow-sm">
                    <i class="fas fa-check-double text-emerald-600"></i> Set Semua Hadir
                </button>
            </div>

            <!-- Main Content: single responsive list (mobile-first) -->
            <!-- Mobile: stacked cards. md+: reflows into a table-like row via CSS grid on the SAME markup (no duplicate DOM, no JS sync needed) -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden mb-6">

                <!-- Column header row: hidden on mobile, shown from md up -->
                <div class="hidden md:grid md:grid-cols-[2.5rem_minmax(180px,1fr)_auto_minmax(180px,1fr)] md:gap-4 md:items-center bg-slate-50/80 border-b border-slate-200/80 text-xs uppercase tracking-wider text-slate-500 font-bold py-4 px-4">
                    <div class="text-center">#</div>
                    <div>Nama Siswa</div>
                    <div class="text-center">Status Kehadiran</div>
                    <div>Keterangan</div>
                </div>

                <div id="siswaList" class="divide-y divide-slate-100 text-sm">
                    @for ($i = 0; $i < 4; $i++)
                    <div class="p-4 md:py-4 md:px-4 md:grid md:grid-cols-[2.5rem_minmax(180px,1fr)_auto_minmax(180px,1fr)] md:gap-4 md:items-center animate-pulse">
                        <div class="hidden md:block h-4 w-4 bg-slate-200 rounded mx-auto"></div>
                        <div class="mb-3 md:mb-0">
                            <div class="h-4 w-40 bg-slate-200 rounded mb-1.5"></div>
                            <div class="h-3 w-24 bg-slate-100 rounded"></div>
                        </div>
                        <div class="mb-3 md:mb-0">
                            <div class="grid grid-cols-4 md:inline-flex gap-1.5 bg-slate-50 p-1 rounded-xl border border-slate-100 md:w-auto">
                                <div class="h-8 md:h-7 md:w-14 bg-slate-200 rounded-lg"></div>
                                <div class="h-8 md:h-7 md:w-12 bg-slate-200 rounded-lg"></div>
                                <div class="h-8 md:h-7 md:w-12 bg-slate-200 rounded-lg"></div>
                                <div class="h-8 md:h-7 md:w-12 bg-slate-200 rounded-lg"></div>
                            </div>
                        </div>
                        <div class="h-8 w-full bg-slate-100 rounded-xl"></div>
                    </div>
                    @endfor
                </div>
            </div>

            <!-- ======================================================== -->
            <!-- REUSABLE FLOATING ACTION DOCK                              -->
            <!-- Mobile: bottom-20, di atas Bottom Nav. Desktop: menyatu di kanan bawah container -->
            <!-- ======================================================== -->
            <div class="fixed bottom-20 inset-x-3 md:relative md:bottom-0 md:inset-x-0 z-[99999] pointer-events-none">
                <div class="max-w-7xl mx-auto flex justify-center md:justify-end">
                    <div class="pointer-events-auto w-full max-w-md md:w-auto bg-slate-900/90 text-white backdrop-blur-xl p-2.5 sm:p-3 rounded-2xl border border-white/20 shadow-[0_12px_40px_rgba(0,0,0,0.35)] transition-all transform hover:scale-[1.01]">
                        <button type="submit" id="btnSimpan" disabled
                            class="w-full md:w-auto inline-flex items-center justify-center gap-2.5 px-6 py-3.5 rounded-xl bg-emerald-500 hover:bg-emerald-600 active:bg-emerald-700 disabled:opacity-40 disabled:cursor-not-allowed text-white font-bold shadow-md shadow-emerald-500/20 active:scale-[0.98] transition-all text-sm sm:text-base cursor-pointer">
                            <i class="fas fa-floppy-disk text-lg"></i>
                            <span>Simpan Absensi</span>
                        </button>
                    </div>
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

    box.className = `mb-6 p-4 rounded-2xl text-sm flex items-start justify-between border shadow-sm transition-all ${
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
    box.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
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
        document.getElementById('siswaList').innerHTML =
            `<div class="p-6 text-center text-rose-500 font-medium"><i class="fas fa-exclamation-circle me-1"></i> ${escapeHTML(err.message)}</div>`;
    }
}

function renderForm(data) {
    const info = data.jadwal;
    document.getElementById('titleMapel').innerHTML = `<i class="fas fa-book-open text-emerald-300 me-1"></i> ${escapeHTML(info.nama_mapel)}`;
    document.getElementById('subtitleInfo').innerHTML = `
        <span class="inline-flex items-center gap-1.5 bg-white/10 px-2.5 py-1 rounded-lg backdrop-blur-md"><i class="fas fa-door-open text-emerald-300"></i> ${escapeHTML(info.nama_kelas)}</span>
        <span class="inline-flex items-center gap-1.5 bg-white/10 px-2.5 py-1 rounded-lg backdrop-blur-md"><i class="far fa-clock text-emerald-300"></i> ${escapeHTML(info.jam_masuk)} - ${escapeHTML(info.jam_pulang)}</span>
        <span class="inline-flex items-center gap-1.5 bg-white/10 px-2.5 py-1 rounded-lg backdrop-blur-md"><i class="far fa-calendar text-emerald-300"></i> ${escapeHTML(data.tanggal)} (${escapeHTML(info.hari)})</span>
    `;

    const listSiswa = data.siswa || [];
    const container = document.getElementById('siswaList');

    if (listSiswa.length === 0) {
        container.innerHTML = `<div class="p-8 text-center text-slate-400 font-medium">Tidak ada siswa terdaftar di kelas ini.</div>`;
        return;
    }

    document.getElementById('jumlahSiswa').innerText = `(${listSiswa.length} siswa)`;

    const statuses = data.status_valid || ['Hadir', 'Izin', 'Sakit', 'Alfa'];

    const statusColors = {
        'Hadir': 'peer-checked:bg-emerald-600 peer-checked:text-white hover:bg-emerald-50 text-emerald-700 border-emerald-200',
        'Izin':  'peer-checked:bg-amber-500 peer-checked:text-white hover:bg-amber-50 text-amber-700 border-amber-200',
        'Sakit': 'peer-checked:bg-sky-500 peer-checked:text-white hover:bg-sky-50 text-sky-700 border-sky-200',
        'Alfa':  'peer-checked:bg-rose-600 peer-checked:text-white hover:bg-rose-50 text-rose-700 border-rose-200'
    };

    let html = '';

    listSiswa.forEach((s, idx) => {
        const isReqKeterangan = (s.status === 'Izin' || s.status === 'Sakit');
        const reqKeteranganAttr = isReqKeterangan ? '' : 'disabled';

        html += `
            <div class="p-4 md:py-3.5 md:px-4 md:grid md:grid-cols-[2.5rem_minmax(180px,1fr)_auto_minmax(180px,1fr)] md:gap-4 md:items-center hover:bg-slate-50/80 transition-colors">

                <!-- # (desktop only) -->
                <div class="hidden md:flex items-center justify-center text-slate-400 font-semibold text-xs">${idx + 1}</div>

                <!-- Nama -->
                <div class="mb-3 md:mb-0">
                    <span class="md:hidden inline-block text-[11px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md mb-1">#${idx + 1}</span>
                    <div class="font-bold text-slate-800 text-sm">${escapeHTML(s.nama)}</div>
                    <div class="text-[11px] text-slate-400 mt-0.5">NISN: ${escapeHTML(s.nisn || '-')}</div>
                    <input type="hidden" name="kehadiran[${idx}][siswa_id]" value="${s.siswa_id}">
                </div>

                <!-- Status -->
                <div class="mb-3 md:mb-0">
                    <div class="grid grid-cols-4 md:inline-flex gap-1.5 bg-slate-100 md:bg-slate-100 p-1 rounded-xl border border-slate-200/80 w-full md:w-auto">
                        ${statuses.map(st => `
                            <label class="relative cursor-pointer text-center">
                                <input type="radio"
                                    name="kehadiran[${idx}][status]"
                                    value="${st}"
                                    ${(s.status === st) ? 'checked' : ''}
                                    onchange="toggleKeterangan(${idx}, '${st}')"
                                    class="peer sr-only">
                                <span class="block md:inline-block w-full md:w-auto px-2 md:px-3 py-2 md:py-1.5 rounded-lg text-xs font-semibold transition-all border border-transparent ${statusColors[st] || 'peer-checked:bg-slate-800 peer-checked:text-white'}">
                                    ${st}
                                </span>
                            </label>
                        `).join('')}
                    </div>
                </div>

                <!-- Keterangan -->
                <div>
                    <input type="text"
                        id="ket_${idx}"
                        name="kehadiran[${idx}][keterangan]"
                        value="${escapeHTML(s.keterangan || '')}"
                        placeholder="Catatan/Alasan jika Sakit atau Izin..."
                        ${reqKeteranganAttr}
                        class="w-full text-xs px-3 py-2.5 md:py-2 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 disabled:bg-slate-50 disabled:text-slate-300 disabled:border-slate-100 transition-all">
                    <p class="text-xs text-rose-500 mt-1 hidden" id="err_ket_${idx}"></p>
                </div>
            </div>`;
    });

    container.innerHTML = html;
    document.getElementById('btnSimpan').removeAttribute('disabled');
}

function toggleKeterangan(idx, status) {
    const input = document.getElementById(`ket_${idx}`);
    if (!input) return;

    const isRequired = (status === 'Izin' || status === 'Sakit');

    if (isRequired) {
        input.removeAttribute('disabled');
    } else {
        input.value = '';
        input.setAttribute('disabled', 'true');
        input.classList.remove('border-rose-500');
    }

    document.getElementById(`err_ket_${idx}`)?.classList.add('hidden');
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
    btn.innerHTML = `<i class="fas fa-spinner fa-spin text-lg"></i> <span>Menyimpan...</span>`;

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
                            ketInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
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
        btn.innerHTML = `<i class="fas fa-floppy-disk text-lg"></i> <span>Simpan Absensi</span>`;
    }
}
</script>
@endsection