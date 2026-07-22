@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    {{-- BAR HEADER & AKSI --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-[#161d3f] p-6 rounded-2xl shadow-md border border-[#242b4d]">
        <div>
            <h4 class="text-xl font-bold text-white flex items-center gap-2">
                <i class="fas fa-file-pdf text-[#ea5455]"></i> Pengaturan Format Laporan PDF
            </h4>
            <p class="text-sm text-[#626885] mt-1">Kelola tata letak, kop surat, orientasi halaman, dan prasyarat penanda tangan dokumen.</p>
        </div>
        <a href="{{ route('admin.report-setting.edit') }}" class="inline-flex items-center gap-2 bg-[#7367f0] hover:bg-[#5b50d6] text-white font-semibold px-4 py-2.5 rounded-xl shadow-sm transition-all duration-200 text-xs">
            <i class="fas fa-pen text-[10px]"></i> Edit Pengaturan
        </a>
    </div>

    {{-- ALERT NOTIFIKASI SUKSES --}}
    @if(session('success'))
        <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-xl flex items-center gap-3 shadow-sm" role="alert">
            <i class="fas fa-check-circle text-lg"></i>
            <span class="font-semibold text-sm">{{ session('success') }}</span>
        </div>
    @endif

    {{-- KONTEN UTAMA GRID --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <div class="space-y-6">
            {{-- KOP SURAT CARD --}}
            <div class="bg-[#161d3f] border border-[#242b4d] rounded-2xl shadow-md overflow-hidden">
                <div class="bg-[#10163a]/50 border-b border-[#242b4d] px-5 py-4 flex items-center gap-2">
                    <i class="fas fa-heading text-[#7367f0]"></i>
                    <h5 class="font-bold text-[#c2c6dc] text-xs uppercase tracking-wider">Bagian Header (Kop Surat)</h5>
                </div>
                <ul class="divide-y divide-[#242b4d] text-sm text-[#c2c6dc]">
                    <li class="px-5 py-4 flex justify-between items-center hover:bg-[#10163a]/30 transition">
                        <span class="flex items-center gap-2.5 text-[#626885]"><i class="fas fa-image text-[#7367f0] w-4 text-center"></i> Tampilkan Logo</span>
                        <span class="px-2.5 py-0.5 rounded-lg text-xs font-semibold {{ $setting->show_logo ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20' }}">{{ $setting->show_logo ? 'Ya' : 'Tidak' }}</span>
                    </li>
                    <li class="px-5 py-4 flex justify-between items-center hover:bg-[#10163a]/30 transition">
                        <span class="flex items-center gap-2.5 text-[#626885]"><i class="fas fa-school text-[#7367f0] w-4 text-center"></i> Tampilkan Nama Sekolah</span>
                        <span class="px-2.5 py-0.5 rounded-lg text-xs font-semibold {{ $setting->show_school_name ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20' }}">{{ $setting->show_school_name ? 'Ya' : 'Tidak' }}</span>
                    </li>
                    <li class="px-5 py-4 flex justify-between items-center hover:bg-[#10163a]/30 transition">
                        <span class="flex items-center gap-2.5 text-[#626885]"><i class="fas fa-map-marker-alt text-[#7367f0] w-4 text-center"></i> Tampilkan Alamat</span>
                        <span class="px-2.5 py-0.5 rounded-lg text-xs font-semibold {{ $setting->show_address ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20' }}">{{ $setting->show_address ? 'Ya' : 'Tidak' }}</span>
                    </li>
                    <li class="px-5 py-4 flex justify-between items-center hover:bg-[#10163a]/30 transition">
                        <span class="flex items-center gap-2.5 text-[#626885]"><i class="fas fa-envelope text-[#7367f0] w-4 text-center"></i> Tampilkan Email</span>
                        <span class="px-2.5 py-0.5 rounded-lg text-xs font-semibold {{ $setting->show_email ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20' }}">{{ $setting->show_email ? 'Ya' : 'Tidak' }}</span>
                    </li>
                    <li class="px-5 py-4 flex justify-between items-center hover:bg-[#10163a]/30 transition">
                        <span class="flex items-center gap-2.5 text-[#626885]"><i class="fas fa-phone text-[#7367f0] w-4 text-center"></i> Tampilkan Telepon</span>
                        <span class="px-2.5 py-0.5 rounded-lg text-xs font-semibold {{ $setting->show_phone ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20' }}">{{ $setting->show_phone ? 'Ya' : 'Tidak' }}</span>
                    </li>
                    <li class="px-5 py-4 flex justify-between items-center hover:bg-[#10163a]/30 transition">
                        <span class="flex items-center gap-2.5 text-[#626885]"><i class="fas fa-grip-lines text-[#7367f0] w-4 text-center"></i> Garis Header</span>
                        <span class="font-bold text-white bg-[#10163a] px-2 py-0.5 rounded-md text-xs border border-[#242b4d]">{{ $setting->show_header_line ? ucfirst($setting->header_line_style) : 'Tidak' }}</span>
                    </li>
                </ul>
            </div>

            {{-- BODY FORMAT CARD --}}
            <div class="bg-[#161d3f] border border-[#242b4d] rounded-2xl shadow-md overflow-hidden">
                <div class="bg-[#10163a]/50 border-b border-[#242b4d] px-5 py-4 flex items-center gap-2">
                    <i class="fas fa-file-alt text-[#7367f0]"></i>
                    <h5 class="font-bold text-[#c2c6dc] text-xs uppercase tracking-wider">Bagian Isi (Body)</h5>
                </div>
                <ul class="divide-y divide-[#242b4d] text-sm text-[#c2c6dc]">
                    <li class="px-5 py-4 flex justify-between items-center hover:bg-[#10163a]/30 transition">
                        <span class="flex items-center gap-2.5 text-[#626885]"><i class="fas fa-font text-[#7367f0] w-4 text-center"></i> Font & Ukuran</span>
                        <span class="font-semibold text-white">{{ $setting->font_family }} <span class="text-[11px] bg-[#10163a] border border-[#242b4d] text-[#c2c6dc] px-2 py-0.5 rounded-md ml-1">{{ $setting->font_size }}pt</span></span>
                    </li>
                    <li class="px-5 py-4 flex justify-between items-center hover:bg-[#10163a]/30 transition">
                        <span class="flex items-center gap-2.5 text-[#626885]"><i class="fas fa-scroll text-[#7367f0] w-4 text-center"></i> Kertas & Orientasi</span>
                        <span class="font-semibold text-white">{{ $setting->paper_size }} — <span class="text-[#7367f0] text-xs uppercase font-bold">{{ $setting->orientation }}</span></span>
                    </li>
                    <li class="px-5 py-4 flex justify-between items-center hover:bg-[#10163a]/30 transition">
                        <span class="flex items-center gap-2.5 text-[#626885]"><i class="fas fa-arrows-alt text-[#7367f0] w-4 text-center"></i> Margin Halaman</span>
                        <span class="font-medium text-right">
                            <span class="block text-xs font-bold text-white bg-[#10163a] border border-[#242b4d] px-2 py-1 rounded-lg">{{ $setting->margin_top }} / {{ $setting->margin_right }} / {{ $setting->margin_bottom }} / {{ $setting->margin_left }} mm</span>
                            <span class="block text-[10px] text-[#626885] mt-1">(atas/kanan/bawah/kiri)</span>
                        </span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="space-y-6">
            {{-- VALIDASI FOOTER CARD --}}
            <div class="bg-[#161d3f] border border-[#242b4d] rounded-2xl shadow-md overflow-hidden">
                <div class="bg-[#10163a]/50 border-b border-[#242b4d] px-5 py-4 flex items-center gap-2">
                    <i class="fas fa-signature text-[#7367f0]"></i>
                    <h5 class="font-bold text-[#c2c6dc] text-xs uppercase tracking-wider">Bagian Bawah (Footer & Validasi)</h5>
                </div>
                <ul class="divide-y divide-[#242b4d] text-sm text-[#c2c6dc]">
                    <li class="px-5 py-4 flex justify-between items-center hover:bg-[#10163a]/30 transition">
                        <span class="flex items-center gap-2.5 text-[#626885]"><i class="fas fa-city text-[#7367f0] w-4 text-center"></i> Kota Penerbitan</span>
                        <span class="font-semibold text-white">{{ $setting->city }}</span>
                    </li>
                    <li class="px-5 py-4 flex justify-between items-center hover:bg-[#10163a]/30 transition">
                        <span class="flex items-center gap-2.5 text-[#626885]"><i class="fas fa-user-tie text-[#7367f0] w-4 text-center"></i> Penanda Tangan</span>
                        <span class="text-right">
                            <span class="block font-bold text-white">{{ $setting->signer_name }}</span>
                            <span class="block text-xs text-[#626885] mt-0.5">{{ $setting->signer_position }}</span>
                        </span>
                    </li>
                    <li class="px-5 py-4 flex justify-between items-center hover:bg-[#10163a]/30 transition">
                        <span class="flex items-center gap-2.5 text-[#626885]"><i class="fas fa-id-card text-[#7367f0] w-4 text-center"></i> NIP Resmi</span>
                        <span class="font-mono text-sm text-white bg-[#10163a] px-2 py-0.5 border border-[#242b4d] rounded-md">{{ $setting->signer_nip ?? '-' }}</span>
                    </li>
                    <li class="px-5 py-4 flex justify-between items-center hover:bg-[#10163a]/30 transition">
                        <span class="flex items-center gap-2.5 text-[#626885]"><i class="fas fa-signature text-[#7367f0] w-4 text-center"></i> Tanda Tangan</span>
                        <span class="px-2.5 py-0.5 rounded-lg text-xs font-semibold {{ $setting->show_signature ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20' }}">{{ $setting->show_signature ? 'Ya' : 'Tidak' }}</span>
                    </li>
                    <li class="px-5 py-4 flex justify-between items-center hover:bg-[#10163a]/30 transition">
                        <span class="flex items-center gap-2.5 text-[#626885]"><i class="fas fa-stamp text-[#7367f0] w-4 text-center"></i> Stempel Resmi</span>
                        <span class="px-2.5 py-0.5 rounded-lg text-xs font-semibold {{ $setting->show_school_stamp ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20' }}">{{ $setting->show_school_stamp ? 'Ya' : 'Tidak' }}</span>
                    </li>
                </ul>
            </div>

            {{-- FITUR TAMBAHAN CARD --}}
            <div class="bg-[#161d3f] border border-[#242b4d] rounded-2xl shadow-md overflow-hidden">
                <div class="bg-[#10163a]/50 border-b border-[#242b4d] px-5 py-4 flex items-center gap-2">
                    <i class="fas fa-plus-circle text-[#7367f0]"></i>
                    <h5 class="font-bold text-[#c2c6dc] text-xs uppercase tracking-wider">Fitur Tambahan</h5>
                </div>
                <ul class="divide-y divide-[#242b4d] text-sm text-[#c2c6dc]">
                    <li class="px-5 py-4 flex justify-between items-center hover:bg-[#10163a]/30 transition">
                        <span class="flex items-center gap-2.5 text-[#626885]"><i class="fas fa-calendar-alt text-[#7367f0] w-4 text-center"></i> Tanggal Cetak</span>
                        <span class="px-2.5 py-0.5 rounded-lg text-xs font-semibold {{ $setting->show_print_date ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20' }}">{{ $setting->show_print_date ? 'Ya' : 'Tidak' }}</span>
                    </li>
                    <li class="px-5 py-4 flex justify-between items-center hover:bg-[#10163a]/30 transition">
                        <span class="flex items-center gap-2.5 text-[#626885]"><i class="fas fa-hashtag text-[#7367f0] w-4 text-center"></i> Nomor Halaman</span>
                        <span class="px-2.5 py-0.5 rounded-lg text-xs font-semibold {{ $setting->show_page_number ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20' }}">{{ $setting->show_page_number ? 'Ya' : 'Tidak' }}</span>
                    </li>
                    <li class="px-5 py-4 flex justify-between items-center hover:bg-[#10163a]/30 transition">
                        <span class="flex items-center gap-2.5 text-[#626885]"><i class="fas fa-qrcode text-[#7367f0] w-4 text-center"></i> QR Code Verifikasi</span>
                        <span class="px-2.5 py-0.5 rounded-lg text-xs font-semibold {{ $setting->show_qrcode ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20' }}">{{ $setting->show_qrcode ? 'Ya' : 'Tidak' }}</span>
                    </li>
                </ul>
            </div>
        </div>

    </div>
</div>
@endsection