@extends('layouts.admin')
@section('title', 'Pengaturan PDF')
@section('content')
<div class="space-y-6">
    {{-- BAR HEADER & AKSI --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
        <div>
            <h4 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-file-pdf text-red-500"></i> Pengaturan Format Laporan PDF
            </h4>
            <p class="text-sm text-gray-500 mt-1">Kelola tata letak, kop surat, orientasi halaman, dan prasyarat penanda tangan dokumen.</p>
        </div>
        <a href="{{ route('admin.report-setting.edit') }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-4 py-2.5 rounded-xl shadow-sm transition-all duration-200 text-xs">
            <i class="fas fa-pen text-[10px]"></i> Edit Pengaturan
        </a>
    </div>

    {{-- ALERT NOTIFIKASI SUKSES --}}
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl flex items-center gap-3 shadow-sm" role="alert">
            <i class="fas fa-check-circle text-lg text-emerald-600"></i>
            <span class="font-semibold text-sm">{{ session('success') }}</span>
        </div>
    @endif

    {{-- KONTEN UTAMA GRID --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <div class="space-y-6">
            {{-- KOP SURAT CARD --}}
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-5 py-4 flex items-center gap-2">
                    <i class="fas fa-heading text-indigo-600"></i>
                    <h5 class="font-bold text-gray-700 text-xs uppercase tracking-wider">Bagian Header (Kop Surat)</h5>
                </div>
                <ul class="divide-y divide-gray-100 text-sm text-gray-700">
                    <li class="px-5 py-4 flex justify-between items-center hover:bg-gray-50/80 transition">
                        <span class="flex items-center gap-2.5 text-gray-600"><i class="fas fa-image text-indigo-600 w-4 text-center"></i> Tampilkan Logo</span>
                        <span class="px-2.5 py-0.5 rounded-lg text-xs font-semibold {{ $setting->show_logo ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">{{ $setting->show_logo ? 'Ya' : 'Tidak' }}</span>
                    </li>
                    <li class="px-5 py-4 flex justify-between items-center hover:bg-gray-50/80 transition">
                        <span class="flex items-center gap-2.5 text-gray-600"><i class="fas fa-school text-indigo-600 w-4 text-center"></i> Tampilkan Nama Sekolah</span>
                        <span class="px-2.5 py-0.5 rounded-lg text-xs font-semibold {{ $setting->show_school_name ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">{{ $setting->show_school_name ? 'Ya' : 'Tidak' }}</span>
                    </li>
                    <li class="px-5 py-4 flex justify-between items-center hover:bg-gray-50/80 transition">
                        <span class="flex items-center gap-2.5 text-gray-600"><i class="fas fa-map-marker-alt text-indigo-600 w-4 text-center"></i> Tampilkan Alamat</span>
                        <span class="px-2.5 py-0.5 rounded-lg text-xs font-semibold {{ $setting->show_address ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">{{ $setting->show_address ? 'Ya' : 'Tidak' }}</span>
                    </li>
                    <li class="px-5 py-4 flex justify-between items-center hover:bg-gray-50/80 transition">
                        <span class="flex items-center gap-2.5 text-gray-600"><i class="fas fa-envelope text-indigo-600 w-4 text-center"></i> Tampilkan Email</span>
                        <span class="px-2.5 py-0.5 rounded-lg text-xs font-semibold {{ $setting->show_email ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">{{ $setting->show_email ? 'Ya' : 'Tidak' }}</span>
                    </li>
                    <li class="px-5 py-4 flex justify-between items-center hover:bg-gray-50/80 transition">
                        <span class="flex items-center gap-2.5 text-gray-600"><i class="fas fa-phone text-indigo-600 w-4 text-center"></i> Tampilkan Telepon</span>
                        <span class="px-2.5 py-0.5 rounded-lg text-xs font-semibold {{ $setting->show_phone ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">{{ $setting->show_phone ? 'Ya' : 'Tidak' }}</span>
                    </li>
                    <li class="px-5 py-4 flex justify-between items-center hover:bg-gray-50/80 transition">
                        <span class="flex items-center gap-2.5 text-gray-600"><i class="fas fa-grip-lines text-indigo-600 w-4 text-center"></i> Garis Header</span>
                        <span class="font-bold text-gray-800 bg-gray-100 px-2.5 py-0.5 rounded-md text-xs border border-gray-200">{{ $setting->show_header_line ? ucfirst($setting->header_line_style) : 'Tidak' }}</span>
                    </li>
                </ul>
            </div>

            {{-- BODY FORMAT CARD --}}
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-5 py-4 flex items-center gap-2">
                    <i class="fas fa-file-alt text-indigo-600"></i>
                    <h5 class="font-bold text-gray-700 text-xs uppercase tracking-wider">Bagian Isi (Body)</h5>
                </div>
                <ul class="divide-y divide-gray-100 text-sm text-gray-700">
                    <li class="px-5 py-4 flex justify-between items-center hover:bg-gray-50/80 transition">
                        <span class="flex items-center gap-2.5 text-gray-600"><i class="fas fa-font text-indigo-600 w-4 text-center"></i> Font & Ukuran</span>
                        <span class="font-semibold text-gray-900">{{ $setting->font_family }} <span class="text-[11px] bg-gray-100 border border-gray-200 text-gray-700 px-2 py-0.5 rounded-md ml-1">{{ $setting->font_size }}pt</span></span>
                    </li>
                    <li class="px-5 py-4 flex justify-between items-center hover:bg-gray-50/80 transition">
                        <span class="flex items-center gap-2.5 text-gray-600"><i class="fas fa-scroll text-indigo-600 w-4 text-center"></i> Kertas & Orientasi</span>
                        <span class="font-semibold text-gray-900">{{ $setting->paper_size }} — <span class="text-indigo-600 text-xs uppercase font-bold">{{ $setting->orientation }}</span></span>
                    </li>
                    <li class="px-5 py-4 flex justify-between items-center hover:bg-gray-50/80 transition">
                        <span class="flex items-center gap-2.5 text-gray-600"><i class="fas fa-arrows-alt text-indigo-600 w-4 text-center"></i> Margin Halaman</span>
                        <span class="font-medium text-right">
                            <span class="block text-xs font-bold text-gray-800 bg-gray-100 border border-gray-200 px-2.5 py-1 rounded-lg">{{ $setting->margin_top }} / {{ $setting->margin_right }} / {{ $setting->margin_bottom }} / {{ $setting->margin_left }} mm</span>
                            <span class="block text-[10px] text-gray-400 mt-1">(atas/kanan/bawah/kiri)</span>
                        </span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="space-y-6">
            {{-- VALIDASI FOOTER CARD --}}
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-5 py-4 flex items-center gap-2">
                    <i class="fas fa-signature text-indigo-600"></i>
                    <h5 class="font-bold text-gray-700 text-xs uppercase tracking-wider">Bagian Bawah (Footer & Validasi)</h5>
                </div>
                <ul class="divide-y divide-gray-100 text-sm text-gray-700">
                    <li class="px-5 py-4 flex justify-between items-center hover:bg-gray-50/80 transition">
                        <span class="flex items-center gap-2.5 text-gray-600"><i class="fas fa-city text-indigo-600 w-4 text-center"></i> Kota Penerbitan</span>
                        <span class="font-semibold text-gray-900">{{ $setting->city }}</span>
                    </li>
                    <li class="px-5 py-4 flex justify-between items-center hover:bg-gray-50/80 transition">
                        <span class="flex items-center gap-2.5 text-gray-600"><i class="fas fa-user-tie text-indigo-600 w-4 text-center"></i> Penanda Tangan</span>
                        <span class="text-right">
                            <span class="block font-bold text-gray-900">{{ $setting->signer_name }}</span>
                            <span class="block text-xs text-gray-500 mt-0.5">{{ $setting->signer_position }}</span>
                        </span>
                    </li>
                    <li class="px-5 py-4 flex justify-between items-center hover:bg-gray-50/80 transition">
                        <span class="flex items-center gap-2.5 text-gray-600"><i class="fas fa-id-card text-indigo-600 w-4 text-center"></i> NIP Resmi</span>
                        <span class="font-mono text-xs font-bold text-gray-800 bg-gray-100 px-2 py-1 border border-gray-200 rounded-md">{{ $setting->signer_nip ?? '-' }}</span>
                    </li>
                    <li class="px-5 py-4 flex justify-between items-center hover:bg-gray-50/80 transition">
                        <span class="flex items-center gap-2.5 text-gray-600"><i class="fas fa-signature text-indigo-600 w-4 text-center"></i> Tanda Tangan</span>
                        <span class="px-2.5 py-0.5 rounded-lg text-xs font-semibold {{ $setting->show_signature ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">{{ $setting->show_signature ? 'Ya' : 'Tidak' }}</span>
                    </li>
                    <li class="px-5 py-4 flex justify-between items-center hover:bg-gray-50/80 transition">
                        <span class="flex items-center gap-2.5 text-gray-600"><i class="fas fa-stamp text-indigo-600 w-4 text-center"></i> Stempel Resmi</span>
                        <span class="px-2.5 py-0.5 rounded-lg text-xs font-semibold {{ $setting->show_school_stamp ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">{{ $setting->show_school_stamp ? 'Ya' : 'Tidak' }}</span>
                    </li>
                </ul>
            </div>

            {{-- FITUR TAMBAHAN CARD --}}
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-5 py-4 flex items-center gap-2">
                    <i class="fas fa-plus-circle text-indigo-600"></i>
                    <h5 class="font-bold text-gray-700 text-xs uppercase tracking-wider">Fitur Tambahan</h5>
                </div>
                <ul class="divide-y divide-gray-100 text-sm text-gray-700">
                    <li class="px-5 py-4 flex justify-between items-center hover:bg-gray-50/80 transition">
                        <span class="flex items-center gap-2.5 text-gray-600"><i class="fas fa-calendar-alt text-indigo-600 w-4 text-center"></i> Tanggal Cetak</span>
                        <span class="px-2.5 py-0.5 rounded-lg text-xs font-semibold {{ $setting->show_print_date ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">{{ $setting->show_print_date ? 'Ya' : 'Tidak' }}</span>
                    </li>
                    <li class="px-5 py-4 flex justify-between items-center hover:bg-gray-50/80 transition">
                        <span class="flex items-center gap-2.5 text-gray-600"><i class="fas fa-hashtag text-indigo-600 w-4 text-center"></i> Nomor Halaman</span>
                        <span class="px-2.5 py-0.5 rounded-lg text-xs font-semibold {{ $setting->show_page_number ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">{{ $setting->show_page_number ? 'Ya' : 'Tidak' }}</span>
                    </li>
                    <li class="px-5 py-4 flex justify-between items-center hover:bg-gray-50/80 transition">
                        <span class="flex items-center gap-2.5 text-gray-600"><i class="fas fa-qrcode text-indigo-600 w-4 text-center"></i> QR Code Verifikasi</span>
                        <span class="px-2.5 py-0.5 rounded-lg text-xs font-semibold {{ $setting->show_qrcode ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">{{ $setting->show_qrcode ? 'Ya' : 'Tidak' }}</span>
                    </li>
                </ul>
            </div>
        </div>

    </div>
</div>
@endsection