@extends('layouts.admin')
@section('title', 'Jadwal Mengajar')

@section('content')
    <div class="space-y-8">

        {{-- Header Section --}}
        <div
            class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 bg-[#161d3f] p-6 rounded-2xl shadow-md border border-[#242b4d]">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-white flex items-center gap-2.5">
                    <i class="fas fa-calendar-alt text-[#7367f0]"></i> Daftar Jadwal Pelajaran
                </h1>
                <p class="text-xs sm:text-sm text-[#626885] mt-1">Kelola waktu, kelas, dan pengajar aktif.</p>
            </div>

            <div class="flex flex-col gap-5">
                 <a href="{{ route('admin.jadwal.pdf') }}" class="text-white rounded-xl hover:bg-green-900 bg-green-800 p-2" target="_blank">
                <i class="fa fa-print"></i> Cetak Jadwal
            </a>
            <a href="{{ route('admin.jadwal.create') }}"
                class="w-full sm:w-auto bg-[#7367f0] hover:bg-[#5b50d6] text-white font-semibold py-2.5 px-5 rounded-xl shadow transition duration-200 text-center text-xs flex items-center justify-center gap-2">
                <i class="fas fa-plus text-[10px]"></i> Tambah Jadwal
            </a>
            </div>
            
           
        </div>

        {{-- Alert Notifications --}}
        @if (session('success') || session('error'))
            <div
                class="{{ session('success') ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-400' : 'bg-rose-500/10 border-rose-500/20 text-rose-400' }} border px-4 py-3 rounded-xl flex items-start gap-3 text-sm transition">
                <i
                    class="fas {{ session('success') ? 'fa-check-circle' : 'fa-exclamation-circle' }} mt-0.5 text-base flex-shrink-0"></i>
                <span class="font-semibold">{{ session('success') ?? session('error') }}</span>
            </div>
        @endif

        {{-- START: Section Statistik Pelajaran Aktif --}}
        <div>
            <h2 class="text-xs font-bold uppercase tracking-wider text-[#626885] mb-4 flex items-center gap-2">
                <i class="fas fa-chart-pie text-[#7367f0]"></i> Statistik Jadwal Aktif per Hari
            </h2>

            {{-- Grid Card Responsif --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                @forelse($statitsik as $stat)
                    <div
                        class="bg-[#161d3f] p-4 rounded-2xl shadow-md border border-[#242b4d] flex flex-col justify-between hover:border-[#7367f0]/40 transition">
                        <div>
                            <span
                                class="block text-sm font-bold text-white border-b border-[#242b4d] pb-2 mb-3 flex items-center justify-between">
                                {{ $stat->hari }}
                                <span
                                    class="bg-[#7367f0]/10 text-[#7367f0] text-[10px] px-2 py-0.5 rounded-lg font-semibold border border-[#7367f0]/20">
                                    {{ $stat->jumlah_jadwal }} Sesi
                                </span>
                            </span>
                        </div>
                        <div class="space-y-2 text-xs text-[#c2c6dc]">
                            <div class="flex justify-between items-center">
                                <span class="text-[#626885]">Guru:</span>
                                <span class="font-semibold text-white">{{ $stat->jumlah_guru }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-[#626885]">Mapel:</span>
                                <span class="font-semibold text-white">{{ $stat->jumlah_mapel }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-[#626885]">Kelas:</span>
                                <span class="font-semibold text-white">{{ $stat->jumlah_kelas }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-[#626885]">Ruang:</span>
                                <span class="font-semibold text-white">{{ $stat->jumlah_ruangan }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div
                        class="col-span-full bg-[#161d3f] border border-dashed border-[#242b4d] text-[#626885] text-center py-6 rounded-2xl text-sm">
                        Belum ada statistik data aktif yang terekam.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Data Table --}}
        <div class="bg-[#161d3f] rounded-2xl shadow-md border border-[#242b4d] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[700px] text-sm text-[#c2c6dc] whitespace-nowrap">
                    <thead>
                        <tr
                            class="bg-[#10163a]/50 border-b border-[#242b4d] text-[#626885] text-xs font-semibold uppercase tracking-wider">
                            <th class="px-6 py-4">Hari / Waktu</th>
                            <th class="px-6 py-4">Mata Pelajaran</th>
                            <th class="px-6 py-4">Guru</th>
                            <th class="px-6 py-4">Kelas / Ruangan</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#242b4d]">
                        @forelse($jadwals as $jadwal)
                            <tr class="hover:bg-[#10163a]/40 transition">
                                <td class="px-6 py-4">
                                    <span class="font-bold text-white block text-base">{{ $jadwal->hari }}</span>
                                    <span class="text-xs text-[#626885] inline-flex items-center gap-1 mt-1">
                                        <i class="far fa-clock text-[#7367f0]"></i>
                                        {{ \Carbon\Carbon::parse($jadwal->jam_masuk)->format('H:i') }} -
                                        {{ \Carbon\Carbon::parse($jadwal->jam_pulang)->format('H:i') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-semibold text-white">
                                    {{ $jadwal->mata_pelajaran ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-[#c2c6dc]">
                                    {{ $jadwal->guru->nama ?? ($jadwal->guru->user->name ?? '-') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="block text-white font-semibold">Kelas: {{ $jadwal->kelas ?? '-' }}</span>
                                    <span class="text-xs text-[#626885] block mt-0.5">Ruang:
                                        {{ $jadwal->ruangan ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if ($jadwal->is_active)
                                        <span
                                            class="bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-xs px-2.5 py-1 rounded-lg font-semibold inline-flex items-center gap-1.5">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span> Aktif
                                        </span>
                                    @else
                                        <span
                                            class="bg-[#10163a] text-[#626885] border border-[#242b4d] text-xs px-2.5 py-1 rounded-lg font-semibold inline-flex items-center gap-1.5">
                                            <span class="h-1.5 w-1.5 rounded-full bg-[#626885]"></span> Non-Aktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center items-center gap-2">
                                        <button
                                            onclick="openShowModal({{ json_encode($jadwal) }}, '{{ $jadwal->guru->nama ?? ($jadwal->guru->user->name ?? '-') }}')"
                                            class="text-[#7367f0] hover:text-white bg-[#7367f0]/10 hover:bg-[#7367f0] p-2.5 rounded-xl transition cursor-pointer"
                                            title="Detail">
                                            <i class="fas fa-eye text-xs"></i>
                                        </button>

                                        <button onclick="openEditModal({{ json_encode($jadwal) }})"
                                            class="text-[#ff9f43] hover:text-[#161d3f] bg-[#ff9f43]/10 hover:bg-[#ff9f43] p-2.5 rounded-xl transition cursor-pointer"
                                            title="Edit">
                                            <i class="fas fa-edit text-xs"></i>
                                        </button>

                                        <form action="{{ route('admin.jadwal.destroy', $jadwal->id) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')"
                                            class="inline m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-rose-400 hover:text-white bg-rose-500/10 hover:bg-rose-500 p-2.5 rounded-xl transition cursor-pointer"
                                                title="Hapus">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-[#626885]">
                                    <i class="fas fa-calendar-times text-4xl mb-3 text-[#242b4d] block"></i>
                                    <span class="text-sm font-medium">Belum ada data jadwal pelajaran.</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($jadwals->hasPages())
                <div class="p-4 border-t border-[#242b4d] bg-[#10163a]/50">
                    {{ $jadwals->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- MODAL TEMPLATE REUSABLE WRAPPER --}}
    <div id="modalOverlay"
        class="fixed inset-0 z-50 hidden bg-[#10163a]/80 backdrop-blur-sm transition-opacity duration-300 items-center justify-center p-4 overflow-y-auto">

        {{-- Detail Modal --}}
        <div id="showModal"
            class="hidden bg-[#161d3f] border border-[#242b4d] rounded-2xl shadow-xl max-w-md w-full overflow-hidden my-auto transform transition-all">
            <div class="bg-[#7367f0] text-white px-6 py-4 flex justify-between items-center">
                <h3 class="font-bold flex items-center gap-2"><i class="fas fa-info-circle"></i> Detail Jadwal</h3>
                <button onclick="closeActiveModal()" class="text-white/80 hover:text-white transition cursor-pointer"><i
                        class="fas fa-times text-lg"></i></button>
            </div>
            <div class="p-6 space-y-4 text-sm text-[#c2c6dc]">
                <div class="grid grid-cols-3 border-b border-[#242b4d] pb-2.5"><span
                        class="font-semibold text-[#626885]">Hari</span><span
                        class="col-span-2 text-right sm:text-left text-white" id="show-hari"></span></div>
                <div class="grid grid-cols-3 border-b border-[#242b4d] pb-2.5"><span
                        class="font-semibold text-[#626885]">Jam</span><span
                        class="col-span-2 text-right sm:text-left text-white" id="show-jam"></span></div>
                <div class="grid grid-cols-3 border-b border-[#242b4d] pb-2.5"><span
                        class="font-semibold text-[#626885]">Mata Pelajaran</span><span
                        class="col-span-2 text-right sm:text-left font-bold text-[#7367f0]" id="show-mapel"></span></div>
                <div class="grid grid-cols-3 border-b border-[#242b4d] pb-2.5"><span
                        class="font-semibold text-[#626885]">Guru Pengajar</span><span
                        class="col-span-2 text-right sm:text-left text-white" id="show-guru"></span></div>
                <div class="grid grid-cols-3 border-b border-[#242b4d] pb-2.5"><span
                        class="font-semibold text-[#626885]">Kelas</span><span
                        class="col-span-2 text-right sm:text-left text-white" id="show-kelas"></span></div>
                <div class="grid grid-cols-3 border-b border-[#242b4d] pb-2.5"><span
                        class="font-semibold text-[#626885]">Ruangan</span><span
                        class="col-span-2 text-right sm:text-left text-white" id="show-ruangan"></span></div>
                <div class="grid grid-cols-3 border-b border-[#242b4d] pb-2.5"><span
                        class="font-semibold text-[#626885]">Status</span><span class="col-span-2 text-right sm:text-left"
                        id="show-status"></span></div>
            </div>
            <div class="bg-[#10163a]/50 border-t border-[#242b4d] px-6 py-4 flex justify-end">
                <button onclick="closeActiveModal()"
                    class="bg-[#242b4d] hover:bg-[#2e375e] text-[#c2c6dc] font-bold py-2 px-4 rounded-xl text-xs transition cursor-pointer">Tutup</button>
            </div>
        </div>

        {{-- Edit Modal --}}
        <div id="editModal"
            class="hidden bg-[#161d3f] border border-[#242b4d] rounded-2xl shadow-xl max-w-lg w-full overflow-hidden my-auto transform transition-all">
            <div class="bg-[#ff9f43] text-[#161d3f] px-6 py-4 flex justify-between items-center">
                <h3 class="font-bold flex items-center gap-2"><i class="fas fa-edit"></i> Ubah Jadwal Pelajaran</h3>
                <button onclick="closeActiveModal()"
                    class="text-[#161d3f]/80 hover:text-[#161d3f] transition cursor-pointer"><i
                        class="fas fa-times text-lg"></i></button>
            </div>
            <form id="editForm" method="POST" class="p-6 space-y-5 max-h-[80vh] overflow-y-auto">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-[#c2c6dc] font-semibold mb-2 text-xs uppercase tracking-wider">Pilih Guru
                        <span class="text-[#ff9f43]">*</span></label>
                    <select name="guru_id" id="edit-guru_id"
                        class="w-full bg-[#10163a] border border-[#242b4d] text-white rounded-xl p-3 text-sm focus:ring-2 focus:ring-[#ff9f43] focus:border-[#ff9f43] outline-hidden transition cursor-pointer"
                        required>
                        @foreach ($gurus ?? [] as $guru)
                            <option value="{{ $guru->id }}">{{ $guru->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-[#c2c6dc] font-semibold mb-2 text-xs uppercase tracking-wider">Hari <span
                                class="text-[#ff9f43]">*</span></label>
                        <select name="hari" id="edit-hari"
                            class="w-full bg-[#10163a] border border-[#242b4d] text-white rounded-xl p-3 text-sm focus:ring-2 focus:ring-[#ff9f43] focus:border-[#ff9f43] outline-hidden transition cursor-pointer"
                            required>
                            @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $h)
                                <option value="{{ $h }}">{{ $h }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[#c2c6dc] font-semibold mb-2 text-xs uppercase tracking-wider">Jam Masuk
                            <span class="text-[#ff9f43]">*</span></label>
                        <input type="time" name="jam_masuk" id="edit-jam_masuk"
                            class="w-full bg-[#10163a] border border-[#242b4d] text-white rounded-xl p-3 text-sm focus:ring-2 focus:ring-[#ff9f43] focus:border-[#ff9f43] outline-hidden transition"
                            required>
                    </div>
                    <div>
                        <label class="block text-[#c2c6dc] font-semibold mb-2 text-xs uppercase tracking-wider">Jam Pulang
                            <span class="text-[#ff9f43]">*</span></label>
                        <input type="time" name="jam_pulang" id="edit-jam_pulang"
                            class="w-full bg-[#10163a] border border-[#242b4d] text-white rounded-xl p-3 text-sm focus:ring-2 focus:ring-[#ff9f43] focus:border-[#ff9f43] outline-hidden transition"
                            required>
                    </div>
                </div>

                <div>
                    <label class="block text-[#c2c6dc] font-semibold mb-2 text-xs uppercase tracking-wider">Mata
                        Pelajaran</label>
                    <input type="text" name="mata_pelajaran" id="edit-mapel"
                        class="w-full bg-[#10163a] border border-[#242b4d] text-white rounded-xl p-3 text-sm focus:ring-2 focus:ring-[#ff9f43] focus:border-[#ff9f43] outline-hidden transition placeholder-[#626885]"
                        placeholder="Contoh: Matematika">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label
                            class="block text-[#c2c6dc] font-semibold mb-2 text-xs uppercase tracking-wider">Kelas</label>
                        <input type="text" name="kelas" id="edit-kelas"
                            class="w-full bg-[#10163a] border border-[#242b4d] text-white rounded-xl p-3 text-sm focus:ring-2 focus:ring-[#ff9f43] focus:border-[#ff9f43] outline-hidden transition placeholder-[#626885]"
                            placeholder="Contoh: X RPL 1">
                    </div>
                    <div>
                        <label
                            class="block text-[#c2c6dc] font-semibold mb-2 text-xs uppercase tracking-wider">Ruangan</label>
                        <input type="text" name="ruangan" id="edit-ruangan"
                            class="w-full bg-[#10163a] border border-[#242b4d] text-white rounded-xl p-3 text-sm focus:ring-2 focus:ring-[#ff9f43] focus:border-[#ff9f43] outline-hidden transition placeholder-[#626885]"
                            placeholder="Contoh: Lab Komputer 2">
                    </div>
                </div>

                <div class="pt-2">
                    <div
                        class="flex items-center justify-between p-3.5 bg-[#10163a] border border-[#242b4d] rounded-xl max-w-xs">
                        <label
                            class="text-sm font-semibold text-[#c2c6dc] flex items-center gap-2.5 cursor-pointer select-none"
                            for="edit-is_active">
                            <i class="fas fa-toggle-on text-[#626885] w-4 text-center"></i> Aktifkan Jadwal
                        </label>
                        <div class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" id="edit-is_active" value="1"
                                class="sr-only peer">
                            <div
                                class="w-11 h-6 bg-[#161d3f] border border-[#242b4d] peer-focus:outline-hidden rounded-full peer peer-checked:after:translate-x-full peer-checked:after:bg-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-[#626885] after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#ff9f43] peer-checked:border-transparent">
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-[#10163a]/50 -mx-6 -mb-6 px-6 py-4 flex flex-col-reverse sm:flex-row sm:justify-end gap-3 border-t border-[#242b4d] mt-6">
                    <button type="button" onclick="closeActiveModal()"
                        class="w-full sm:w-auto bg-[#242b4d] hover:bg-[#2e375e] text-[#c2c6dc] font-bold py-2.5 px-5 rounded-xl text-xs transition cursor-pointer">Batal</button>
                    <button type="submit"
                        class="w-full sm:w-auto bg-[#ff9f43] hover:bg-[#e28c34] text-[#161d3f] font-bold py-2.5 px-5 rounded-xl text-xs transition cursor-pointer">Simpan
                        Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const overlay = document.getElementById('modalOverlay');
        const showModal = document.getElementById('showModal');
        const editModal = document.getElementById('editModal');

        function openShowModal(jadwal, namaGuru) {
            document.getElementById('show-hari').innerText = jadwal.hari;
            document.getElementById('show-jam').innerText =
                `${jadwal.jam_masuk.slice(0,5)} - ${jadwal.jam_pulang.slice(0,5)}`;
            document.getElementById('show-mapel').innerText = jadwal.mata_pelajaran || '-';
            document.getElementById('show-guru').innerText = namaGuru;
            document.getElementById('show-kelas').innerText = jadwal.kelas || '-';
            document.getElementById('show-ruangan').innerText = jadwal.ruangan || '-';
            document.getElementById('show-status').innerHTML = jadwal.is_active ?
                '<span class="text-emerald-400 font-bold inline-flex items-center gap-1"><i class="fas fa-check-circle"></i> Aktif</span>' :
                '<span class="text-rose-400 font-bold inline-flex items-center gap-1"><i class="fas fa-times-circle"></i> Non-Aktif</span>';

            toggleModal(showModal, true);
        }

        function openEditModal(jadwal) {
            document.getElementById('editForm').action = `/admin/jadwal/${jadwal.id}`;

            document.getElementById('edit-hari').value = jadwal.hari;
            document.getElementById('edit-jam_masuk').value = jadwal.jam_masuk.slice(0, 5);
            document.getElementById('edit-jam_pulang').value = jadwal.jam_pulang.slice(0, 5);
            document.getElementById('edit-mapel').value = jadwal.mata_pelajaran || '';
            document.getElementById('edit-kelas').value = jadwal.kelas || '';
            document.getElementById('edit-ruangan').value = jadwal.ruangan || '';
            document.getElementById('edit-is_active').checked = !!jadwal.is_active;

            // Validasi pengisian select guru
            const selectGuru = document.getElementById('edit-guru_id');
            if (selectGuru.options.length > 0) {
                selectGuru.value = jadwal.guru_id;
            } else {
                // Fallback render jika data $gurus tidak dilempar dari controller
                selectGuru.innerHTML =
                    `<option value="${jadwal.guru_id}" selected>${jadwal.guru?.nama || 'Guru Terpilih'}</option>`;
            }

            toggleModal(editModal, true);
        }

        function toggleModal(modalEl, show) {
            if (show) {
                overlay.classList.remove('hidden');
                overlay.classList.add('flex');
                modalEl.classList.remove('hidden');
            } else {
                overlay.classList.add('hidden');
                overlay.classList.remove('flex');
                showModal.classList.add('hidden');
                editModal.classList.add('hidden');
            }
        }

        function closeActiveModal() {
            toggleModal(null, false);
        }

        // Close modal when clicking outside the modal box
        overlay.addEventListener('click', function(e) {
            if (e.target === overlay) closeActiveModal();
        });
    </script>
@endsection
