@extends('layouts.guru')
@section('page_title', 'Absensi Siswa')
@section('title', 'Absensi Siswa')

@section('content')
<div class="min-h-screen bg-[#FAF8F5] text-slate-800 pb-16 font-serif">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Header / Title & Filter Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-6">
        <div class="bg-white rounded-xl border border-amber-900/10 shadow-sm p-6 sm:p-8 relative overflow-hidden">
            
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <!-- Title Info -->
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-emerald-900/5 flex items-center justify-center text-emerald-800 shrink-0">
                        <i class="fas fa-clipboard-user text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold font-serif text-slate-900 tracking-tight">
                            Absensi Siswa
                        </h1>
                        <p class="text-xs sm:text-sm text-slate-600 font-sans mt-0.5">
                            Pilih jadwal pelajaran untuk mengisi atau mengoreksi presensi kelas.
                        </p>
                    </div>
                </div>

                <!-- Control / Filter Tanggal -->
                <div class="w-full md:w-auto bg-[#FAF8F5] p-3 rounded-lg border border-amber-900/15 shadow-inner font-sans">
                    <label for="tanggalInput" class="block text-[11px] font-semibold uppercase tracking-wider text-amber-900 mb-1 px-0.5">
                        <i class="far fa-calendar-alt me-1 text-amber-700"></i> Pilih Tanggal Presensi
                    </label>
                    <input 
                        type="date" 
                        id="tanggalInput" 
                        value="{{ date('Y-m-d') }}" 
                        class="w-full md:w-48 bg-white text-slate-800 font-medium px-3 py-2 rounded border border-amber-900/20 focus:border-emerald-800 focus:ring-1 focus:ring-emerald-800 focus:outline-none shadow-sm text-xs sm:text-sm transition-all"
                    >
                </div>
            </div>
        </div>
    </div>

    <!-- Main Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Global Error Alert -->
        <div id="errorAlert" class="hidden mb-6 p-4 rounded-xl bg-rose-50/80 border border-rose-200 text-rose-800 text-sm flex items-start justify-between shadow-sm animate-fade-in font-sans">
            <div class="flex items-start gap-3">
                <div class="p-1.5 bg-rose-100/80 rounded text-rose-700 mt-0.5 shrink-0">
                    <i class="fas fa-triangle-exclamation text-base"></i>
                </div>
                <div>
                    <h4 class="font-bold text-rose-900 font-serif">Terjadi Kesalahan</h4>
                    <span id="errorMessage" class="text-xs text-rose-700 leading-relaxed"></span>
                </div>
            </div>
            <button type="button" onclick="hideError()" class="text-rose-400 hover:text-rose-600 p-1 rounded transition-colors">
                <i class="fas fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Skeleton Loading State -->
        <div id="loadingState" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 hidden">
            @for($i = 0; $i < 3; $i++)
            <div class="bg-white p-6 rounded-xl border border-amber-900/10 shadow-sm animate-pulse flex flex-col justify-between h-56">
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <div class="h-4 bg-slate-200 rounded w-1/3"></div>
                        <div class="h-4 bg-slate-200 rounded-full w-1/4"></div>
                    </div>
                    <div class="h-6 bg-slate-200 rounded w-3/4"></div>
                    <div class="h-4 bg-slate-200 rounded w-1/2"></div>
                </div>
                <div class="pt-4 border-t border-slate-100 space-y-2">
                    <div class="h-3 bg-slate-200 rounded w-full"></div>
                    <div class="h-8 bg-slate-200 rounded w-full"></div>
                </div>
            </div>
            @endfor
        </div>

        <!-- Jadwal List Grid Container -->
        <div id="jadwalContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"></div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const tanggalInput = document.getElementById('tanggalInput');
    if (tanggalInput) {
        fetchJadwal(tanggalInput.value);
        tanggalInput.addEventListener('change', (e) => {
            fetchJadwal(e.target.value);
        });
    }
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
            throw new Error(result.message || `Gagal mengambil data (${response.status})`);
        }

        renderJadwal(result.data);
    } catch (err) {
        showError(err.message);
        container.innerHTML = `
            <div class="col-span-full bg-white rounded-xl p-8 text-center border border-amber-900/10 shadow-sm font-sans">
                <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-full flex items-center justify-center mx-auto mb-3 text-xl border border-rose-200">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <p class="text-slate-800 font-serif font-bold text-base mb-1">Gagal Memuat Jadwal</p>
                <p class="text-slate-500 text-xs">${escapeHTML(err.message)}</p>
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
            <div class="col-span-full bg-white rounded-xl p-10 text-center border border-amber-900/10 shadow-sm font-sans">
                <div class="w-16 h-16 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl border border-slate-200">
                    <i class="fas fa-calendar-xmark"></i>
                </div>
                <h3 class="text-slate-900 font-serif font-bold text-lg mb-1">Tidak Ada Jadwal Mengajar</h3>
                <p class="text-slate-500 text-xs sm:text-sm max-w-md mx-auto">
                    Tidak ditemukan jadwal mengajar pada hari <span class="font-semibold text-emerald-900">${escapeHTML(data.hari)}</span> (${escapeHTML(data.tanggal)}).
                </p>
            </div>`;
        return;
    }

    let html = '';
    const watermarkUrl = "{{ asset('storage/images/logo.png') }}";

    listJadwal.forEach(j => {
        const percent = j.total_siswa > 0 ? Math.round((j.sudah_diabsen / j.total_siswa) * 100) : 0;
        
        const badgeStatus = j.selesai 
            ? `<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded text-[11px] font-sans font-semibold bg-emerald-50 text-emerald-900 border border-emerald-800/20">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-700 animate-pulse"></span> Selesai
               </span>` 
            : `<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded text-[11px] font-sans font-semibold bg-amber-50 text-amber-900 border border-amber-800/20">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-600"></span> Belum Lengkap
               </span>`;

        const formUrl = `{{ route('guru.absen-siswa.form', ':id') }}`.replace(':id', j.id) + `?tanggal=${encodeURIComponent(data.tanggal)}`;

        html += `
            <div class="group relative bg-white rounded-xl border border-amber-900/15 shadow-sm hover:shadow-xl hover:border-emerald-800/40 transition-all duration-300 p-6 flex flex-col justify-between overflow-hidden">
                
                <!-- BACKGROUND LOGO WATERMARK -->
                <div class="absolute -bottom-6 -right-6 w-40 h-40 opacity-[0.06] group-hover:opacity-[0.12] transition-all duration-300 pointer-events-none select-none transform group-hover:scale-105">
                    <img src="${watermarkUrl}" 
                         alt="Watermark Logo" 
                         class="w-full h-full object-contain filter grayscale" />
                </div>

                <!-- Card Content -->
                <div class="relative z-10">
                    <!-- Top Status & Jam -->
                    <div class="flex items-center justify-between gap-2 mb-3 font-sans">
                        <span class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-600 bg-slate-100/80 border border-slate-200 px-2 py-0.5 rounded">
                            <i class="far fa-clock text-amber-700"></i> ${escapeHTML(j.jam_masuk)} - ${escapeHTML(j.jam_pulang)} WIB
                        </span>
                        ${badgeStatus}
                    </div>

                    <!-- Mapel Title -->
                    <h3 class="text-xl font-bold font-serif text-slate-900 leading-snug mb-2 group-hover:text-emerald-900 transition-colors">
                        ${escapeHTML(j.nama_mapel)}
                    </h3>

                    <!-- Kelas Info -->
                    <p class="text-xs font-sans text-slate-600 flex items-center gap-1.5 mb-5">
                        <i class="fas fa-door-open text-slate-400"></i> Kelas: 
                        <span class="font-bold text-slate-800 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-800/20">
                            ${escapeHTML(j.nama_kelas)}
                        </span>
                    </p>

                    <!-- Progress Presensi Siswa -->
                    <div class="space-y-1.5 mb-6 font-sans bg-[#FAF8F5] p-3 rounded-lg border border-amber-900/10">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-600 font-medium">Progres Presensi</span>
                            <span class="font-bold text-slate-800">${j.sudah_diabsen} / ${j.total_siswa} Siswa</span>
                        </div>
                        <div class="w-full bg-slate-200/70 rounded-full h-2 overflow-hidden">
                            <div class="bg-emerald-800 h-2 rounded-full transition-all duration-300" style="width: ${percent}%"></div>
                        </div>
                    </div>
                </div>

                <!-- Action Button -->
                <div class="relative z-10 pt-2 font-sans">
                    <a href="${formUrl}" class="inline-flex items-center justify-center gap-2 w-full py-2.5 px-4 text-xs sm:text-sm font-semibold tracking-wide text-emerald-900 bg-emerald-50/50 hover:bg-emerald-800 hover:text-white rounded-lg transition-all duration-200 border border-emerald-800/30 hover:border-emerald-800 shadow-sm active:scale-[0.99]">
                        ${j.selesai 
                            ? '<i class="fas fa-pen-to-square text-xs"></i> Edit Presensi' 
                            : '<i class="fas fa-clipboard-check text-xs"></i> Isi Presensi'
                        }
                    </a>
                </div>

            </div>`;
    });

    container.innerHTML = html;
}
</script>
@endsection