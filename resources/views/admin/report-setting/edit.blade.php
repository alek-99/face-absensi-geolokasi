@extends('layouts.admin')
@section('title','Edit Pengaturan PDF')
@section('content')
<div class="space-y-6">
    {{-- BAR HEADER JADWAL --}}
    <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <h4 class="text-xl font-bold text-slate-800 flex items-center gap-2">
            <i class="fas fa-sliders-h text-[#7367f0]"></i> Edit Pengaturan Format Laporan PDF
        </h4>
        <p class="text-sm text-slate-500 mt-1">Sesuaikan komponen cetak halaman utama, area margin pembatas, dan tanda tangan digital validasi.</p>
    </div>

    {{-- DOKUMEN ERROR VALIDASI --}}
    @if($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl text-rose-600 shadow-xs" role="alert">
            <div class="flex items-center gap-2 font-bold mb-2 text-sm">
                <i class="fas fa-exclamation-triangle text-base"></i> Mohon periksa kembali inputan Anda:
            </div>
            <ul class="list-disc pl-5 space-y-1 text-xs text-rose-700">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FORM INPUT UTAMA --}}
    <form action="{{ route('admin.report-setting.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- KOORDINAT KOP SURAT --}}
        <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs overflow-hidden">
            <div class="bg-slate-50 border-b border-slate-200/80 px-5 py-4 flex items-center gap-2">
                <i class="fas fa-heading text-[#7367f0]"></i>
                <h5 class="font-bold text-slate-700 text-xs uppercase tracking-wider">Bagian Header (Kop Surat)</h5>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach([
                    'show_logo' => ['Tampilkan Logo', 'fa-image'],
                    'show_school_name' => ['Tampilkan Nama Sekolah', 'fa-school'],
                    'show_address' => ['Tampilkan Alamat', 'fa-map-marker-alt'],
                    'show_email' => ['Tampilkan Email', 'fa-envelope'],
                    'show_phone' => ['Tampilkan Telepon', 'fa-phone'],
                    'show_header_line' => ['Tampilkan Garis Header', 'fa-grip-lines']
                ] as $field => $meta)
                <div class="flex items-center justify-between p-3.5 bg-slate-50/70 border border-slate-200/80 rounded-xl">
                    <label class="text-sm font-medium text-slate-700 flex items-center gap-2.5 cursor-pointer select-none" for="{{ $field }}">
                        <i class="fas {{ $meta[1] }} text-[#7367f0] w-4 text-center"></i> {{ $meta[0] }}
                    </label>
                    <div class="relative inline-flex items-center cursor-pointer">
                        <input type="hidden" name="{{ $field }}" value="0">
                        <input type="checkbox" id="{{ $field }}" name="{{ $field }}" value="1" class="sr-only peer" {{ old($field, $setting->$field) ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-slate-200 border border-slate-300 peer-focus:outline-hidden rounded-full peer peer-checked:after:translate-x-full peer-checked:after:bg-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-slate-400 after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#7367f0] peer-checked:border-transparent"></div>
                    </div>
                </div>
                @endforeach

                <div class="md:col-span-2 lg:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2 flex items-center gap-2">
                        <i class="fas fa-paint-brush text-[#7367f0] w-4 text-center"></i> Gaya Garis Header
                    </label>
                    <select name="header_line_style" class="w-full bg-white border border-slate-300 text-slate-800 rounded-xl px-4 py-2.5 text-sm focus:outline-hidden focus:ring-2 focus:ring-[#7367f0]/40 focus:border-[#7367f0] transition cursor-pointer">
                        <option value="single" {{ old('header_line_style', $setting->header_line_style) === 'single' ? 'selected' : '' }}>Single (Garis Tunggal)</option>
                        <option value="double" {{ old('header_line_style', $setting->header_line_style) === 'double' ? 'selected' : '' }}>Double (Garis Ganda)</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- KONFIGURASI BADAN DOKUMEN --}}
        <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs overflow-hidden">
            <div class="bg-slate-50 border-b border-slate-200/80 px-5 py-4 flex items-center gap-2">
                <i class="fas fa-file-alt text-[#7367f0]"></i>
                <h5 class="font-bold text-slate-700 text-xs uppercase tracking-wider">Bagian Isi (Body)</h5>
            </div>
            <div class="p-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2 flex items-center gap-2">
                        <i class="fas fa-font text-[#7367f0] w-4 text-center"></i> Font Family
                    </label>
                    <input type="text" name="font_family" value="{{ old('font_family', $setting->font_family) }}" class="w-full bg-white border border-slate-300 text-slate-800 rounded-xl px-4 py-2.5 text-sm focus:outline-hidden focus:ring-2 focus:ring-[#7367f0]/40 focus:border-[#7367f0] transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2 flex items-center gap-2">
                        <i class="fas fa-text-height text-[#7367f0] w-4 text-center"></i> Ukuran Font (pt)
                    </label>
                    <input type="number" name="font_size" value="{{ old('font_size', $setting->font_size) }}" class="w-full bg-white border border-slate-300 text-slate-800 rounded-xl px-4 py-2.5 text-sm focus:outline-hidden focus:ring-2 focus:ring-[#7367f0]/40 focus:border-[#7367f0] transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2 flex items-center gap-2">
                        <i class="fas fa-scroll text-[#7367f0] w-4 text-center"></i> Ukuran Kertas
                    </label>
                    <select name="paper_size" class="w-full bg-white border border-slate-300 text-slate-800 rounded-xl px-4 py-2.5 text-sm focus:outline-hidden focus:ring-2 focus:ring-[#7367f0]/40 focus:border-[#7367f0] transition cursor-pointer">
                        @foreach(['A4', 'F4', 'LETTER'] as $paper)
                            <option value="{{ $paper }}" {{ old('paper_size', $setting->paper_size) === $paper ? 'selected' : '' }}>{{ $paper }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2 flex items-center gap-2">
                        <i class="fas fa-arrows-alt-v text-[#7367f0] w-4 text-center"></i> Orientasi
                    </label>
                    <select name="orientation" class="w-full bg-white border border-slate-300 text-slate-800 rounded-xl px-4 py-2.5 text-sm focus:outline-hidden focus:ring-2 focus:ring-[#7367f0]/40 focus:border-[#7367f0] transition cursor-pointer">
                        <option value="portrait" {{ old('orientation', $setting->orientation) === 'portrait' ? 'selected' : '' }}>Portrait</option>
                        <option value="landscape" {{ old('orientation', $setting->orientation) === 'landscape' ? 'selected' : '' }}>Landscape</option>
                    </select>
                </div>

                <div class="sm:col-span-2 md:col-span-4 mt-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-3 flex items-center gap-1.5">
                        <i class="fas fa-border-style text-[#7367f0]"></i> Batas Margin Halaman (mm)
                    </label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        @foreach(['margin_top' => 'Margin Atas', 'margin_bottom' => 'Margin Bawah', 'margin_left' => 'Margin Kiri', 'margin_right' => 'Margin Kanan'] as $marginField => $marginLabel)
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">{{ $marginLabel }}</label>
                            <input type="number" name="{{ $marginField }}" value="{{ old($marginField, $setting->$marginField) }}" class="w-full bg-white border border-slate-300 text-slate-800 rounded-xl px-4 py-2 text-sm focus:outline-hidden focus:ring-2 focus:ring-[#7367f0]/40 focus:border-[#7367f0] transition">
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- KONFIGURASI PENANDA TANGAN / STAMPEL --}}
        <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs overflow-hidden">
            <div class="bg-slate-50 border-b border-slate-200/80 px-5 py-4 flex items-center gap-2">
                <i class="fas fa-stamp text-[#7367f0]"></i>
                <h5 class="font-bold text-slate-700 text-xs uppercase tracking-wider">Bagian Bawah (Footer & Validasi)</h5>
            </div>
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="flex items-center justify-between p-3.5 bg-slate-50/70 border border-slate-200/80 rounded-xl">
                        <label class="text-sm font-medium text-slate-700 flex items-center gap-2.5 cursor-pointer select-none" for="show_signature">
                            <i class="fas fa-signature text-[#7367f0] w-4 text-center"></i> Tampilkan Tanda Tangan
                        </label>
                        <div class="relative inline-flex items-center cursor-pointer">
                            <input type="hidden" name="show_signature" value="0">
                            <input type="checkbox" id="show_signature" name="show_signature" value="1" class="sr-only peer" {{ old('show_signature', $setting->show_signature) ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-slate-200 border border-slate-300 peer-focus:outline-hidden rounded-full peer peer-checked:after:translate-x-full peer-checked:after:bg-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-slate-400 after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#7367f0] peer-checked:border-transparent"></div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-3.5 bg-slate-50/70 border border-slate-200/80 rounded-xl">
                        <label class="text-sm font-medium text-slate-700 flex items-center gap-2.5 cursor-pointer select-none" for="show_school_stamp">
                            <i class="fas fa-stamp text-[#7367f0] w-4 text-center"></i> Tampilkan Stempel
                        </label>
                        <div class="relative inline-flex items-center cursor-pointer">
                            <input type="hidden" name="show_school_stamp" value="0">
                            <input type="checkbox" id="show_school_stamp" name="show_school_stamp" value="1" class="sr-only peer" {{ old('show_school_stamp', $setting->show_school_stamp) ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-slate-200 border border-slate-300 peer-focus:outline-hidden rounded-full peer peer-checked:after:translate-x-full peer-checked:after:bg-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-slate-400 after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#7367f0] peer-checked:border-transparent"></div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2 flex items-center gap-2">
                            <i class="fas fa-city text-[#7367f0] w-4 text-center"></i> Kota Penerbitan
                        </label>
                        <input type="text" name="city" value="{{ old('city', $setting->city) }}" class="w-full bg-white border border-slate-300 text-slate-800 rounded-xl px-4 py-2.5 text-sm focus:outline-hidden focus:ring-2 focus:ring-[#7367f0]/40 focus:border-[#7367f0] transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2 flex items-center gap-2">
                            <i class="fas fa-user-tie text-[#7367f0] w-4 text-center"></i> Nama Penanda Tangan
                        </label>
                        <input type="text" name="signer_name" value="{{ old('signer_name', $setting->signer_name) }}" class="w-full bg-white border border-slate-300 text-slate-800 rounded-xl px-4 py-2.5 text-sm focus:outline-hidden focus:ring-2 focus:ring-[#7367f0]/40 focus:border-[#7367f0] transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2 flex items-center gap-2">
                            <i class="fas fa-briefcase text-[#7367f0] w-4 text-center"></i> Jabatan Resmi
                        </label>
                        <input type="text" name="signer_position" value="{{ old('signer_position', $setting->signer_position) }}" class="w-full bg-white border border-slate-300 text-slate-800 rounded-xl px-4 py-2.5 text-sm focus:outline-hidden focus:ring-2 focus:ring-[#7367f0]/40 focus:border-[#7367f0] transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2 flex items-center gap-2">
                            <i class="fas fa-id-card text-[#7367f0] w-4 text-center"></i> Nomor Induk Pegawai (NIP)
                        </label>
                        <input type="text" name="signer_nip" value="{{ old('signer_nip', $setting->signer_nip) }}" class="w-full bg-white border border-slate-300 text-slate-800 rounded-xl px-4 py-2.5 text-sm focus:outline-hidden focus:ring-2 focus:ring-[#7367f0]/40 focus:border-[#7367f0] transition">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-200">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2 flex items-center gap-2">
                            <i class="fas fa-cloud-upload-alt text-[#7367f0] w-4 text-center"></i> Gambar Tanda Tangan
                        </label>
                        <input type="file" name="signature_file" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-[#7367f0]/10 file:text-[#7367f0] hover:file:bg-[#7367f0]/20 transition cursor-pointer bg-slate-50 border border-slate-300 rounded-xl p-1.5">
                        @if($setting->signature_file)
                            <div class="mt-3 p-2.5 bg-slate-50 border border-slate-200/80 inline-block rounded-xl shadow-inner">
                                <img src="{{ $setting->signature_url }}" class="h-12 object-contain rounded" alt="Preview Tanda Tangan">
                            </div>
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2 flex items-center gap-2">
                            <i class="fas fa-cloud-upload-alt text-[#7367f0] w-4 text-center"></i> Gambar Berkas Stempel
                        </label>
                        <input type="file" name="stamp_file" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-[#7367f0]/10 file:text-[#7367f0] hover:file:bg-[#7367f0]/20 transition cursor-pointer bg-slate-50 border border-slate-300 rounded-xl p-1.5">
                        @if($setting->stamp_file)
                            <div class="mt-3 p-2.5 bg-slate-50 border border-slate-200/80 inline-block rounded-xl shadow-inner">
                                <img src="{{ $setting->stamp_url }}" class="h-12 object-contain rounded" alt="Preview Stempel">
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- FITUR ADISIONAL PENDUKUNG --}}
        <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs overflow-hidden">
            <div class="bg-slate-50 border-b border-slate-200/80 px-5 py-4 flex items-center gap-2">
                <i class="fas fa-plus-circle text-[#7367f0]"></i>
                <h5 class="font-bold text-slate-700 text-xs uppercase tracking-wider">Fitur Tambahan</h5>
            </div>
            <div class="p-6 grid grid-cols-1 sm:grid-cols-3 gap-6">
                @foreach([
                    'show_print_date' => ['Tampilkan Tanggal Cetak', 'fa-calendar-alt'],
                    'show_page_number' => ['Tampilkan Nomor Halaman', 'fa-hashtag'],
                    'show_qrcode' => ['Tampilkan QR Code Verifikasi', 'fa-qrcode']
                ] as $field => $meta)
                <div class="flex items-center justify-between p-3.5 bg-slate-50/70 border border-slate-200/80 rounded-xl">
                    <label class="text-sm font-medium text-slate-700 flex items-center gap-2.5 cursor-pointer select-none" for="{{ $field }}">
                        <i class="fas {{ $meta[1] }} text-[#7367f0] w-4 text-center"></i> {{ $meta[0] }}
                    </label>
                    <div class="relative inline-flex items-center cursor-pointer">
                        <input type="hidden" name="{{ $field }}" value="0">
                        <input type="checkbox" id="{{ $field }}" name="{{ $field }}" value="1" class="sr-only peer" {{ old($field, $setting->$field) ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-slate-200 border border-slate-300 peer-focus:outline-hidden rounded-full peer peer-checked:after:translate-x-full peer-checked:after:bg-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-slate-400 after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#7367f0] peer-checked:border-transparent"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- CONTAINER BUTTON AKSI --}}
        <div class="flex items-center gap-3 pt-4 border-t border-slate-200">
            <button type="submit" class="inline-flex items-center gap-2 bg-[#7367f0] hover:bg-[#5b50d6] text-white font-semibold px-5 py-2.5 rounded-xl shadow-xs transition duration-200 text-xs cursor-pointer">
                <i class="fas fa-save"></i> Simpan Pengaturan
            </button>
            <a href="{{ route('admin.report-setting.index') }}" class="inline-flex items-center gap-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-semibold px-5 py-2.5 rounded-xl shadow-xs transition duration-200 text-xs">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection