@extends('layouts.guru')

@section('title', 'Absensi')
@section('page_title', 'Absensi Face ID')

@section('content')
<div class="max-w-md mx-auto px-3 py-4 space-y-4">

    {{-- ===================== HEADER: GURU + JAM ===================== --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="bg-slate-900 px-5 py-4 text-white">
            <div class="flex items-center justify-between gap-3">
                <div class="min-w-0">
                    <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Selamat datang</p>
                    <h2 id="nama-guru" class="text-lg font-bold tracking-tight mt-0.5 truncate">
                        {{ auth()->user()->name ?? '—' }}
                    </h2>
                </div>
                <div class="shrink-0 w-11 h-11 bg-white/10 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-chalkboard-user text-lg text-white/80"></i>
                </div>
            </div>
        </div>
        <div class="px-5 py-3 flex items-center justify-between bg-slate-50">
            <div class="flex items-center gap-2 text-xs text-slate-500 min-w-0">
                <i class="fa-solid fa-calendar-day text-slate-400 shrink-0"></i>
                <span id="tanggal-hari-ini" class="font-medium text-slate-600 truncate"></span>
            </div>
            <div class="flex items-center gap-1.5 text-xs shrink-0">
                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg bg-emerald-50 text-emerald-700 font-semibold border border-emerald-200">
                    <i class="fa-solid fa-right-to-bracket text-[10px]"></i>
                    <span id="info-jam-masuk">--:--</span>
                </span>
                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg bg-rose-50 text-rose-700 font-semibold border border-rose-200">
                    <i class="fa-solid fa-right-from-bracket text-[10px]"></i>
                    <span id="info-jam-keluar">--:--</span>
                </span>
            </div>
        </div>
    </div>

    {{-- ===================== JADWAL HARI INI ===================== --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm px-5 py-4">
        <div class="flex items-center justify-between mb-2.5">
            <h3 class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                <i class="fa-solid fa-calendar-week text-slate-400"></i>
                Jadwal hari ini
            </h3>
            <a href="{{ route('guru.jadwal.index') }}"
               class="text-[11px] font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">
                Lihat semua <i class="fa-solid fa-chevron-right text-[8px] ml-0.5"></i>
            </a>
        </div>
        <div id="info-jadwal">
            <div class="flex items-center gap-2 text-xs text-slate-400 py-1">
                <i class="fa-solid fa-circle-notch animate-spin"></i>
                <span>Memuat jadwal…</span>
            </div>
        </div>
    </div>

    {{-- ===================== STATUS + AKSI (diisi JS) ===================== --}}
    <div id="ringkasan-loading" class="bg-white rounded-2xl border border-slate-200 shadow-sm px-5 py-8 flex flex-col items-center justify-center gap-2 text-slate-400">
        <i class="fa-solid fa-circle-notch animate-spin text-xl"></i>
        <span class="text-xs font-medium">Memuat status absensi…</span>
    </div>

    <div id="ringkasan-content" class="hidden space-y-4">
        <div id="status-banner" class="rounded-2xl p-4 border flex items-start gap-3"></div>

        <div id="aksi-area" class="w-full"></div>

        <div class="grid grid-cols-2 gap-3">
            <button type="button" id="btn-buka-izin"
                    class="flex items-center justify-center gap-2 w-full py-3 px-4 rounded-xl bg-amber-50 hover:bg-amber-100 active:scale-[0.98] text-amber-700 border border-amber-200 text-xs font-bold transition-all">
                <i class="fa-solid fa-file-pen"></i>
                <span>Ajukan izin</span>
            </button>

            <a href="{{ route('guru.absensi.riwayat') }}"
               class="flex items-center justify-center gap-2 w-full py-3 px-4 rounded-xl bg-slate-50 hover:bg-slate-100 active:scale-[0.98] text-slate-600 border border-slate-200 text-xs font-bold transition-all">
                <i class="fa-solid fa-clock-rotate-left"></i>
                <span>Riwayat</span>
            </a>
        </div>
    </div>

    {{-- ===================== PANDUAN ===================== --}}
    <div class="bg-slate-900 text-white rounded-2xl p-5">
        <h4 class="text-xs font-bold tracking-wide mb-2 flex items-center gap-2 text-slate-300">
            <i class="fa-solid fa-circle-info"></i> Cara absen
        </h4>
        <ol class="text-[11px] text-slate-300 space-y-1.5 leading-relaxed list-decimal list-inside marker:text-slate-500">
            <li>Pastikan <strong class="text-white">GPS &amp; kamera</strong> aktif dan izinkan akses saat diminta.</li>
            <li>Posisikan wajah di tengah bingkai kamera dengan pencahayaan yang cukup.</li>
            <li>Tekan <strong class="text-white">"Kirim Absen"</strong> untuk merekam kehadiran.</li>
            <li>Untuk izin/sakit/cuti/tugas luar, tekan <strong class="text-white">"Ajukan Izin"</strong> sebagai gantinya.</li>
        </ol>
    </div>
</div>

{{-- ============================================================ --}}
{{-- MODAL KAMERA (Face ID + GPS)                                  --}}
{{-- ============================================================ --}}
<div id="camera-modal" class="hidden fixed inset-0 bg-black/60 z-50 items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm overflow-hidden">

        <div class="flex justify-between items-center px-5 py-4 border-b border-slate-100">
            <h3 id="modal-title" class="text-sm font-bold text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-camera text-slate-500"></i> Absen
            </h3>
            <button id="modal-close" type="button" aria-label="Tutup"
                    class="w-7 h-7 flex items-center justify-center text-slate-400 hover:text-slate-600 hover:bg-slate-50 rounded-lg transition-all">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <div id="modal-camera-view" class="p-5 space-y-4">
            <div class="relative rounded-2xl overflow-hidden bg-slate-950 aspect-[3/4] flex items-center justify-center">
                <video id="video" autoplay playsinline muted
                       class="absolute inset-0 w-full h-full object-cover mirror"></video>
                <canvas id="canvas-capture" class="hidden"></canvas>

                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                    <div class="w-48 h-56 rounded-2xl border-2 border-dashed border-white/30"></div>
                </div>

                <div id="camera-status" class="absolute bottom-3 left-3 right-3 text-center">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-black/50 text-white text-[10px] font-medium">
                        <i class="fa-solid fa-circle-notch animate-spin text-indigo-400"></i>
                        Menyiapkan kamera…
                    </span>
                </div>
            </div>

            <div class="flex items-center justify-center">
                <div id="gps-pill" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-amber-50 border border-amber-200 text-amber-700">
                    <i class="fa-solid fa-satellite text-[10px]"></i>
                    <span id="gps-text">Mencari lokasi GPS…</span>
                </div>
            </div>

            <div id="modal-error" class="hidden p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs font-medium"></div>

            <button id="btn-submit-absen" type="button"
                    class="w-full py-3.5 px-4 rounded-xl font-semibold shadow-sm transition-all flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white">
                <i class="fa-solid fa-paper-plane"></i> Kirim absen
            </button>
        </div>

        <div id="modal-success-view" class="hidden p-8 text-center space-y-4">
            <div class="flex justify-center">
                <div class="w-16 h-16 rounded-full bg-emerald-100 flex items-center justify-center">
                    <i class="fa-solid fa-check text-3xl text-emerald-600"></i>
                </div>
            </div>
            <div>
                <h4 class="text-base font-bold text-slate-800">Berhasil</h4>
                <p id="modal-success-text" class="text-sm text-slate-500 mt-1">Absensi Anda telah tercatat.</p>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- MODAL PENGAJUAN IZIN                                          --}}
{{-- ============================================================ --}}
<div id="modal-izin" class="hidden fixed inset-0 bg-black/50 z-50 items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm overflow-hidden max-h-[90vh] flex flex-col">
        <div class="flex justify-between items-center px-5 py-4 border-b border-slate-100 shrink-0">
            <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-file-pen text-amber-500"></i> Pengajuan izin
            </h3>
            <button id="close-modal-izin" type="button" aria-label="Tutup"
                    class="w-7 h-7 flex items-center justify-center text-slate-400 hover:text-slate-600 hover:bg-slate-50 rounded-lg transition-all">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <div class="p-5 overflow-y-auto space-y-4 flex-1">
            <div class="p-3 rounded-xl bg-amber-50 border border-amber-200 text-amber-700 text-[11px] font-medium leading-relaxed">
                <i class="fa-solid fa-circle-info mr-1"></i>
                Ajukan ketidakhadiran (izin, sakit, cuti, atau tugas luar). Pengajuan akan diverifikasi oleh admin.
            </div>

            <form id="form-izin" enctype="multipart/form-data" novalidate>
                @csrf

                <div class="space-y-1.5">
                    <label for="izin-tanggal" class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">
                        Tanggal <span class="text-red-400">*</span>
                    </label>
                    <input type="date" id="izin-tanggal" name="tanggal"
                           value="{{ date('Y-m-d') }}"
                           min="{{ date('Y-m-d', strtotime('-7 days')) }}"
                           max="{{ date('Y-m-d', strtotime('+30 days')) }}"
                           class="w-full rounded-xl bg-slate-50 border border-slate-200 text-xs focus:ring-2 focus:ring-amber-400 focus:border-amber-400 text-slate-800 font-medium py-2.5 px-3">
                </div>

                <div class="space-y-1.5">
                    <label for="izin-status" class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">
                        Tipe <span class="text-red-400">*</span>
                    </label>
                    <select id="izin-status" name="status"
                            class="w-full rounded-xl bg-slate-50 border border-slate-200 text-xs focus:ring-2 focus:ring-amber-400 focus:border-amber-400 text-slate-800 font-medium py-2.5 px-3">
                        <option value="izin">Izin</option>
                        <option value="sakit">Sakit</option>
                        <option value="cuti">Cuti</option>
                        <option value="tugas_luar">Tugas luar</option>
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label for="izin-keterangan" class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">
                        Keterangan <span class="text-red-400">*</span>
                    </label>
                    <textarea id="izin-keterangan" name="keterangan" rows="3" maxlength="1000"
                              placeholder="Jelaskan alasan ketidakhadiran Anda…"
                              class="w-full rounded-xl bg-slate-50 border border-slate-200 text-xs focus:ring-2 focus:ring-amber-400 focus:border-amber-400 text-slate-800 font-medium py-2.5 px-3 resize-none"></textarea>
                    <p class="text-[10px] text-slate-400 text-right"><span id="counter-keterangan">0</span>/1000</p>
                </div>

                <div class="space-y-1.5">
                    <label for="izin-bukti" class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">
                        Lampiran <span class="text-slate-300 text-[9px] font-normal">(opsional)</span>
                    </label>
                    <input type="file" id="izin-bukti" name="bukti_dokumen"
                           accept=".jpg,.jpeg,.png,.pdf"
                           class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-[10px] file:font-bold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 border border-slate-200 rounded-xl py-1.5 px-2">
                    <p class="text-[10px] text-slate-400">Maks. 2MB, format JPG, PNG, atau PDF.</p>
                </div>

                <div id="izin-error" class="hidden p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs font-medium space-y-1"></div>

                <button type="submit" id="btn-kirim-izin"
                        class="w-full py-3.5 px-4 rounded-xl font-bold shadow-sm transition-all flex items-center justify-center gap-2 bg-amber-500 hover:bg-amber-600 active:bg-amber-700 text-white text-sm">
                    <i class="fa-solid fa-paper-plane"></i> Kirim pengajuan
                </button>
            </form>
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- TOAST                                                         --}}
{{-- ============================================================ --}}
<div id="toast" class="hidden fixed top-20 left-1/2 -translate-x-1/2 z-[60] w-full max-w-sm px-4 transition-all duration-300">
    <div id="toast-body" class="flex items-center gap-3 px-4 py-3 rounded-xl shadow-lg border text-sm font-medium">
        <i id="toast-icon" class="fa-solid fa-circle-info"></i>
        <span id="toast-message"></span>
    </div>
</div>

<style>
    .mirror { transform: scaleX(-1); }
    #toast.show { opacity: 1; transform: translateX(-50%) translateY(0); }
    #toast:not(.show) { opacity: 0; transform: translateX(-50%) translateY(-12px); }
    body.modal-open { overflow: hidden; }
    @media (prefers-reduced-motion: reduce) {
        .animate-spin { animation: none; }
    }
</style>

<script>
    window.ABSENSI_CONFIG = {
        modelsUrl: '{{ asset('/models') }}',
        wajahStatusUrl: '{{ route('guru.wajah.status') }}',
        wajahHalamanUrl: '{{ route('guru.wajah.halaman') }}',
        statusUrl: '{{ route('guru.absensi.status') }}',
        masukUrl: '{{ route('guru.absensi.masuk') }}',
        pulangUrl: '{{ route('guru.absensi.pulang') }}',
        ajukanIzinUrl: '{{ route('guru.absensi.ajukan-izin') }}',
        csrfToken: '{{ csrf_token() }}',
    };
</script>
<script src="{{ asset('/js/absensi.js') }}" defer></script>
@endsection