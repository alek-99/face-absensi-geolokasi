@extends('layouts.admin')

@section('title', 'Ubah Data Guru')

@section('content')
<div class="space-y-6 font-sans text-black">

    {{-- BREADCRUMB & BACK BUTTON --}}
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 bg-white/80 backdrop-blur-md p-6 shadow-xs border-l-4 border-green-600">
        <div>
            <h2 class="text-2xl font-bold text-black tracking-tight flex items-center gap-2.5">
                <span class="p-2 bg-amber-50 text-black border border-amber-100/50">
                    <i class="fa-solid fa-user-pen text-xl text-black"></i>
                </span>
                Ubah Data Guru
            </h2>
            <p class="text-sm text-black mt-1 pl-11">Perbarui informasi profil atau username login guru yang bersangkutan.</p>
        </div>
        <div>
            <a href="{{ route('admin.guru.index') }}" class="inline-flex items-center gap-2 bg-slate-50 hover:bg-slate-100 text-black font-semibold px-4 py-2.5 border border-slate-200/80 transition-all text-xs shadow-2xs">
                <i class="fa-solid fa-arrow-left text-xs text-black"></i>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    {{-- NOTIFIKASI ERROR GLOBAL --}}
    @if ($errors->any())
        <div class="flex items-start gap-3 bg-rose-50 border border-rose-200/60 text-black px-4 py-3.5 text-sm shadow-2xs animate-fade-in">
            <i class="fa-solid fa-triangle-exclamation text-lg text-black mt-0.5"></i>
            <div>
                <span class="font-semibold block text-sm text-black">Mohon periksa kembali form input Anda:</span>
                <ul class="list-disc list-inside text-xs mt-1 space-y-0.5 text-black">
                    @foreach ($errors->all() as $error)
                        <li class="text-black">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- FORM UTAMA --}}
    <form action="{{ route('admin.guru.update', $guru->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- KIRI: DATA AKUN LOGIN --}}
            <div class="bg-white p-6 shadow-xs border border-slate-200/80 space-y-4 h-fit">
                <div class="border-b border-slate-100 pb-3">
                    <h3 class="font-bold text-black flex items-center gap-2">
                        <i class="fa-solid fa-lock text-black"></i> Akun Login Guru
                    </h3>
                    <p class="text-xs text-black mt-0.5">Kredensial login aplikasi. Email dan username harus unik.</p>
                </div>

                {{-- Username --}}
                <div>
                    <label for="user" class="block text-xs font-bold text-black uppercase tracking-wider mb-2">Username</label>
                    <input type="text" id="user" name="user" value="{{ old('user', $guru->user->name ?? '') }}" required
                           class="w-full px-4 py-2.5 bg-slate-50/50 border @error('user') border-rose-300 focus:ring-rose-500/20 @else border-slate-200 focus:ring-indigo-500/20 @endif focus:border-indigo-500 text-black focus:outline-none focus:ring-4 transition-all text-sm">
                    @error('user') 
                        <span class="text-xs text-black mt-1 block font-medium">{{ $message }}</span> 
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-xs font-bold text-black uppercase tracking-wider mb-2">Alamat Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $guru->user->email ?? '') }}" required
                           class="w-full px-4 py-2.5 bg-slate-50/50 border @error('email') border-rose-300 focus:ring-rose-500/20 @else border-slate-200 focus:ring-indigo-500/20 @endif focus:border-indigo-500 text-black focus:outline-none focus:ring-4 transition-all text-sm">
                    @error('email') 
                        <span class="text-xs text-black mt-1 block font-medium">{{ $message }}</span> 
                    @enderror
                </div>

                <div class="bg-amber-50 border border-amber-200/60 p-3.5 text-black text-xs leading-relaxed">
                    <i class="fa-solid fa-circle-info mr-1 text-black"></i>
                    Biarkan kolom password di bawah ini <strong>kosong</strong> jika Anda tidak ingin mengubah password login guru saat ini.
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-xs font-bold text-black uppercase tracking-wider mb-2">Password Baru (Opsional)</label>
                    <input type="password" id="password" name="password" placeholder="Isi jika ingin ganti password"
                           class="w-full px-4 py-2.5 bg-slate-50/50 border @error('password') border-rose-300 focus:ring-rose-500/20 @else border-slate-200 focus:ring-indigo-500/20 @endif focus:border-indigo-500 text-black focus:outline-none focus:ring-4 transition-all text-sm">
                    @error('password') 
                        <span class="text-xs text-black mt-1 block font-medium">{{ $message }}</span> 
                    @enderror
                </div>

                {{-- Konfirmasi Password --}}
                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-black uppercase tracking-wider mb-2">Konfirmasi Password Baru</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password baru"
                           class="w-full px-4 py-2.5 bg-slate-50/50 border border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/20 text-black focus:outline-none transition-all text-sm">
                </div>
            </div>

            {{-- KANAN: DATA PROFIL GURU --}}
            <div class="lg:col-span-2 bg-white p-6 shadow-xs border border-slate-200/80 space-y-6">
                <div class="border-b border-slate-100 pb-3">
                    <h3 class="font-bold text-black flex items-center gap-2">
                        <i class="fa-solid fa-address-card text-black"></i> Biodata Lengkap Guru
                    </h3>
                    <p class="text-xs text-black mt-0.5">Sesuaikan informasi biodata yang mengalami perubahan.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Nama --}}
                    <div>
                        <label for="nama" class="block text-xs font-bold text-black uppercase tracking-wider mb-2">Nama Lengkap & Gelar</label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama', $guru->nama) }}" required
                               class="w-full px-4 py-2.5 bg-slate-50/50 border @error('nama') border-rose-300 focus:ring-rose-500/20 @else border-slate-200 focus:ring-indigo-500/20 @endif focus:border-indigo-500 text-black focus:outline-none focus:ring-4 transition-all text-sm">
                        @error('nama') 
                            <span class="text-xs text-black mt-1 block font-medium">{{ $message }}</span> 
                        @enderror
                    </div>

                    {{-- NIP --}}
                    <div>
                        <label for="nip" class="block text-xs font-bold text-black uppercase tracking-wider mb-2">NIP (Nomor Induk Pegawai)</label>
                        <input type="text" id="nip" name="nip" value="{{ old('nip', $guru->nip) }}" required
                               class="w-full px-4 py-2.5 bg-slate-50/50 border @error('nip') border-rose-300 focus:ring-rose-500/20 @else border-slate-200 focus:ring-indigo-500/20 @endif focus:border-indigo-500 text-black focus:outline-none focus:ring-4 transition-all text-sm font-mono tracking-wide">
                        @error('nip') 
                            <span class="text-xs text-black mt-1 block font-medium">{{ $message }}</span> 
                        @enderror
                    </div>

                    {{-- Jenis Kelamin --}}
                    <div>
                        <label for="jenis_kelamin" class="block text-xs font-bold text-black uppercase tracking-wider mb-2">Jenis Kelamin</label>
                        <select id="jenis_kelamin" name="jenis_kelamin" required
                                class="w-full px-4 py-2.5 bg-slate-50/50 border @error('jenis_kelamin') border-rose-300 focus:ring-rose-500/20 @else border-slate-200 focus:ring-indigo-500/20 @endif focus:border-indigo-500 text-black focus:outline-none focus:ring-4 transition-all text-sm">
                            @php $jk = old('jenis_kelamin', $guru->jenis_kelamin); @endphp
                            <option value="Laki-laki" {{ $jk == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ $jk == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin') 
                            <span class="text-xs text-black mt-1 block font-medium">{{ $message }}</span> 
                        @enderror
                    </div>

                    {{-- No HP --}}
                    <div>
                        <label for="no_hp" class="block text-xs font-bold text-black uppercase tracking-wider mb-2">Nomor WhatsApp / HP</label>
                        <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp', $guru->no_hp) }}" required
                               inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9+]/g, '')"
                               placeholder="Contoh: 083898654844 atau 6283898654844"
                               class="w-full px-4 py-2.5 bg-slate-50/50 border @error('no_hp') border-rose-300 focus:ring-rose-500/20 @else border-slate-200 focus:ring-indigo-500/20 @endif focus:border-indigo-500 text-black focus:outline-none focus:ring-4 transition-all text-sm">
                        @error('no_hp') 
                            <span class="text-xs text-black mt-1 block font-medium">{{ $message }}</span> 
                        @enderror
                    </div>

                    {{-- Tempat Lahir --}}
                    <div>
                        <label for="tempat_lahir" class="block text-xs font-bold text-black uppercase tracking-wider mb-2">Tempat Lahir</label>
                        <input type="text" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir', $guru->tempat_lahir) }}" required
                               class="w-full px-4 py-2.5 bg-slate-50/50 border @error('tempat_lahir') border-rose-300 focus:ring-rose-500/20 @else border-slate-200 focus:ring-indigo-500/20 @endif focus:border-indigo-500 text-black focus:outline-none focus:ring-4 transition-all text-sm">
                        @error('tempat_lahir') 
                            <span class="text-xs text-black mt-1 block font-medium">{{ $message }}</span> 
                        @enderror
                    </div>

                    {{-- Tanggal Lahir --}}
                    <div>
                        <label for="tanggal_lahir" class="block text-xs font-bold text-black uppercase tracking-wider mb-2">Tanggal Lahir</label>
                        <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', $guru->tanggal_lahir) }}" required
                               class="w-full px-4 py-2.5 bg-slate-50/50 border @error('tanggal_lahir') border-rose-300 focus:ring-rose-500/20 @else border-slate-200 focus:ring-indigo-500/20 @endif focus:border-indigo-500 text-black focus:outline-none focus:ring-4 transition-all text-sm">
                        @error('tanggal_lahir') 
                            <span class="text-xs text-black mt-1 block font-medium">{{ $message }}</span> 
                        @enderror
                    </div>
                </div>

                {{-- Alamat --}}
                <div>
                    <label for="alamat" class="block text-xs font-bold text-black uppercase tracking-wider mb-2">Alamat Tinggal Sekarang</label>
                    <textarea id="alamat" name="alamat" rows="3" required
                              class="w-full px-4 py-2.5 bg-slate-50/50 border @error('alamat') border-rose-300 focus:ring-rose-500/20 @else border-slate-200 focus:ring-indigo-500/20 @endif focus:border-indigo-500 text-black focus:outline-none focus:ring-4 transition-all text-sm">{{ old('alamat', $guru->alamat) }}</textarea>
                    @error('alamat') 
                        <span class="text-xs text-black mt-1 block font-medium">{{ $message }}</span> 
                    @enderror
                </div>

                {{-- Foto Profil --}}
                <div>
                    <label for="foto_profil" class="block text-xs font-bold text-black uppercase tracking-wider mb-2">Foto Profil</label>
                    <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center p-4 border border-slate-200/80 bg-slate-50/50">
                        {{-- Preview Foto Lama --}}
                        <div class="w-16 h-16 overflow-hidden bg-white border border-slate-200 shadow-2xs flex-shrink-0">
                            @if ($guru->foto_profil)
                                <img src="{{ asset('storage/' . $guru->foto_profil) }}" alt="Foto {{ $guru->nama }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-black text-[10px] text-center p-1 font-semibold uppercase">No Pic</div>
                            @endif
                        </div>
                        
                        <div class="flex-1 text-sm w-full">
                            <input type="file" id="foto_profil" name="foto_profil" accept="image/*"
                                   class="block w-full text-xs text-black file:mr-4 file:py-1.5 file:px-3 file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-black hover:file:bg-indigo-100 transition-all cursor-pointer">
                            <p class="text-xs text-black mt-1">Pilih file baru jika ingin mengganti foto. Maksimal ukuran file 2MB.</p>
                        </div>
                    </div>
                    @error('foto_profil') 
                        <span class="text-xs text-black mt-1 block font-medium">{{ $message }}</span> 
                    @enderror
                </div>

                {{-- BUTTON SUBMIT --}}
                <div class="border-t border-slate-100 pt-4 flex justify-end gap-2.5">
                    <a href="{{ route('admin.guru.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-black font-semibold text-xs transition-all">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-black font-bold text-xs shadow-sm transition-all flex items-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-floppy-disk text-xs text-black"></i> Simpan Perubahan
                    </button>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection