@extends('layouts.admin')

@section('title', 'Pengajuan Ijin')
@section('content')

    <div class="space-y-6">
        
        {{-- BREADCRUMB & FILTER SECTION --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-[#161d3f] p-6 rounded-2xl shadow-md border border-[#242b4d]">
            <div>
                <h1 class="text-2xl font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-clipboard-user text-[#7367f0]"></i>
                    Daftar Pengajuan Absensi
                </h1>
                <p class="text-sm text-[#626885] mt-1">Kelola dan proses persetujuan berkas absensi guru.</p>
            </div>
            
            <form action="{{ route('admin.absensi.pengajuan') }}" method="GET" class="flex items-center gap-2 bg-[#10163a] p-2 rounded-xl border border-[#242b4d]">
                <label for="status" class="text-xs font-semibold uppercase text-[#c2c6dc] px-2 flex items-center gap-1">
                    <i class="fa-solid fa-filter text-[#7367f0]"></i> Filter Status
                </label>
                <select name="status" id="status" onchange="this.form.submit()" class="rounded-lg border-[#242b4d] text-sm bg-[#161d3f] text-[#c2c6dc] p-2 focus:ring-2 focus:ring-[#7367f0] focus:border-[#7367f0] focus:outline-hidden cursor-pointer">
                    <option value="">Semua</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </form>
        </div>

        {{-- FLASH MESSAGES --}}
        @if(session('success'))
            <div class="flex items-center gap-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-4 py-3 rounded-xl text-sm shadow-sm">
                <i class="fa-solid fa-circle-check text-lg"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="flex items-center gap-3 bg-rose-500/10 border border-rose-500/20 text-rose-400 px-4 py-3 rounded-xl text-sm shadow-sm">
                <i class="fa-solid fa-circle-xmark text-lg"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        {{-- AREA TABEL UTAMA --}}
        <div class="bg-[#161d3f] rounded-2xl shadow-md border border-[#242b4d] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-[#c2c6dc]">
                    <thead>
                        <tr class="bg-[#10163a]/50 border-b border-[#242b4d] text-xs font-semibold tracking-wider text-[#c2c6dc] uppercase">
                            <th class="px-6 py-4"><i class="fa-solid fa-calendar-day mr-1 text-[#7367f0]"></i> Tanggal</th>
                            <th class="px-6 py-4"><i class="fa-solid fa-user-tie mr-1 text-[#7367f0]"></i> Nama Guru</th>
                            <th class="px-6 py-4"><i class="fa-solid fa-circle-info mr-1 text-[#7367f0]"></i> Keterangan</th>
                            <th class="px-6 py-4"><i class="fa-solid fa-toggle-on mr-1 text-[#7367f0]"></i> Status</th>
                            <th class="px-6 py-4 text-right"><i class="fa-solid fa-gear mr-1 text-[#7367f0]"></i> Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#242b4d] text-sm">
                        @forelse($pengajuan as $item)
                            <tr class="hover:bg-[#10163a]/40 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-white">
                                    {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-semibold text-white">{{ $item->guru->user->name ?? 'N/A' }}</div>
                                    <div class="text-xs text-[#626885] mt-0.5">NIP: {{ $item->guru->nip ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 max-w-xs truncate text-[#c2c6dc]">
                                    {{ $item->keterangan ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($item->status_persetujuan == 'pending')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                            <i class="fa-solid fa-clock-rotate-left text-[10px]"></i> Pending
                                        </span>
                                    @elseif($item->status_persetujuan == 'disetujui')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                            <i class="fa-solid fa-check text-[10px]"></i> Disetujui
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-rose-500/10 text-rose-400 border border-rose-500/20">
                                            <i class="fa-solid fa-xmark text-[10px]"></i> Ditolak
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button onclick="openDetailModal({{ $item->id }})" class="inline-flex items-center gap-1 text-[#7367f0] hover:text-white bg-[#7367f0]/10 hover:bg-[#7367f0] border border-[#7367f0]/20 px-3 py-1.5 rounded-xl transition-all cursor-pointer text-xs font-semibold">
                                        <i class="fa-solid fa-eye text-xs"></i> Detail / Aksi
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-[#626885]">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <i class="fa-regular fa-folder-open text-4xl mb-2 text-[#242b4d]"></i>
                                        <p class="text-sm">Tidak ada data pengajuan absensi ditemukan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($pengajuan->hasPages())
                <div class="px-6 py-4 bg-[#10163a]/50 border-t border-[#242b4d]">
                    {{ $pengajuan->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- MODAL AREA COMPONENT --}}
    <div id="detailModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto">
        <div class="fixed inset-0 bg-gray-950/80 backdrop-blur-xs transition-opacity" onclick="closeDetailModal()"></div>
        
        <div class="relative bg-[#161d3f] rounded-2xl max-w-lg w-full shadow-2xl overflow-hidden border border-[#242b4d] transform transition-all scale-95 opacity-0 duration-200" id="modalContainer">
            
            <div class="bg-[#10163a] px-6 py-4 border-b border-[#242b4d] flex items-center justify-between">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-circle-info text-[#7367f0]"></i> Detail Pengajuan Absensi
                </h3>
                <button onclick="closeDetailModal()" class="text-[#626885] hover:text-white cursor-pointer transition-colors">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <div class="px-6 py-4 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-xs font-bold uppercase text-[#626885] flex items-center gap-1"><i class="fa-solid fa-user"></i> Nama Guru</span>
                        <p id="modal-nama" class="text-sm font-semibold text-white mt-0.5">-</p>
                    </div>
                    <div>
                        <span class="text-xs font-bold uppercase text-[#626885] flex items-center gap-1"><i class="fa-solid fa-calendar"></i> Tanggal</span>
                        <p id="modal-tanggal" class="text-sm font-semibold text-white mt-0.5">-</p>
                    </div>
                </div>

                <div>
                    <span class="text-xs font-bold uppercase text-[#626885] flex items-center gap-1"><i class="fa-solid fa-align-left"></i> Keterangan Guru</span>
                    <p id="modal-keterangan" class="text-sm bg-[#10163a] border border-[#242b4d] p-3 rounded-xl mt-1 text-[#c2c6dc] whitespace-pre-line">-</p>
                </div>

                <div id="reject-form-area" class="hidden border-t border-dashed border-[#242b4d] pt-4">
                    <form id="form-reject" method="POST" class="space-y-3">
                        @csrf
                        <div>
                            <label for="catatan_admin" class="text-xs font-bold uppercase text-rose-400 flex items-center gap-1 mb-1">
                                <i class="fa-solid fa-comment-dots"></i> Alasan/Catatan Penolakan
                            </label>
                            <textarea name="catatan_admin" id="catatan_admin" rows="3" placeholder="Masukkan alasan penolakan di sini..." class="w-full text-sm rounded-xl border-[#242b4d] bg-[#10163a] focus:ring-2 focus:ring-rose-500 focus:border-rose-500 p-3 focus:outline-hidden border text-[#c2c6dc]"></textarea>
                        </div>
                        <div class="flex justify-end gap-2">
                            <button type="button" onclick="toggleRejectForm(false)" class="px-3 py-2 text-xs font-semibold text-[#c2c6dc] bg-[#10163a] hover:bg-[#242b4d] rounded-xl border border-[#242b4d] cursor-pointer transition-colors">
                                <i class="fa-solid fa-arrow-left"></i> Batal
                            </button>
                            <button type="submit" class="px-4 py-2 text-xs font-semibold bg-rose-500 hover:bg-rose-600 text-white rounded-xl flex items-center gap-1 shadow-sm cursor-pointer transition-colors">
                                <i class="fa-solid fa-circle-xmark"></i> Konfirmasi Tolak
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-[#10163a] px-6 py-4 border-t border-[#242b4d] flex flex-col sm:flex-row justify-between gap-2" id="modal-actions">
                <div class="flex gap-2 w-full sm:w-auto">
                    <form id="form-approve" method="POST" class="w-full sm:w-auto">
                        @csrf
                        <button type="submit" class="w-full sm:w-auto px-4 py-2.5 text-sm font-semibold bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl flex items-center justify-center gap-1.5 shadow-sm transition-all cursor-pointer">
                            <i class="fa-solid fa-circle-check"></i> Setujui
                        </button>
                    </form>
                    
                    <button type="button" onclick="toggleRejectForm(true)" class="w-full sm:w-auto px-4 py-2.5 text-sm font-semibold bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/20 rounded-xl flex items-center justify-center gap-1.5 transition-all cursor-pointer">
                        <i class="fa-solid fa-ban"></i> Tolak Pengajuan
                    </button>
                </div>
                
                <button onclick="closeDetailModal()" class="w-full sm:w-auto px-4 py-2.5 text-sm font-semibold bg-[#161d3f] border border-[#242b4d] text-[#c2c6dc] hover:bg-[#242b4d] rounded-xl transition-all cursor-pointer text-center">
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

                    const actionsDiv = document.getElementById('modal-actions');
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