@extends('layouts.admin')

@section('title', 'Pengajuan Ijin')
@section('content')

    <div class="space-y-6 font-sans">
        
        {{-- BREADCRUMB & FILTER SECTION --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white/80 backdrop-blur-md p-4  shadow-xs border-l-4 border-green-600">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight flex items-center gap-2.5">
                    DAFTAR PENGAJUAN ABSENSI GURU
                </h1>
                <p class="text-sm text-slate-500 mt-1">Kelola dan proses persetujuan berkas izin/sakit guru secara *real-time*.</p>
            </div>
            
            <form action="{{ route('admin.absensi.pengajuan') }}" method="GET" class="flex items-center gap-2 bg-slate-50/80 p-1.5 border border-slate-200/60">
                <label for="status" class="text-xs font-semibold uppercase text-slate-500 px-3 flex items-center gap-1.5">
                    <i class="fa-solid fa-filter text-indigo-500"></i> Filter Status
                </label>
                <select name="status" id="status" onchange="this.form.submit()" class=" border-slate-200 text-sm bg-white text-slate-700 py-1.5 px-3 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none cursor-pointer shadow-2xs font-medium transition-all">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </form>
        </div>

        {{-- FLASH MESSAGES --}}
        @if(session('success'))
            <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200/60 text-emerald-700 px-4 py-3.5 text-sm shadow-2xs animate-fade-in">
                <i class="fa-solid fa-circle-check text-lg text-emerald-500"></i>
                <div class="font-medium">{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="flex items-center gap-3 bg-rose-50 border border-rose-200/60 text-rose-700 px-4 py-3.5  text-sm shadow-2xs animate-fade-in">
                <i class="fa-solid fa-circle-xmark text-lg text-rose-500"></i>
                <div class="font-medium">{{ session('error') }}</div>
            </div>
        @endif

        {{-- AREA TABEL UTAMA --}}
        <div class="bg-white shadow-xs border border-slate-200/80 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-slate-600">
                    <thead>
                        <tr class="bg-green-600 border-b border-slate-200/80 text-sm font-bold tracking-wider text-white uppercase">
                            <th class="px-6 py-4"><i class="fa-solid fa-calendar-day mr-1.5 text-white"></i> Tanggal</th>
                            <th class="px-6 py-4"><i class="fa-solid fa-user-tie mr-1.5 text-white"></i> Nama Guru</th>
                            <th class="px-6 py-4"><i class="fa-solid fa-circle-info mr-1.5 text-white"></i> Keterangan</th>
                            <th class="px-6 py-4"><i class="fa-solid fa-toggle-on mr-1.5 text-white"></i> Status</th>
                            <th class="px-6 py-4 text-right"><i class="fa-solid fa-gear mr-1.5 text-white"></i> Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($pengajuan as $item)
                            <tr class="hover:bg-indigo-50/30 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap font-semibold text-slate-800">
                                    {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-semibold text-slate-800">{{ $item->guru->user->name ?? 'N/A' }}</div>
                                    <div class="text-xs text-slate-400 mt-0.5 font-mono">NIP: {{ $item->guru->nip ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 max-w-xs truncate text-slate-600">
                                    {{ $item->keterangan ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($item->status_persetujuan == 'pending')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200/60 shadow-2xs">
                                            <span class="w-1.5 h-1.5  bg-amber-500 animate-pulse"></span>
                                            Pending
                                        </span>
                                    @elseif($item->status_persetujuan == 'disetujui')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1  text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60 shadow-2xs">
                                            <i class="fa-solid fa-check text-[10px]"></i> Disetujui
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1  text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200/60 shadow-2xs">
                                            <i class="fa-solid fa-xmark text-[10px]"></i> Ditolak
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button onclick="openDetailModal({{ $item->id }})" class="inline-flex items-center gap-1.5 text-indigo-600 hover:text-white bg-indigo-50 hover:bg-indigo-600 border border-indigo-200/60 hover:border-indigo-600 px-3.5 py-1.5 transition-all duration-200 shadow-2xs cursor-pointer text-xs font-semibold">
                                        <i class="fa-solid fa-eye"></i> Detail / Aksi
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center gap-3">
                                        <div class="p-4 bg-slate-50 text-slate-300">
                                            <i class="fa-regular fa-folder-open text-4xl"></i>
                                        </div>
                                        <p class="text-sm font-medium text-slate-500">Tidak ada data pengajuan absensi ditemukan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($pengajuan->hasPages())
                <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100">
                    {{ $pengajuan->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- MODAL AREA COMPONENT --}}
    <div id="detailModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity duration-300" onclick="closeDetailModal()"></div>
        
        <div class="relative bg-white  max-w-lg w-full shadow-2xl border border-slate-100 overflow-hidden transform transition-all scale-95 opacity-0 duration-200" id="modalContainer">
            
            <div class="bg-slate-50/80 px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                    <span class="p-1.5 bg-indigo-50 text-indigo-600">
                        <i class="fa-solid fa-circle-info"></i>
                    </span>
                    Detail Pengajuan Absensi
                </h3>
                <button onclick="closeDetailModal()" class="w-8 h-8 bg-white hover:bg-slate-100 border border-slate-200/60 text-slate-400 hover:text-slate-600 flex items-center justify-center cursor-pointer transition-colors shadow-2xs">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="px-6 py-5 space-y-4">
                <div class="grid grid-cols-2 gap-4 bg-slate-50/60 p-4  border border-slate-100">
                    <div>
                        <span class="text-xs font-bold uppercase text-slate-400 flex items-center gap-1.5"><i class="fa-solid fa-user"></i> Nama Guru</span>
                        <p id="modal-nama" class="text-sm font-bold text-slate-800 mt-1">-</p>
                    </div>
                    <div>
                        <span class="text-xs font-bold uppercase text-slate-400 flex items-center gap-1.5"><i class="fa-solid fa-calendar"></i> Tanggal</span>
                        <p id="modal-tanggal" class="text-sm font-bold text-slate-800 mt-1">-</p>
                    </div>
                </div>

                <div>
                    <span class="text-xs font-bold uppercase text-slate-400 flex items-center gap-1.5"><i class="fa-solid fa-align-left"></i> Keterangan Guru</span>
                    <p id="modal-keterangan" class="text-sm bg-slate-50 border border-slate-200/60 p-3.5 mt-1.5 text-slate-700 whitespace-pre-line leading-relaxed">-</p>
                </div>

                <div id="reject-form-area" class="hidden border-t border-dashed border-slate-200 pt-4">
                    <form id="form-reject" method="POST" class="space-y-3">
                        @csrf
                        <div>
                            <label for="catatan_admin" class="text-xs font-bold uppercase text-rose-600 flex items-center gap-1.5 mb-1.5">
                                <i class="fa-solid fa-comment-dots"></i> Alasan / Catatan Penolakan
                            </label>
                            <textarea name="catatan_admin" id="catatan_admin" rows="3" placeholder="Masukkan alasan penolakan di sini..." class="w-full text-sm  border-slate-200 bg-white focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 p-3 focus:outline-none border text-slate-700 shadow-2xs placeholder:text-slate-400"></textarea>
                        </div>
                        <div class="flex justify-end gap-2">
                            <button type="button" onclick="toggleRejectForm(false)" class="px-4 py-2 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200  cursor-pointer transition-colors">
                                Batal
                            </button>
                            <button type="submit" class="px-4 py-2 text-xs font-semibold bg-rose-600 hover:bg-rose-700 text-white  flex items-center gap-1.5 shadow-sm cursor-pointer transition-colors">
                                <i class="fa-solid fa-circle-xmark"></i> Konfirmasi Tolak
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-slate-50/80 px-6 py-4 border-t border-slate-100 flex flex-col sm:flex-row justify-between gap-2" id="modal-actions">
                <div class="flex gap-2 w-full sm:w-auto">
                    <form id="form-approve" method="POST" class="w-full sm:w-auto">
                        @csrf
                        <button type="submit" class="w-full sm:w-auto px-5 py-2.5 text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white  flex items-center justify-center gap-2 shadow-sm transition-all cursor-pointer">
                            <i class="fa-solid fa-circle-check"></i> Setujui
                        </button>
                    </form>
                    
                    <button type="button" onclick="toggleRejectForm(true)" class="w-full sm:w-auto px-5 py-2.5 text-xs font-bold bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200/60  flex items-center justify-center gap-2 transition-all cursor-pointer">
                        <i class="fa-solid fa-ban"></i> Tolak Pengajuan
                    </button>
                </div>
                
                <button onclick="closeDetailModal()" class="w-full sm:w-auto px-5 py-2.5 text-xs font-semibold bg-white border border-slate-200/80 text-slate-600 hover:bg-slate-100  transition-all cursor-pointer text-center shadow-2xs">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById('detailModal');
        const container = document.getElementById('modalContainer');

        function openDetailModal(id) {
            toggleRejectForm(false);
            
            modal.classList.remove('hidden');
            setTimeout(() => {
                container.classList.remove('scale-95', 'opacity-0');
                container.classList.add('scale-100', 'opacity-100');
            }, 50);

            fetch(`/admin/absensi/${id}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('modal-nama').textContent = data.guru?.user?.name || 'N/A';
                    document.getElementById('modal-tanggal').textContent = data.tanggal;
                    document.getElementById('modal-keterangan').textContent = data.keterangan || '-';

                    document.getElementById('form-approve').action = `/admin/absensi/${id}/approve`;
                    document.getElementById('form-reject').action = `/admin/absensi/${id}/reject`;

                    if(data.status_persetujuan !== 'pending') {
                        document.getElementById('form-approve').classList.add('hidden');
                        document.getElementById('modal-actions').firstElementChild.classList.add('hidden');
                    } else {
                        document.getElementById('form-approve').classList.remove('hidden');
                        document.getElementById('modal-actions').firstElementChild.classList.remove('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error fetching details:', error);
                    alert('Gagal memuat data detail.');
                });
        }

        function closeDetailModal() {
            container.classList.remove('scale-100', 'opacity-100');
            container.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 200);
        }

        function toggleRejectForm(show) {
            const formArea = document.getElementById('reject-form-area');
            const mainActionButtons = document.getElementById('form-approve').parentElement;

            if(show) {
                formArea.classList.remove('hidden');
                mainActionButtons.classList.add('hidden');
            } else {
                formArea.classList.add('hidden');
                mainActionButtons.classList.remove('hidden');
                document.getElementById('catatan_admin').value = '';
            }
        }
    </script>
@endsection