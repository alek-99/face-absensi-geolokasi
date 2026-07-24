@extends('layouts.guru')

@section('title', 'Absensi')
@section('page_title', 'Absensi Face ID')

@section('content')
<div class="max-w-md mx-auto px-2 py-4 space-y-5 animate-fade-in">

    {{-- ===== SECTION 1: HEADER INFO GURU ===== --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-indigo-600 to-violet-600 px-5 py-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-indigo-200 uppercase tracking-wider">Selamat Datang</p>
                    <h2 id="nama-guru" class="text-lg font-extrabold tracking-tight mt-0.5">
                        {{ auth()->user()->name ?? '—' }}
                    </h2>
                </div>
                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-sm">
                    <i class="fa-solid fa-face-smile text-2xl text-white/90"></i>
                </div>
            </div>
        </div>
        <div class="px-5 py-3.5 flex items-center justify-between bg-slate-50/80">
            <div class="flex items-center gap-2 text-xs text-slate-500">
                <i class="fa-solid fa-calendar-day text-indigo-400"></i>
                <span id="tanggal-hari-ini" class="font-semibold text-slate-600">
                    {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                </span>
            </div>
            <div class="flex items-center gap-2 text-xs">
                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg bg-indigo-50 text-indigo-600 font-semibold border border-indigo-100">
                    <i class="fa-solid fa-right-to-bracket text-[10px]"></i>
                    <span id="info-jam-masuk">--:--</span>
                </span>
                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg bg-rose-50 text-rose-600 font-semibold border border-rose-100">
                    <i class="fa-solid fa-right-from-bracket text-[10px]"></i>
                    <span id="info-jam-keluar">--:--</span>
                </span>
            </div>
        </div>
    </div>

    {{-- ===== SECTION 2: JADWAL HARI INI ===== --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm px-5 py-4">
        <div class="flex items-center justify-between mb-2">
            <h3 class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                <i class="fa-solid fa-calendar-week text-indigo-500"></i>
                Jadwal Hari Ini
            </h3>
            <a href="{{ route('guru.jadwal.index') }}"
               class="text-[10px] font-bold text-indigo-600 hover:text-indigo-800 transition-colors">
                Lihat Semua <i class="fa-solid fa-chevron-right text-[8px] ml-0.5"></i>
            </a>
        </div>
        <div id="info-jadwal" class="text-xs text-slate-400">
            <span id="ringkasan-loading">
                <i class="fa-solid fa-circle-notch animate-spin text-indigo-400 mr-1.5"></i>
                Memuat jadwal...
            </span>
        </div>
    </div>

    {{-- ===== SECTION 3: STATUS BANNER (Dinamis dari JS) ===== --}}
    <div id="ringkasan-content" class="hidden space-y-4">
        {{-- Status Banner --}}
        <div id="status-banner" class="rounded-xl p-4 border flex items-start gap-3">
            {{-- Diisi oleh JS --}}
        </div>

        {{-- Action Area (Tombol Absen / Info) --}}
        <div id="aksi-area" class="w-full">
            {{-- Diisi oleh JS --}}
        </div>

        {{-- ===== SECTION 4: FITUR TAMBAHAN (IZIN & RIWAYAT CEPAT) ===== --}}
        <div class="grid grid-cols-2 gap-3">
            {{-- Tombol Buka Form Izin --}}
            <button type="button" id="btn-buka-izin"
                    class="flex items-center justify-center gap-2 w-full py-3.5 px-4 rounded-xl bg-amber-50 hover:bg-amber-100 active:scale-[0.98] text-amber-700 border border-amber-200 text-xs font-bold transition-all cursor-pointer shadow-sm">
                <i class="fa-solid fa-file-pen"></i>
                <span>Ajukan Izin</span>
            </button>

            {{-- Tombol Cepat ke Riwayat --}}
            <a href="{{ route('guru.absensi.riwayat') }}"
               class="flex items-center justify-center gap-2 w-full py-3.5 px-4 rounded-xl bg-slate-50 hover:bg-slate-100 active:scale-[0.98] text-slate-600 border border-slate-200 text-xs font-bold transition-all shadow-sm">
                <i class="fa-solid fa-clock-rotate-left"></i>
                <span>Riwayat</span>
            </a>
        </div>
    </div>

    {{-- ===== SECTION 5: CARD PANDUAN ABSEN ===== --}}
    <div class="bg-gradient-to-br from-slate-800 to-slate-900 text-white rounded-2xl p-5 shadow-sm relative overflow-hidden">
        <div class="absolute right-0 top-0 opacity-5">
            <i class="fa-solid fa-circle-info text-7xl"></i>
        </div>
        <h4 class="text-xs font-bold tracking-wide mb-1 flex items-center gap-2 text-indigo-400">
            <i class="fa-solid fa-lightbulb"></i> Cara Absen
        </h4>
        <ul class="text-[11px] text-slate-300 space-y-1.5 leading-relaxed">
            <li class="flex items-start gap-2">
                <i class="fa-solid fa-1 mt-0.5 text-indigo-400 text-[10px]"></i>
                <span>Pastikan <strong>GPS & Kamera</strong> aktif dan izinkan akses.</span>
            </li>
            <li class="flex items-start gap-2">
                <i class="fa-solid fa-2 mt-0.5 text-indigo-400 text-[10px]"></i>
                <span>Posisikan wajah di tengah bingkai kamera dengan pencahayaan cukup.</span>
            </li>
            <li class="flex items-start gap-2">
                <i class="fa-solid fa-3 mt-0.5 text-indigo-400 text-[10px]"></i>
                <span>Tekan <strong>"Kirim Absen"</strong> untuk merekam kehadiran.</span>
            </li>
            <li class="flex items-start gap-2">
                <i class="fa-solid fa-4 mt-0.5 text-indigo-400 text-[10px]"></i>
                <span>Untuk izin/sakit/cuti, tekan tombol <strong>"Ajukan Izin"</strong>.</span>
            </li>
        </ul>
    </div>
</div>

{{-- ============================================================ --}}
{{-- MODAL KAMERA ABSENSI (Face ID + GPS)                         --}}
{{-- ============================================================ --}}
<div id="camera-modal" class="hidden fixed inset-0 bg-black/60 z-50 items-center justify-center p-4 backdrop-blur-sm">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm overflow-hidden border border-white/20">

        {{-- Header Modal --}}
        <div class="flex justify-between items-center px-5 py-4 border-b border-slate-100">
            <h3 id="modal-title" class="text-sm font-bold text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-camera text-indigo-500"></i> Absen
            </h3>
            <button id="modal-close" type="button"
                    class="w-7 h-7 flex items-center justify-center text-slate-400 hover:text-slate-600 hover:bg-slate-50 rounded-lg transition-all cursor-pointer">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        {{-- Camera View --}}
        <div id="modal-camera-view" class="p-5 space-y-4">
            <div class="relative rounded-2xl overflow-hidden bg-black aspect-[3/4] flex items-center justify-center">
                <video id="video" autoplay playsinline muted
                       class="absolute inset-0 w-full h-full object-cover mirror"></video>
                <canvas id="canvas-capture" class="hidden"></canvas>

                {{-- Overlay Face Guide --}}
                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                    <div class="w-48 h-56 rounded-2xl border-2 border-dashed border-white/30 bg-white/5 backdrop-blur-[1px] flex items-center justify-center">
                        <i class="fa-solid fa-face-smile text-5xl text-white/20"></i>
                    </div>
                </div>

                {{-- Camera Status --}}
                <div id="camera-status" class="absolute bottom-3 left-3 right-3 text-center">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-black/50 backdrop-blur-sm text-white text-[10px] font-medium">
                        <i class="fa-solid fa-circle-notch animate-spin text-indigo-400"></i>
                        Menyiapkan kamera...
                    </span>
                </div>
            </div>

            {{-- GPS Status --}}
            <div class="flex items-center justify-center">
                <div id="gps-pill" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-amber-50 border border-amber-200 text-amber-700">
                    <i class="fa-solid fa-satellite text-[10px]"></i>
                    <span id="gps-text">Mencari lokasi GPS...</span>
                </div>
            </div>

            {{-- Error Message --}}
            <div id="modal-error" class="hidden p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs font-medium"></div>

            {{-- Submit Button --}}
            <button id="btn-submit-absen" type="button"
                    class="w-full py-3.5 px-4 rounded-xl font-semibold shadow-sm transition-all flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white cursor-pointer">
                <i class="fa-solid fa-paper-plane"></i> Kirim Absen
            </button>
        </div>

        {{-- Success View --}}
        <div id="modal-success-view" class="hidden p-8 text-center space-y-4">
            <div class="flex justify-center">
                <div id="modal-success-check" class="w-20 h-20 rounded-full bg-emerald-100 flex items-center justify-center">
                    <i class="fa-solid fa-check text-4xl text-emerald-500"></i>
                </div>
            </div>
            <div>
                <h4 class="text-base font-bold text-slate-800">Berhasil!</h4>
                <p id="modal-success-text" class="text-sm text-slate-500 mt-1">Absensi Anda telah tercatat.</p>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- MODAL PENGAJUAN IZIN                                         --}}
{{-- ============================================================ --}}
<div id="modal-izin" class="hidden fixed inset-0 bg-black/50 z-50 items-center justify-center p-4 backdrop-blur-sm">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm overflow-hidden border border-white/20 max-h-[90vh] flex flex-col">
        {{-- Header --}}
        <div class="flex justify-between items-center px-5 py-4 border-b border-slate-100 shrink-0">
            <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-file-pen text-amber-500"></i> Pengajuan Izin
            </h3>
            <button id="close-modal-izin" type="button"
                    class="w-7 h-7 flex items-center justify-center text-slate-400 hover:text-slate-600 hover:bg-slate-50 rounded-lg transition-all cursor-pointer">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        {{-- Body --}}
        <div class="p-5 overflow-y-auto space-y-4 flex-1">
            <div class="p-3 rounded-xl bg-amber-50 border border-amber-200 text-amber-700 text-[11px] font-medium leading-relaxed">
                <i class="fa-solid fa-circle-info mr-1"></i>
                Ajukan ketidakhadiran (Izin, Sakit, Cuti, atau Tugas Luar). Pengajuan akan diverifikasi oleh Admin.
            </div>

            <form id="form-izin" enctype="multipart/form-data" novalidate>
                @csrf

                {{-- Tanggal --}}
                <div class="space-y-1.5">
                    <label for="izin-tanggal" class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">
                        Tanggal <span class="text-red-400">*</span>
                    </label>
                    <input type="date" id="izin-tanggal" name="tanggal"
                           value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d', strtotime('-7 days')) }}" max="{{ date('Y-m-d', strtotime('+30 days')) }}"
                           class="w-full rounded-xl bg-slate-50 border border-slate-200 text-xs focus:ring-amber-500 focus:border-amber-500 text-slate-800 font-medium py-2.5 px-3">
                </div>

                {{-- Tipe Izin --}}
                <div class="space-y-1.5">
                    <label for="izin-status" class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">
                        Tipe <span class="text-red-400">*</span>
                    </label>
                    <select id="izin-status" name="status"
                            class="w-full rounded-xl bg-slate-50 border border-slate-200 text-xs focus:ring-amber-500 focus:border-amber-500 text-slate-800 font-medium py-2.5 px-3">
                        <option value="izin">Izin</option>
                        <option value="sakit">Sakit</option>
                        <option value="cuti">Cuti</option>
                        <option value="tugas_luar">Tugas Luar</option>
                    </select>
                </div>

                {{-- Keterangan --}}
                <div class="space-y-1.5">
                    <label for="izin-keterangan" class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">
                        Keterangan <span class="text-red-400">*</span>
                    </label>
                    <textarea id="izin-keterangan" name="keterangan" rows="3" maxlength="1000"
                              placeholder="Jelaskan alasan ketidakhadiran Anda..."
                              class="w-full rounded-xl bg-slate-50 border border-slate-200 text-xs focus:ring-amber-500 focus:border-amber-500 text-slate-800 font-medium py-2.5 px-3 resize-none"></textarea>
                    <p class="text-[10px] text-slate-400 text-right"><span id="counter-keterangan">0</span>/1000</p>
                </div>

                {{-- Upload Dokumen --}}
                <div class="space-y-1.5">
                    <label for="izin-bukti" class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">
                        Lampiran <span class="text-slate-300 text-[9px] font-normal">(opsional)</span>
                    </label>
                    <div class="relative">
                        <input type="file" id="izin-bukti" name="bukti_dokumen"
                               accept=".jpg,.jpeg,.png,.pdf"
                               class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-[10px] file:font-bold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 file:cursor-pointer cursor-pointer border border-slate-200 rounded-xl py-1.5 px-2">
                    </div>
                    <p class="text-[10px] text-slate-400">Maks. 2MB, format: JPG, PNG, PDF</p>
                </div>

                {{-- Error Container --}}
                <div id="izin-error" class="hidden p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs font-medium space-y-1"></div>

                {{-- Submit Button --}}
                <button type="submit" id="btn-kirim-izin"
                        class="w-full py-3.5 px-4 rounded-xl font-bold shadow-sm transition-all flex items-center justify-center gap-2 bg-amber-500 hover:bg-amber-600 active:bg-amber-700 text-white cursor-pointer text-sm">
                    <i class="fa-solid fa-paper-plane"></i> Kirim Pengajuan
                </button>
            </form>
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- TOAST NOTIFICATION                                           --}}
{{-- ============================================================ --}}
<div id="toast" class="hidden fixed top-20 left-1/2 -translate-x-1/2 z-[60] w-full max-w-sm px-4 transition-all duration-300">
    <div id="toast-body" class="flex items-center gap-3 px-4 py-3 rounded-xl shadow-lg border text-sm font-medium">
        <i id="toast-icon" class="fa-solid fa-circle-info"></i>
        <span id="toast-message"></span>
    </div>
</div>

<style>
    .animate-fade-in {
        animation: fadeIn 0.3s ease-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .mirror {
        transform: scaleX(-1);
    }
    #toast.show {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
    }
    #toast:not(.show) {
        opacity: 0;
        transform: translateX(-50%) translateY(-12px);
    }
    body.modal-open {
        overflow: hidden;
    }
