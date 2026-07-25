@extends('layouts.admin')
@section('title', 'Tambah Data Guru')
@section('content')
<div class="space-y-6 font-sans">

    {{-- BREADCRUMB & BACK BUTTON --}}
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 bg-white/80 backdrop-blur-md p-6 rounded-2xl shadow-xs border border-slate-200/80">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight flex items-center gap-2.5">
                <span class="p-2 rounded-xl bg-indigo-50 text-indigo-600 border border-indigo-100/50">
                    <i class="fa-solid fa-user-plus text-xl"></i>
                </span>
                Tambah Guru Baru
            </h2>
            <p class="text-sm text-slate-500 mt-1 pl-11">Tambah dan buat akun login sekaligus profil lengkap guru.</p>
        </div>
        <div>
            <a href="{{ route('admin.guru.index') }}" class="inline-flex items-center gap-2 bg-slate-50 hover:bg-slate-100 text-slate-600 font-semibold px-4 py-2.5 rounded-xl border border-slate-200/80 transition-all text-xs shadow-2xs">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    {{-- NOTIFIKASI ERROR GLOBAL --}}
    @if ($errors->any())
        <div class="flex items-start gap-3 bg-rose-50 border border-rose-200/60 text-rose-700 px-4 py-3.5 rounded-2xl text-sm shadow-2xs animate-fade-in">
            <i class="fa-solid fa-triangle-exclamation text-lg text-rose-500 mt-0.5"></i>
            <div>
                <span class="font-semibold block text-sm">Mohon periksa kembali form input Anda:</span>
                <ul class="list-disc list-inside text-xs mt-1 space-y-0.5 opacity-90">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- FORM UTAMA --}}
    <form action="{{ route('admin.guru.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- KIRI: DATA AKUN LOGIN --}}
            <div class="bg-white p-6 rounded-2xl shadow-xs border border-slate-200/80 space-y-4 h-fit">
                <div class="border-b border-slate-100 pb-3">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <i class="fa-solid fa-lock text-indigo-600"></i> Akun Login Guru
                    </h3>
                    <p class="text-xs text-slate-400 mt-0.5">Kredensial yang digunakan guru untuk masuk ke sistem.</p>
                </div>

                {{-- Username --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Username</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-50/50 border @error('name') border-rose-300 focus:ring-rose-500/20 @else border-slate-200 focus:ring-indigo-500/20 @endif focus:border-indigo-500 text-slate-800 focus:outline-none focus:ring-4 transition-all text-sm">
                    @error('name') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-50/50 border @error('email') border-rose-300 focus:ring-rose-500/20 @else border-slate-200 focus:ring-indigo-500/20 @endif focus:border-indigo-500 text-slate-800 focus:outline-none focus:ring-4 transition-all text-sm">
                    @error('email') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Password</label>
                    <input type="password" name="password" required
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-50/50 border @error('password') border-rose-300 focus:ring-rose-500/20 @else border-slate-200 focus:ring-indigo-500/20 @endif focus:border-indigo-500 text-slate-800 focus:outline-none focus:ring-4 transition-all text-sm">
                    @error('password') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>

                {{-- Konfirmasi Password --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-50/50 border border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/20 text-slate-800 focus:outline-none transition-all text-sm">
                </div>
            </div>

            {{-- KANAN: DATA PROFIL GURU --}}
            <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-xs border border-slate-200/80 space-y-6">
                <div class="border-b border-slate-100 pb-3">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <i class="fa-solid fa-address-card text-indigo-600"></i> Biodata Lengkap Guru
                    </h3>
                    <p class="text-xs text-slate-400 mt-0.5">Informasi personal sesuai dengan berkas resmi instansi.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Nama --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Lengkap & Gelar</label>
                        <input type="text" name="nama" value="{{ old('nama') }}" required
                               class="w-full px-4 py-2.5 rounded-xl bg-slate-50/50 border @error('nama') border-rose-300 focus:ring-rose-500/20 @else border-slate-200 focus:ring-indigo-500/20 @endif focus:border-indigo-500 text-slate-800 focus:outline-none focus:ring-4 transition-all text-sm">
                        @error('nama') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    {{-- NIP --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">NIP (Nomor Induk Pegawai)</label>
                        <input type="text" name="nip" value="{{ old('nip') }}" required
                               class="w-full px-4 py-2.5 rounded-xl bg-slate-50/50 border @error('nip') border-rose-300 focus:ring-rose-500/20 @else border-slate-200 focus:ring-indigo-500/20 @endif focus:border-indigo-500 text-slate-800 focus:outline-none focus:ring-4 transition-all text-sm font-mono tracking-wide">
                        @error('nip') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    {{-- Jenis Kelamin --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Jenis Kelamin</label>
                        <select name="jenis_kelamin" required
                                class="w-full px-4 py-2.5 rounded-xl bg-slate-50/50 border @error('jenis_kelamin') border-rose-300 focus:ring-rose-500/20 @else border-slate-200 focus:ring-indigo-500/20 @endif focus:border-indigo-500 text-slate-800 focus:outline-none focus:ring-4 transition-all text-sm">
                            <option value="" disabled {{ old('jenis_kelamin') == '' ? 'selected' : '' }}>Pilih Jenis Kelamin</option>
                            <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    {{-- No HP --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nomor WhatsApp / HP</label>
                        <input type="text" name="no_hp" value="{{ old('no_hp') }}" required 
                               inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9+]/g, '')"
                               placeholder="Contoh: 083898654844 atau 6283898654844"
                               class="w-full px-4 py-2.5 rounded-xl bg-slate-50/50 border @error('no_hp') border-rose-300 focus:ring-rose-500/20 @else border-slate-200 focus:ring-indigo-500/20 @endif focus:border-indigo-500 text-slate-800 focus:outline-none focus:ring-4 transition-all text-sm">
                        @error('no_hp') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    {{-- Tempat Lahir --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" required
                               class="w-full px-4 py-2.5 rounded-xl bg-slate-50/50 border @error('tempat_lahir') border-rose-300 focus:ring-rose-500/20 @else border-slate-200 focus:ring-indigo-500/20 @endif focus:border-indigo-500 text-slate-800 focus:outline-none focus:ring-4 transition-all text-sm">
                        @error('tempat_lahir') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    {{-- Tanggal Lahir --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required
                               class="w-full px-4 py-2.5 rounded-xl bg-slate-50/50 border @error('tanggal_lahir') border-rose-300 focus:ring-rose-500/20 @else border-slate-200 focus:ring-indigo-500/20 @endif focus:border-indigo-500 text-slate-800 focus:outline-none focus:ring-4 transition-all text-sm">
                        @error('tanggal_lahir') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Alamat --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Alamat Tinggal Sekarang</label>
                    <textarea name="alamat" rows="3" required
                              class="w-full px-4 py-2.5 rounded-xl bg-slate-50/50 border @error('alamat') border-rose-300 focus:ring-rose-500/20 @else border-slate-200 focus:ring-indigo-500/20 @endif focus:border-indigo-500 text-slate-800 focus:outline-none focus:ring-4 transition-all text-sm">{{ old('alamat') }}</textarea>
                    @error('alamat') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>

                {{-- Foto Profil --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Foto Profil</label>
                    <div class="mt-1 flex items-center gap-4 p-4 border-2 border-dashed border-slate-200 rounded-2xl hover:border-indigo-500 transition-colors bg-slate-50/50">
                        <i class="fa-solid fa-image text-3xl text-slate-300"></i>
                        <div class="flex-1 text-sm">
                            <input type="file" name="foto_profil" accept="image/*"
                                   class="block w-full text-xs text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 transition-all cursor-pointer">
                            <p class="text-xs text-slate-400 mt-1">Format: JPG, JPEG, PNG. Maksimal ukuran file 2MB.</p>
                        </div>
                    </div>
                    @error('foto_profil') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>

                {{-- BUTTON SUBMIT --}}
                <div class="border-t border-slate-100 pt-4 flex justify-end gap-2.5">
                    <button type="reset" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold text-xs transition-all cursor-pointer">
                        Reset Form
                    </button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-sm transition-all flex items-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-floppy-disk text-xs"></i> Simpan Data Guru
                    </button>
                </div>
            </div>

        </div>
    </form>

</div>
@endsection