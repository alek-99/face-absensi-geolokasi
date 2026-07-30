@extends('layouts.guru')

@section('title', 'Riwayat Absensi')
@section('page_title', 'Riwayat Absensi')

@section('content')
    <div class="max-w-md mx-auto px-2 py-4 space-y-5 animate-fade-in">

        {{-- Tombol Kembali --}}
        <div class="flex items-center justify-between px-1">
            <a href="{{ route('guru.absensi.index') }}"
                class="inline-flex items-center text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 transition-colors group">
                <i class="fa-solid fa-arrow-left mr-1.5 group-hover:-translate-x-0.5 transition-transform"></i>
                Kembali ke Absensi Hari Ini
            </a>
        </div>

        {{-- Header Section --}}
        <div class="bg-white dark:bg-[#161615] p-5 rounded-2xl border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-sm flex flex-col gap-4">
            <div>
                <span class="text-[10px] font-bold text-amber-600 dark:text-amber-500 uppercase tracking-wider block mb-1">
                    Presensi Mandiri
                </span>
                <h2 class="text-xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">
                    Riwayat Absensi
                </h2>
                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A] mt-0.5">
                    Rekam jejak kehadiran lengkap Anda pada sistem presensi.
                </p>
            </div>

            {{-- Tombol Buka Modal Cetak Excel --}}
            <button type="button" id="btn-buka-excel"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 active:scale-[0.98] text-white text-xs font-bold transition shadow-sm cursor-pointer">
                <i class="fa-solid fa-file-excel text-sm"></i> Cetak Excel
            </button>
        </div>

        {{-- Form Filter Halaman --}}
        <form method="GET" action="{{ route('guru.absensi.riwayat') }}"
            class="bg-white dark:bg-[#161615] p-4 rounded-2xl border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-sm space-y-3">
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-[10px] font-bold text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider block mb-1">
                        Bulan
                    </label>
                    <select name="bulan"
                        class="w-full rounded-xl bg-slate-50 dark:bg-zinc-900 border-[#e3e3e0] dark:border-[#3E3E3A] text-xs focus:ring-indigo-500 focus:border-indigo-500 text-[#1b1b18] dark:text-[#EDEDEC] font-medium py-2">
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ sprintf('%02d', $m) }}"
                                {{ request('bulan', now()->format('m')) == sprintf('%02d', $m) ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label class="text-[10px] font-bold text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider block mb-1">
                        Tahun
                    </label>
                    <select name="tahun"
                        class="w-full rounded-xl bg-slate-50 dark:bg-zinc-900 border-[#e3e3e0] dark:border-[#3E3E3A] text-xs focus:ring-indigo-500 focus:border-indigo-500 text-[#1b1b18] dark:text-[#EDEDEC] font-medium py-2">
                        @for ($y = now()->year; $y >= now()->year - 3; $y--)
                            <option value="{{ $y }}" {{ request('tahun', now()->year) == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                </div>
            </div>

            <button type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-700 active:scale-[0.98] text-white text-xs font-bold py-2.5 rounded-xl transition inline-flex items-center justify-center gap-1.5 cursor-pointer shadow-sm">
                <i class="fa-solid fa-magnifying-glass"></i> Terapkan Filter
            </button>
        </form>

        {{-- List Card Container --}}
        <div class="space-y-3">
            @forelse($riwayat as $row)
                @php
                    $status = strtolower($row->status);

                    switch ($status) {
                        case 'hadir':
                            $class = 'bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 border-emerald-200 dark:border-emerald-900/40';
                            break;
                        case 'terlambat':
                            $class = 'bg-amber-50 dark:bg-amber-950/20 text-amber-700 dark:text-amber-400 border-amber-200 dark:border-amber-900/40';
                            break;
                        case 'izin':
                            $class = 'bg-blue-50 dark:bg-blue-950/20 text-blue-700 dark:text-blue-400 border-blue-200 dark:border-blue-900/40';
                            break;
                        case 'sakit':
                            $class = 'bg-indigo-50 dark:bg-indigo-950/20 text-indigo-700 dark:text-indigo-400 border-indigo-200 dark:border-indigo-900/40';
                            break;
                        case 'cuti':
                            $class = 'bg-purple-50 dark:bg-purple-950/20 text-purple-700 dark:text-purple-400 border-purple-200 dark:border-purple-900/40';
                            break;
                        case 'tugas_luar':
                            $class = 'bg-cyan-50 dark:bg-cyan-950/20 text-cyan-700 dark:text-cyan-400 border-cyan-200 dark:border-cyan-900/40';
                            break;
                        case 'alpha':
                            $class = 'bg-red-50 dark:bg-red-950/20 text-red-700 dark:text-red-400 border-red-200 dark:border-red-900/40';
                            break;
                        default:
                            $class = 'bg-gray-50 dark:bg-neutral-900 text-gray-700 dark:text-neutral-400 border-gray-200 dark:border-neutral-800';
                    }

                    $statusText = ucwords(str_replace('_', ' ', $row->status));

                    $approval = strtolower($row->status_persetujuan ?? 'pending');
                    switch ($approval) {
                        case 'disetujui':
                            $approvalClass = 'bg-emerald-100 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400';
                            break;
                        case 'ditolak':
                            $approvalClass = 'bg-rose-100 dark:bg-rose-950/40 text-rose-700 dark:text-rose-400';
                            break;
                        default:
                            $approvalClass = 'bg-amber-100 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400';
                    }
                    $approvalText = ucfirst($approval);
                    $isPermit = in_array($status, ['izin', 'sakit', 'cuti', 'tugas_luar']);
                @endphp

                {{-- Mobile Row Card --}}
                <div class="bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] p-4 rounded-xl shadow-xs flex items-center justify-between transition-all active:scale-[0.99] cursor-pointer btn-detail"
                    data-tanggal="{{ \Carbon\Carbon::parse($row->tanggal)->translatedFormat('d F Y') }}"
                    data-status="{{ $statusText }}" 
                    data-statusclass="{{ $class }}"
                    data-approval="{{ $approvalText }}" 
                    data-approvalclass="{{ $approvalClass }}"
                    data-masuk="{{ $isPermit ? 'Tidak Absen' : ($row->jam_masuk ? \Carbon\Carbon::parse($row->jam_masuk)->format('H:i') : '-') }}"
                    data-pulang="{{ $isPermit ? 'Tidak Absen' : ($row->jam_pulang ? \Carbon\Carbon::parse($row->jam_pulang)->format('H:i') : '-') }}"
                    data-keterangan="{{ $row->keterangan ?? '-' }}"
                    data-bukti="{{ $row->bukti_dokumen ? Storage::url($row->bukti_dokumen) : '' }}">

                    <div class="space-y-1.5">
                        <div class="text-xs font-bold text-[#1b1b18] dark:text-[#EDEDEC]">
                            {{ \Carbon\Carbon::parse($row->tanggal)->translatedFormat('l, d M Y') }}
                        </div>
                        <div class="text-[11px] font-mono text-[#706f6c] dark:text-[#A1A09A] flex items-center gap-3">
                            <span class="flex items-center gap-1">
                                <i class="fa-solid fa-right-to-bracket text-emerald-500 text-[10px]"></i>
                                {{ $isPermit ? '--:--' : ($row->jam_masuk ? \Carbon\Carbon::parse($row->jam_masuk)->format('H:i') : '--:--') }}
                            </span>
                            <span class="flex items-center gap-1">
                                <i class="fa-solid fa-right-from-bracket text-rose-500 text-[10px]"></i>
                                {{ $isPermit ? '--:--' : ($row->jam_pulang ? \Carbon\Carbon::parse($row->jam_pulang)->format('H:i') : '--:--') }}
                            </span>
                        </div>
                        <div class="pt-0.5">
                            <span class="text-[9px] font-bold px-2 py-0.5 rounded-full {{ $approvalClass }}">
                                <i class="fa-solid fa-signature text-[8px] mr-1"></i>{{ $approvalText }}
                            </span>
                        </div>
                    </div>

                    <div class="flex flex-col items-end gap-2">
                        <span class="text-[10px] font-extrabold px-2.5 py-1 rounded-lg border {{ $class }}">
                            {{ $statusText }}
                        </span>
                        <span class="text-gray-300 dark:text-zinc-600 text-xs">
                            <i class="fa-solid fa-chevron-right"></i>
                        </span>
                    </div>
                </div>
            @empty
                <div class="bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] py-10 rounded-2xl text-center text-gray-400 dark:text-zinc-500">
                    <i class="fa-solid fa-calendar-xmark text-3xl mb-2 block"></i>
                    <p class="text-xs font-medium">Belum ada riwayat absensi pada periode ini.</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if (method_exists($riwayat, 'links'))
            <div class="pt-2 px-1">
                {{ $riwayat->links() }}
            </div>
        @endif
    </div>

    {{-- MODAL CETAK EXCEL --}}
    <div id="modal-excel" class="hidden fixed inset-0 bg-black/50 z-50 items-center justify-center p-4 backdrop-blur-xs">
        <div class="bg-white dark:bg-[#161615] rounded-2xl shadow-xl w-full max-w-sm overflow-hidden border border-gray-100 dark:border-[#3E3E3A]">
            <div class="flex justify-between items-center border-b border-[#e3e3e0] dark:border-[#3E3E3A] p-4 bg-gray-50 dark:bg-zinc-900/50">
                <div class="flex items-center space-x-2 text-emerald-600 dark:text-emerald-400">
                    <i class="fa-solid fa-file-excel text-lg"></i>
                    <h3 class="text-sm font-bold text-gray-800 dark:text-[#EDEDEC]">Cetak Excel Presensi</h3>
                </div>
                <button id="close-excel" type="button"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-zinc-300 transition-colors cursor-pointer p-1">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form action="{{ route('guru.absensi.cetak-excel') }}" method="GET" target="_blank" id="form-cetak-excel">
                <div class="p-5 space-y-4">
                    <p class="text-[11px] text-[#706f6c] dark:text-[#A1A09A] leading-relaxed">
                        Pilih target bulan dan tahun rekapitulasi presensi yang ingin Anda ekspor ke berkas Excel.
                    </p>

                    <div>
                        <label class="text-[10px] font-bold text-[#706f6c] dark:text-[#A1A09A] uppercase block mb-1">
                            Bulan Laporan
                        </label>
                        <select name="bulan" id="excel-bulan"
                            class="w-full rounded-xl bg-slate-50 dark:bg-zinc-900 border-[#e3e3e0] dark:border-[#3E3E3A] text-xs focus:ring-emerald-500 focus:border-emerald-500 text-[#1b1b18] dark:text-[#EDEDEC] py-2">
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ sprintf('%02d', $m) }}"
                                    {{ request('bulan', now()->format('m')) == sprintf('%02d', $m) ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label class="text-[10px] font-bold text-[#706f6c] dark:text-[#A1A09A] uppercase block mb-1">
                            Tahun Laporan
                        </label>
                        <select name="tahun" id="excel-tahun"
                            class="w-full rounded-xl bg-slate-50 dark:bg-zinc-900 border-[#e3e3e0] dark:border-[#3E3E3A] text-xs focus:ring-emerald-500 focus:border-emerald-500 text-[#1b1b18] dark:text-[#EDEDEC] py-2">
                            @for ($y = now()->year; $y >= now()->year - 3; $y--)
                                <option value="{{ $y }}" {{ request('tahun', now()->year) == $y ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="bg-gray-50 dark:bg-zinc-900/50 p-4 flex items-center justify-end gap-2 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <button type="button" id="btn-batal-excel"
                        class="px-3 py-2 text-xs font-bold text-gray-500 hover:bg-gray-200 dark:hover:bg-zinc-800 rounded-xl transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" id="btn-submit-excel"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2 rounded-xl shadow-xs transition inline-flex items-center gap-1.5 cursor-pointer">
                        <i class="fa-solid fa-download"></i> Unduh Excel
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL DETAIL DATA ABSEN --}}
    <div id="modal-detail" class="hidden fixed inset-0 bg-black/50 z-50 items-center justify-center p-4 backdrop-blur-xs">
        <div class="bg-white dark:bg-[#161615] rounded-2xl shadow-xl w-full max-w-sm overflow-hidden border border-gray-100 dark:border-[#3E3E3A]">
            <div class="flex justify-between items-center border-b border-[#e3e3e0] dark:border-[#3E3E3A] p-4 bg-gray-50 dark:bg-zinc-900/50">
                <h3 class="text-sm font-bold text-gray-800 dark:text-[#EDEDEC] flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-info text-indigo-500"></i> Detail Presensi
                </h3>
                <button id="close-detail"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-zinc-300 transition-colors cursor-pointer p-1">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <div class="p-5 space-y-3.5 max-h-[65vh] overflow-y-auto">
                <div class="grid grid-cols-2 gap-4 border-b border-gray-100 dark:border-zinc-900 pb-3">
                    <div>
                        <label class="text-[10px] font-bold text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider block mb-0.5">Tanggal</label>
                        <p id="detail-tanggal" class="text-xs text-[#1b1b18] dark:text-[#EDEDEC] font-semibold"></p>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider block mb-0.5">Persetujuan</label>
                        <div class="pt-0.5">
                            <span id="detail-approval" class="text-[9px] font-bold px-2 py-0.5 rounded-full"></span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 border-b border-gray-100 dark:border-zinc-900 pb-3">
                    <div>
                        <label class="text-[10px] font-bold text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider block mb-0.5">Jam Masuk</label>
                        <p id="detail-masuk" class="text-xs font-mono font-bold text-slate-700 dark:text-zinc-300"></p>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider block mb-0.5">Jam Pulang</label>
                        <p id="detail-pulang" class="text-xs font-mono font-bold text-slate-700 dark:text-zinc-300"></p>
                    </div>
                </div>

                <div>
                    <label class="text-[10px] font-bold text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider block mb-0.5">Status Kehadiran</label>
                    <div class="pt-0.5">
                        <span id="detail-status" class="text-[10px] font-extrabold px-2.5 py-0.5 rounded-lg border"></span>
                    </div>
                </div>

                <div>
                    <label class="text-[10px] font-bold text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider block mb-0.5">Keterangan / Alasan</label>
                    <p id="detail-keterangan" class="text-xs text-slate-600 dark:text-zinc-400 bg-slate-50 dark:bg-zinc-900/50 p-2.5 rounded-xl border border-gray-100 dark:border-zinc-900/60 leading-relaxed"></p>
                </div>

                <div id="bukti-area" class="pt-1.5 hidden">
                    <label class="text-[10px] font-bold text-[#706f6c] dark:text-[#A1A09A] uppercase tracking-wider block mb-1">Berkas Lampiran</label>
                    <a id="detail-bukti" target="_blank"
                        class="inline-flex w-full items-center justify-center p-2.5 bg-indigo-50 dark:bg-indigo-950/30 text-indigo-600 dark:text-indigo-400 text-xs rounded-xl font-semibold border border-indigo-100/70 dark:border-indigo-900/40 hover:underline">
                        <i class="fa-solid fa-file-arrow-down mr-1.5"></i> Lihat Dokumen Lampiran
                    </a>
                </div>
            </div>

            <div class="bg-gray-50 dark:bg-zinc-900/50 p-3 flex justify-end border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                <button type="button" id="btn-tutup-detail"
                    class="w-full py-2 bg-slate-200 dark:bg-zinc-800 text-[#1b1b18] dark:text-[#EDEDEC] text-xs font-bold rounded-xl transition cursor-pointer">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- MODAL DETAIL ---
            const modalDetail = document.getElementById('modal-detail');
            const closeDetailBtn = document.getElementById('close-detail');
            const btnTutupDetail = document.getElementById('btn-tutup-detail');

            document.querySelectorAll('.btn-detail').forEach(card => {
                card.onclick = function() {
                    document.getElementById('detail-tanggal').innerText = this.dataset.tanggal;
                    document.getElementById('detail-keterangan').innerText = this.dataset.keterangan || '-';
                    document.getElementById('detail-masuk').innerText = this.dataset.masuk || '-';
                    document.getElementById('detail-pulang').innerText = this.dataset.pulang || '-';

                    const elStatus = document.getElementById('detail-status');
                    elStatus.innerText = this.dataset.status;
                    elStatus.className = "text-[10px] font-extrabold px-2.5 py-0.5 rounded-lg border " + this.dataset.statusclass;

                    const elApproval = document.getElementById('detail-approval');
                    elApproval.innerText = this.dataset.approval;
                    elApproval.className = "text-[9px] font-bold px-2 py-0.5 rounded-full " + this.dataset.approvalclass;

                    const buktiArea = document.getElementById('bukti-area');
                    const detailBukti = document.getElementById('detail-bukti');

                    if (this.dataset.bukti && this.dataset.bukti.trim() !== '') {
                        buktiArea.classList.remove('hidden');
                        detailBukti.href = this.dataset.bukti;
                    } else {
                        buktiArea.classList.add('hidden');
                        detailBukti.removeAttribute('href');
                    }

                    modalDetail.classList.remove('hidden');
                    modalDetail.classList.add('flex');
                }
            });

            const tutupDetail = () => {
                if (modalDetail) {
                    modalDetail.classList.add('hidden');
                    modalDetail.classList.remove('flex');
                }
            };

            if (closeDetailBtn) closeDetailBtn.onclick = tutupDetail;
            if (btnTutupDetail) btnTutupDetail.onclick = tutupDetail;

            // --- MODAL CETAK EXCEL ---
            const modalExcel = document.getElementById('modal-excel');
            const btnBukaExcel = document.getElementById('btn-buka-excel');
            const closeExcel = document.getElementById('close-excel');
            const btnBatalExcel = document.getElementById('btn-batal-excel');
            const formExcel = document.getElementById('form-cetak-excel');

            if (btnBukaExcel) {
                btnBukaExcel.onclick = function() {
                    modalExcel.classList.remove('hidden');
                    modalExcel.classList.add('flex');
                };
            }

            const tutupModalExcel = () => {
                if (modalExcel) {
                    modalExcel.classList.add('hidden');
                    modalExcel.classList.remove('flex');
                }
            };

            if (closeExcel) closeExcel.onclick = tutupModalExcel;
            if (btnBatalExcel) btnBatalExcel.onclick = tutupModalExcel;

            if (formExcel) {
                formExcel.onsubmit = function() {
                    setTimeout(tutupModalExcel, 500);
                };
            }

            // --- OVERLAY CLICK CLOSE ---
            window.onclick = function(e) {
                if (e.target === modalDetail) tutupDetail();
                if (e.target === modalExcel) tutupModalExcel();
            };
        });
    </script>
@endsection