</style>

<script>
    (function() {
        'use strict';

        // =============================================================
        // 1. KONFIGURASI GLOBAL UNTUK absensi.js (DISESUAIKAN DENGAN ROUTE CONTROLLER)
        // =============================================================
        window.ABSENSI_CONFIG = {
            modelsUrl: '{{ asset('/models') }}',
            wajahStatusUrl: '{{ route("guru.wajah.status") }}',
            wajahHalamanUrl: '{{ route("guru.wajah.halaman") }}',
            statusUrl: '{{ route("guru.absensi.status") }}',
            masukUrl: '{{ route("guru.absensi.masuk") }}',
            pulangUrl: '{{ route("guru.absensi.pulang") }}',
            csrfToken: '{{ csrf_token() }}',
        };

        // Load absensi.js
        var script = document.createElement('script');
        script.src = '{{ asset("/js/absensi.js") }}';
        script.async = false;
        document.body.appendChild(script);

        // =============================================================
        // 2. LOGIK PENGAJUAN IZIN (AJAX BERBASED CONTROLLER VALIDATION)
        // =============================================================
        var els = {
            modal: document.getElementById('modal-izin'),
            btnBuka: document.getElementById('btn-buka-izin'),
            btnTutup: document.getElementById('close-modal-izin'),
            form: document.getElementById('form-izin'),
            tanggal: document.getElementById('izin-tanggal'),
            status: document.getElementById('izin-status'),
            keterangan: document.getElementById('izin-keterangan'),
            counter: document.getElementById('counter-keterangan'),
            bukti: document.getElementById('izin-bukti'),
            btnKirim: document.getElementById('btn-kirim-izin'),
            error: document.getElementById('izin-error'),
        };

        // Counter Karakter Keterangan
        if (els.keterangan && els.counter) {
            els.keterangan.addEventListener('input', function() {
                els.counter.textContent = this.value.length;
            });
        }

        // Buka Modal
        if (els.btnBuka && els.modal) {
            els.btnBuka.addEventListener('click', function() {
                els.modal.classList.remove('hidden');
                els.modal.classList.add('flex');
                document.body.classList.add('modal-open');
                if (els.error) {
                    els.error.classList.add('hidden');
                    els.error.innerHTML = '';
                }
            });
        }

        // Tutup Modal
        function closeModalIzin() {
            if (!els.modal) return;
            els.modal.classList.add('hidden');
            els.modal.classList.remove('flex');
            document.body.classList.remove('modal-open');
        }

        if (els.btnTutup) els.btnTutup.addEventListener('click', closeModalIzin);
        if (els.modal) {
            els.modal.addEventListener('click', function(e) {
                if (e.target === els.modal) closeModalIzin();
            });
        }

        // Submit Form Izin
        if (els.form) {
            els.form.addEventListener('submit', async function(e) {
                e.preventDefault();

                if (els.error) {
                    els.error.classList.add('hidden');
                    els.error.innerHTML = '';
                }

                // Loading State
                if (els.btnKirim) {
                    els.btnKirim.disabled = true;
                    els.btnKirim.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Mengirim...';
                }

                try {
                    var formData = new FormData(els.form);

                    var res = await fetch('{{ route("guru.absensi.ajukan-izin") }}', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: formData,
                        credentials: 'same-origin',
                    });

                    var result = await res.json();

                    // Tanggani Error dari Validation Request Laravel (Status 422)
                    if (!res.ok) {
                        if (res.status === 422 && result.errors) {
                            var errorMessages = Object.values(result.errors).flat().join('<br>');
                            throw new Error(errorMessages);
                        }
                        throw new Error(result.message || 'Gagal mengirim pengajuan.');
                    }

                    // Success Handling
                    if (els.btnKirim) {
                        els.btnKirim.innerHTML = '<i class="fa-solid fa-check"></i> Berhasil Dikirim';
                        els.btnKirim.className = 'w-full py-3.5 px-4 rounded-xl font-bold shadow-sm transition-all flex items-center justify-center gap-2 bg-emerald-500 text-white cursor-pointer text-sm';
                    }

                    setTimeout(function() {
                        closeModalIzin();
                        window.location.reload();
                    }, 1200);

                } catch (err) {
                    console.error('Izin Error:', err);
                    if (els.error) {
                        els.error.innerHTML = err.message || 'Terjadi kesalahan sistem.';
                        els.error.classList.remove('hidden');
                    }
                    if (els.btnKirim) {
                        els.btnKirim.disabled = false;
                        els.btnKirim.innerHTML = '<i class="fa-solid fa-paper-plane"></i> Kirim Pengajuan';
                    }
                }
            });
        }

        // Keyboard Shortcut (ESC)
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                if (els.modal && !els.modal.classList.contains('hidden')) {
                    closeModalIzin();
                }
            }
        });

    })();
</script>
@endsection