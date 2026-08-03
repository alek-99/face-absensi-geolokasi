@extends('layouts.admin')
@section('title', 'Management Profil Sekolah')

@section('content')
<div class="space-y-8">

    {{-- Alert Success --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    @endif

    {{-- Alert Error --}}
    @if(session('error'))
        <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    @endif

    @if ($sekolahs->isEmpty())
        {{-- SETUP INITIAL FORM (JIKA BELUM ADA DATA) --}}
        <div class="max-w-4xl mx-auto bg-white shadow-sm overflow-hidden border border-gray-200">
            <div class="p-8 text-center bg-gradient-to-r from-indigo-600 to-violet-600 text-white">
                <svg class="w-14 h-14 mx-auto mb-3 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <h2 class="text-2xl font-extrabold mb-1 tracking-wider">Setup Profil Sekolah</h2>
                <p class="text-indigo-100 text-sm">Data sekolah belum dikonfigurasi. Silakan lengkapi form berikut untuk memulai.</p>
            </div>

            <form action="{{ route('admin.sekolah.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
                @csrf
                
                <div>
                    <h3 class="text-sm font-bold text-indigo-600 uppercase tracking-wider mb-4 border-b border-gray-200 pb-2">1. Informasi Utama & Media</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Sekolah <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama_sekolah" value="{{ old('nama_sekolah') }}" class="w-full px-4 py-2 bg-white border border-gray-300 text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Logo Sekolah</label>
                            <input type="file" name="logo_sekolah" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Foto Sekolah / Gedung</label>
                            <input type="file" name="foto_sekolah" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-bold text-indigo-600 uppercase tracking-wider mb-4 border-b border-gray-200 pb-2">2. Kontak & Jam Operasional</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
                            <input type="text" name="no_telp" value="{{ old('no_telp') }}" class="w-full px-4 py-2 bg-white border border-gray-300 text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email Sekolah</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2 bg-white border border-gray-300 text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jam Masuk <span class="text-rose-500">*</span></label>
                            <input type="time" name="jam_masuk" value="{{ old('jam_masuk') }}" class="w-full px-4 py-2 bg-white border border-gray-300 text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jam Keluar <span class="text-rose-500">*</span></label>
                            <input type="time" name="jam_keluar" value="{{ old('jam_keluar') }}" class="w-full px-4 py-2 bg-white border border-gray-300 text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" required>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-bold text-indigo-600 uppercase tracking-wider mb-4 border-b border-gray-200 pb-2">3. Area & Lokasi Presensi</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Latitude <span class="text-rose-500">*</span></label>
                            <input type="text" name="latitude" value="{{ old('latitude') }}" placeholder="-6.123456" class="w-full px-4 py-2 bg-white border border-gray-300 text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Longitude <span class="text-rose-500">*</span></label>
                            <input type="text" name="longitude" value="{{ old('longitude') }}" placeholder="106.123456" class="w-full px-4 py-2 bg-white border border-gray-300 text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Radius Meter <span class="text-rose-500">*</span></label>
                            <input type="number" name="radius" value="{{ old('radius') }}" placeholder="e.g. 100" class="w-full px-4 py-2 bg-white border border-gray-300 text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap <span class="text-rose-500">*</span></label>
                        <textarea name="alamat" rows="3" class="w-full px-4 py-2 bg-white border border-gray-300 text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" required>{{ old('alamat') }}</textarea>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-200 flex justify-end">
                    <button type="submit" class="bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 text-white font-semibold px-6 py-2.5 shadow-md transition duration-200">
                        Simpan Profil
                    </button>
                </div>
            </form>
        </div>
    @else
        {{-- VIEW & EDIT PROFIL SEKOLAH --}}
        @php $sekolah = $sekolahs->first(); @endphp
        
        <div class="max-w-5xl mx-auto bg-white shadow-sm overflow-hidden border border-gray-200" id="profileContainer">
            
            {{-- Header / Hero Image --}}
            <div class="h-72 bg-gray-100 relative">
                @if($sekolah->foto_sekolah)
                    <img src="{{ asset('storage/' . $sekolah->foto_sekolah) }}" alt="Foto Sekolah" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-gradient-to-r from-indigo-500 to-violet-600 flex items-center justify-center text-white font-semibold">Foto Gedung Sekolah Belum Diatur</div>
                @endif
                <div class="absolute inset-0 bg-black/30"></div>
                
                <button onclick="toggleMode()" id="btnToggle" class="absolute top-6 right-6 bg-white/80 hover:bg-white text-gray-900 font-semibold px-5 py-2.5 shadow-md border border-gray-200/50 backdrop-blur-md transition flex items-center gap-2 text-sm z-20">
                    <svg id="editIcon" class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                    </svg>
                    <span id="btnText">Edit Profil</span>
                </button>
            </div>

            {{-- Logo & Judul --}}
            <div class="px-8 pb-6 relative flex flex-col sm:flex-row items-center sm:items-end gap-5 -mt-20 mb-4 z-10">
                <div class="w-36 h-36 bg-white p-2 shadow-lg border border-gray-200 overflow-hidden flex items-center justify-center">
                    @if($sekolah->logo_sekolah)
                        <img src="{{ asset('storage/' . $sekolah->logo_sekolah) }}" alt="Logo" class="w-full h-full object-contain">
                    @else
                        <div class="w-full h-full bg-gray-50 flex items-center justify-center text-xs text-gray-400 font-bold">NO LOGO</div>
                    @endif
                </div>
                <div class="text-center sm:text-left pt-2">
                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">{{ $sekolah->nama_sekolah }}</h1>
                    <p class="text-xs font-semibold text-emerald-700 bg-emerald-50 inline-block px-3 py-1 mt-2 border border-emerald-200">Sistem Geofencing Aktif ({{ $sekolah->radius }} Meter)</p>
                </div>
            </div>

            <hr class="border-gray-200 mx-8">

            {{-- TAMPILAN VIEW MODE --}}
            <div id="viewMode" class="p-8 grid grid-cols-1 md:grid-cols-3 gap-8 transition-opacity duration-300">
                
                <div class="md:col-span-2 space-y-6">
                    <div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Alamat Lengkap</span>
                        <p class="text-gray-700 mt-1 leading-relaxed font-medium">{{ $sekolah->alamat }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">No. Telepon</span>
                            <p class="text-gray-900 font-semibold mt-0.5">{{ $sekolah->no_telp ?? '-' }}</p>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Email</span>
                            <p class="text-gray-900 font-semibold mt-0.5">{{ $sekolah->email ?? '-' }}</p>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Jam Operasional Masuk</span>
                            <p class="text-emerald-600 font-bold mt-0.5 text-lg">{{ \Carbon\Carbon::parse($sekolah->jam_masuk)->format('H:i') }} WIB</p>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Jam Operasional Keluar</span>
                            <p class="text-rose-600 font-bold mt-0.5 text-lg">{{ \Carbon\Carbon::parse($sekolah->jam_keluar)->format('H:i') }} WIB</p>
                        </div>
                    </div>
                </div>
                
                {{-- Side Card Coordinates --}}
                <div class="bg-gray-50 p-6 border border-gray-200 flex flex-col justify-between">
                    <div class="space-y-4">
                        <h4 class="font-bold text-gray-900 border-b border-gray-200 pb-2 flex items-center gap-1.5 text-sm">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Koordinat Maps
                        </h4>
                        <div class="text-xs">
                            <span class="text-gray-400 font-medium block">Latitude</span>
                            <code class="text-indigo-700 font-mono text-sm bg-indigo-50 border border-indigo-100 px-2.5 py-1 inline-block mt-1 font-semibold">{{ $sekolah->latitude }}</code>
                        </div>
                        <div class="text-xs">
                            <span class="text-gray-400 font-medium block">Longitude</span>
                            <code class="text-indigo-700 font-mono text-sm bg-indigo-50 border border-indigo-100 px-2.5 py-1 inline-block mt-1 font-semibold">{{ $sekolah->longitude }}</code>
                        </div>
                        <div class="text-xs">
                            <span class="text-gray-400 font-medium block">Radius Jangkauan</span>
                            <span class="text-gray-900 font-bold block text-sm mt-1">{{ $sekolah->radius }} Meter</span>
                        </div>
                    </div>
                    
                    <form action="{{ route('admin.sekolah.destroy', $sekolah->id) }}" method="POST" onsubmit="return confirm('Menghapus data profil akan mereset pengaturan dashboard sekolah. Lanjutkan?')" class="mt-6 pt-4 border-t border-gray-200">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs text-rose-600 hover:text-rose-800 font-medium flex items-center gap-1 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Hapus & Reset Profil
                        </button>
                    </form>
                </div>
            </div>

            {{-- TAMPILAN EDIT MODE --}}
            <div id="editMode" class="p-8 hidden transition-opacity duration-300">
                <form action="{{ route('admin.sekolah.update', $sekolah->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <h3 class="text-lg font-bold text-gray-900 border-b border-gray-200 pb-2 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Detail Profil
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Sekolah <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama_sekolah" value="{{ old('nama_sekolah', $sekolah->nama_sekolah) }}" class="w-full px-4 py-2 bg-white border border-gray-300 text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Ubah Logo</label>
                            <input type="file" name="logo_sekolah" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:border-0 file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Ubah Foto Gedung</label>
                            <input type="file" name="foto_sekolah" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:border-0 file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">No. Telepon</label>
                            <input type="text" name="no_telp" value="{{ old('no_telp', $sekolah->no_telp) }}" class="w-full px-4 py-2 bg-white border border-gray-300 text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Email Sekolah</label>
                            <input type="email" name="email" value="{{ old('email', $sekolah->email) }}" class="w-full px-4 py-2 bg-white border border-gray-300 text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Jam Masuk <span class="text-rose-500">*</span></label>
                            <input type="time" name="jam_masuk" value="{{ old('jam_masuk', \Carbon\Carbon::parse($sekolah->jam_masuk)->format('H:i')) }}" class="w-full px-4 py-2 bg-white border border-gray-300 text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none" required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Jam Keluar <span class="text-rose-500">*</span></label>
                            <input type="time" name="jam_keluar" value="{{ old('jam_keluar', \Carbon\Carbon::parse($sekolah->jam_keluar)->format('H:i')) }}" class="w-full px-4 py-2 bg-white border border-gray-300 text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Latitude <span class="text-rose-500">*</span></label>
                            <input type="text" name="latitude" value="{{ old('latitude', $sekolah->latitude) }}" class="w-full px-4 py-2 bg-white border border-gray-300 text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Longitude <span class="text-rose-500">*</span></label>
                            <input type="text" name="longitude" value="{{ old('longitude', $sekolah->longitude) }}" class="w-full px-4 py-2 bg-white border border-gray-300 text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Radius (Meter) <span class="text-rose-500">*</span></label>
                            <input type="number" name="radius" value="{{ old('radius', $sekolah->radius) }}" class="w-full px-4 py-2 bg-white border border-gray-300 text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none" required>
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Alamat Lengkap <span class="text-rose-500">*</span></label>
                            <textarea name="alamat" rows="3" class="w-full px-4 py-2 bg-white border border-gray-300 text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none" required>{{ old('alamat', $sekolah->alamat) }}</textarea>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-200 flex justify-end gap-3">
                        <button type="button" onclick="toggleMode()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium px-5 py-2.5 transition">
                            Batal
                        </button>
                        <button type="submit" class="bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 text-white font-semibold px-6 py-2.5 shadow-md transition">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

        </div>
    @endif
</div>

<script>
    function toggleMode() {
        const viewMode = document.getElementById('viewMode');
        const editMode = document.getElementById('editMode');
        const btnText = document.getElementById('btnText');
        const editIcon = document.getElementById('editIcon');

        if (editMode.classList.contains('hidden')) {
            viewMode.classList.add('hidden');
            editMode.classList.remove('hidden');
            btnText.innerText = 'Lihat Profil';
            editIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>`;
        } else {
            editMode.classList.add('hidden');
            viewMode.classList.remove('hidden');
            btnText.innerText = 'Edit Profil';
            editIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>`;
        }
    }
</script>
@endsection