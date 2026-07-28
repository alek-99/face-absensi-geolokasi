@extends('layouts.guru')
@section('page_title', 'Absen Siswa')
@section('title', 'Absen Siswa')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Header & Filter Tanggal -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 flex items-center gap-2">
                <i class="fas fa-clipboard-user text-indigo-600"></i> Absensi Siswa
            </h1>
            <p class="text-sm text-slate-500 mt-1">Pilih jadwal pelajaran untuk mengisi atau mengoreksi absensi.</p>
        </div>
        <div class="flex items-center gap-2 bg-white p-2 rounded-xl border border-slate-200 shadow-sm w-fit">
            <i class="fas fa-calendar-day text-slate-400 ms-2"></i>
            <input type="date" id="tanggalInput" 
                class="border-0 focus:ring-0 text-sm font-semibold text-slate-700 bg-transparent cursor-pointer"
                value="{{ date('Y-m-d') }}" 
                max="{{ date('Y-m-d') }}">
        </div>
    </div>

    <!-- Alert Banner Error Global -->
    <div id="errorAlert" class="hidden mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm flex items-center justify-between">
        <div class="flex items-center gap-2">
            <i class="fas fa-triangle-exclamation text-red-500"></i>
            <span id="errorMessage"></span>
        </div>
        <button type="button" onclick="hideError()" class="text-red-400 hover:text-red-600">
            <i class="fas fa-xmark"></i>
        </button>
    </div>

    <!-- Loading Skeleton -->
    <div id="loadingState" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 hidden">
        @for($i = 0; $i < 3; $i++)
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm animate-pulse">
            <div class="h-4 bg-slate-200 rounded w-1/3 mb-4"></div>
            <div class="h-6 bg-slate-200 rounded w-3/4 mb-2"></div>
            <div class="h-4 bg-slate-200 rounded w-1/2 mb-6"></div>
            <div class="h-10 bg-slate-200 rounded w-full"></div>
        </div>
        @endfor
    </div>

    <!-- Container Daftar Jadwal -->
    <div id="jadwalContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const tanggalInput = document.getElementById('tanggalInput');
    fetchJadwal(tanggalInput.value);

    tanggalInput.addEventListener('change', (e) => {
        fetchJadwal(e.target.value);
    });
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

function showError(message) {
    const alertEl = document.getElementById('errorAlert');
    document.getElementById('errorMessage').innerText = message;
    alertEl.classList.remove('hidden');
}

function hideError() {
    document.getElementById('errorAlert').classList.add('hidden');
}

async function fetchJadwal(tanggal) {
    const container = document.getElementById('jadwalContainer');
    const loading = document.getElementById('loadingState');
    hideError();

    container.innerHTML = '';
    loading.classList.remove('hidden');

    try {
        const response = await fetch(`{{ route('guru.absen-siswa.api.jadwal-hari-ini') }}?tanggal=${encodeURIComponent(tanggal)}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || `Terjadi kesalahan (${response.status})`);
        }

        renderJadwal(result.data);
    } catch (err) {
        showError(err.message);
        container.innerHTML = `
            <div class="col-span-full text-center py-12 text-slate-400">
                <i class="fas fa-circle-exclamation text-3xl mb-2"></i>
                <p>Gagal memuat data jadwal. Silakan coba lagi.</p>
            </div>`;
    } finally {
        loading.classList.add('hidden');
    }
}

function renderJadwal(data) {
    const container = document.getElementById('jadwalContainer');
    const listJadwal = data.jadwal || [];

    if (listJadwal.length === 0) {
        container.innerHTML = `
            <div class="col-span-full bg-white rounded-2xl p-12 text-center border border-slate-100 shadow-sm">
                <i class="fas fa-calendar-xmark text-4xl text-slate-300 mb-3"></i>
                <p class="text-slate-500 font-medium">Tidak ada jadwal mengajar pada hari <span class="font-bold text-slate-700">${escapeHTML(data.hari)}</span> (${escapeHTML(data.tanggal)}).</p>
            </div>`;
        return;
    }

    let html = '';
    listJadwal.forEach(j => {
        const badgeStatus = j.selesai 
            ? `<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                <i class="fas fa-circle-check"></i> Selesai
               </span>` 
            : `<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                <i class="fas fa-clock"></i> Belum Lengkap
               </span>`;

        const formUrl = `{{ route('guru.absen-siswa.form', ':id') }}`.replace(':id', j.id) + `?tanggal=${encodeURIComponent(data.tanggal)}`;

        html += `
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow duration-200 p-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="inline-flex items-center gap-1 text-xs font-medium text-indigo-700 bg-indigo-50 px-2.5 py-1 rounded-lg">
                            <i class="far fa-clock"></i> ${escapeHTML(j.jam_masuk)} - ${escapeHTML(j.jam_pulang)}
                        </span>
                        ${badgeStatus}
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 mb-1">${escapeHTML(j.nama_mapel)}</h3>
                    <p class="text-sm text-slate-500 flex items-center gap-1.5 mb-4">
                        <i class="fas fa-school text-slate-400"></i> Kelas: <span class="font-semibold text-slate-700">${escapeHTML(j.nama_kelas)}</span>
                    </p>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs text-slate-500">
                        <i class="fas fa-users text-slate-400 me-1"></i> ${j.sudah_diabsen} / ${j.total_siswa} Siswa
                    </span>
                    <a href="${formUrl}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-xl transition-colors">
                        ${j.selesai ? '<i class="fas fa-pen-to-square"></i> Edit Absen' : '<i class="fas fa-clipboard-check"></i> Isi Absen'}
                    </a>
                </div>
            </div>`;
    });

    container.innerHTML = html;
}
</script>
@endsection