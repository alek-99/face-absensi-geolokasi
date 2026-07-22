@extends('layouts.admin')
@section('title', 'Ubah Data Guru')
@section('content')
    <div class="space-y-6">

        {{-- BREADCRUMB & BACK BUTTON --}}
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 bg-[#161d3f] p-6 rounded-2xl shadow-md border border-[#242b4d]">
            <div>
                <h2 class="text-2xl font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-user-pen text-[#7367f0]"></i> Ubah Data Guru
                </h2>
                <p class="text-sm text-[#626885] mt-1">Perbarui informasi profil atau username login guru yang bersangkutan.</p>
            </div>
            <div>
                <a href="{{ route('admin.guru.index') }}"
                    class="inline-flex items-center gap-2 bg-[#10163a] hover:bg-[#242b4d] text-[#c2c6dc] font-medium px-4 py-2 rounded-xl border border-[#242b4d] transition-all">
                    <i class="fa-solid fa-arrow-left text-sm"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </div>

        {{-- NOTIFIKASI ERROR GLOBAL --}}
        @if ($errors->any())
            <div class="flex items-start gap-3 bg-rose-500/10 border border-rose-500/20 text-rose-400 px-4 py-3 rounded-xl shadow-sm">
                <i class="fa-solid fa-triangle-exclamation text-lg mt-0.5"></i>
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
        <form action="{{ route('admin.guru.update', $guru->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- KIRI: DATA AKUN LOGIN --}}
                <div class="bg-[#161d3f] p-6 rounded-2xl shadow-md border border-[#242b4d] space-y-4 h-fit">
                    <div class="border-b border-[#242b4d] pb-3">
                        <h3 class="font-bold text-white flex items-center gap-2">
                            <i class="fa-solid fa-lock text-[#ff9f43]"></i> Akun Login Guru
                        </h3>
                        <p class="text-xs text-[#626885] mt-0.5">Kredensial login aplikasi. Email dan username harus unik.</p>
                    </div>

                    {{-- Username --}}
                    <div>
                        <label class="block text-xs font-semibold text-[#c2c6dc] uppercase tracking-wider mb-2">Username</label>
                        <input type="text" name="user" value="{{ old('user', $guru->user->name ?? '') }}" required
                            class="w-full px-4 py-2.5 rounded-xl bg-[#10163a] border @error('user') border-rose-500 focus:ring-rose-500/20 @else border-[#242b4d] focus:ring-[#7367f0]/20 @endif focus:border-[#7367f0] text-[#c2c6dc] focus:ring-4 transition-all text-sm">
                        @error('user') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-xs font-semibold text-[#c2c6dc] uppercase tracking-wider mb-2">Alamat Email</label>
                        <input type="email" name="email" value="{{ old('email', $guru->user->email ?? '') }}" required
                            class="w-full px-4 py-2.5 rounded-xl bg-[#10163a] border @error('email') border-rose-500 focus:ring-rose-500/20 @else border-[#242b4d] focus:ring-[#7367f0]/20 @endif focus:border-[#7367f0] text-[#c2c6dc] focus:ring-4 transition-all text-sm">
                        @error('email') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="bg-amber-500/10 border border-amber-500/20 rounded-xl p-3 text-amber-400 text-xs leading-relaxed">
                        <i class="fa-solid fa-circle-info mr-1"></i>
                        Biarkan kolom password di bawah ini <strong>kosong</strong> jika Anda tidak ingin mengubah password login guru saat ini.
                    </div>

                    {{-- Password --}}
                    <div>
                        <label class="block text-xs font-semibold text-[#c2c6dc] uppercase tracking-wider mb-2">Password Baru (Opsional)</label>
                        <input type="password" name="password" placeholder="Isi jika ingin ganti password"
                            class="w-full px-4 py-2.5 rounded-xl bg-[#10163a] border @error('password') border-rose-500 focus:ring-rose-500/20 @else border-[#242b4d] focus:ring-[#7367f0]/20 @endif focus:border-[#7367f0] text-[#c2c6dc] focus:ring-4 transition-all text-sm">
                        @error('password') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div>
                        <label class="block text-xs font-semibold text-[#c2c6dc] uppercase tracking-wider mb-2">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" placeholder="Ulangi password baru"
                            class="w-full px-4 py-2.5 rounded-xl bg-[#10163a] border border-[#242b4d] focus:border-[#7367f0] focus:ring-4 focus:ring-[#7367f0]/20 text-[#c2c6dc] transition-all text-sm">
                    </div>
                </div>

                {{-- KANAN: DATA PROFIL GURU --}}
                <div class="lg:col-span-2 bg-[#161d3f] p-6 rounded-2xl shadow-md border border-[#242b4d] space-y-6">
                    <div class="border-b border-[#242b4d] pb-3">
                        <h3 class="font-bold text-white flex items-center gap-2">
                            <i class="fa-solid fa-address-card text-[#7367f0]"></i> Biodata Lengkap Guru
                        </h3>
                        <p class="text-xs text-[#626885] mt-0.5">Sesuaikan informasi biodata yang mengalami perubahan.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Nama --}}
                        <div>
                            <label class="block text-xs font-semibold text-[#c2c6dc] uppercase tracking-wider mb-2">Nama Lengkap & Gelar</label>
                            <input type="text" name="nama" value="{{ old('nama', $guru->nama) }}" required
                                class="w-full px-4 py-2.5 rounded-xl bg-[#10163a] border @error('nama') border-rose-500 focus:ring-rose-500/20 @else border-[#242b4d] focus:ring-[#7367f0]/20 @endif focus:border-[#7367f0] text-[#c2c6dc] focus:ring-4 transition-all text-sm">
                            @error('nama') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        {{-- NIP --}}
                        <div>
                            <label class="block text-xs font-semibold text-[#c2c6dc] uppercase tracking-wider mb-2">NIP (Nomor Induk Pegawai)</label>
                            <input type="text" name="nip" value="{{ old('nip', $guru->nip) }}" required
                                class="w-full px-4 py-2.5 rounded-xl bg-[#10163a] border @error('nip') border-rose-500 focus:ring-rose-500/20 @else border-[#242b4d] focus:ring-[#7367f0]/20 @endif focus:border-[#7367f0] text-[#c2c6dc] focus:ring-4 transition-all text-sm font-mono tracking-wide">
                            @error('nip') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        {{-- Jenis Kelamin --}}
                        <div>
                            <label class="block text-xs font-semibold text-[#c2c6dc] uppercase tracking-wider mb-2">Jenis Kelamin</label>
                            <select name="jenis_kelamin" required
                                class="w-full px-4 py-2.5 rounded-xl bg-[#10163a] border @error('jenis_kelamin') border-rose-500 focus:ring-rose-500/20 @else border-[#242b4d] focus:ring-[#7367f0]/20 @endif focus:border-[#7367f0] text-[#c2c6dc] focus:ring-4 transition-all text-sm">
                                @php $jk = old('jenis_kelamin', $guru->jenis_kelamin); @endphp
                                <option value="Laki-laki" {{ $jk == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ $jk == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        {{-- No HP --}}
                       <div>
    <label class="block text-xs font-semibold text-[#c2c6dc] uppercase tracking-wider mb-2">Nomor WhatsApp / HP</label>
    <input type="text" name="no_hp" value="{{ old('no_hp', $guru->no_hp) }}" required
           inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9+]/g, '')"
           placeholder="Contoh: 083898654844 atau 6283898654844"
           class="w-full px-4 py-2.5 rounded-xl bg-[#10163a] border @error('no_hp') border-rose-500 focus:ring-rose-500/20 @else border-[#242b4d] focus:ring-[#7367f0]/20 @endif focus:border-[#7367f0] text-[#c2c6dc] focus:ring-4 transition-all text-sm">
    @error('no_hp') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
</div>

                        {{-- Tempat Lahir --}}
                        <div>
                            <label class="block text-xs font-semibold text-[#c2c6dc] uppercase tracking-wider mb-2">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $guru->tempat_lahir) }}" required
                                class="w-full px-4 py-2.5 rounded-xl bg-[#10163a] border @error('tempat_lahir') border-rose-500 focus:ring-rose-500/20 @else border-[#242b4d] focus:ring-[#7367f0]/20 @endif focus:border-[#7367f0] text-[#c2c6dc] focus:ring-4 transition-all text-sm">
                            @error('tempat_lahir') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        {{-- Tanggal Lahir --}}
                        <div>
                            <label class="block text-xs font-semibold text-[#c2c6dc] uppercase tracking-wider mb-2">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $guru->tanggal_lahir) }}" required
                                class="w-full px-4 py-2.5 rounded-xl bg-[#10163a] border @error('tanggal_lahir') border-rose-500 focus:ring-rose-500/20 @else border-[#242b4d] focus:ring-[#7367f0]/20 @endif focus:border-[#7367f0] text-[#c2c6dc] focus:ring-4 transition-all text-sm style-calendar-dark">
                            @error('tanggal_lahir') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- Alamat --}}
                    <div>
                        <label class="block text-xs font-semibold text-[#c2c6dc] uppercase tracking-wider mb-2">Alamat Tinggal Sekarang</label>
                        <textarea name="alamat" rows="3" required
                            class="w-full px-4 py-2.5 rounded-xl bg-[#10163a] border @error('alamat') border-rose-500 focus:ring-rose-500/20 @else border-[#242b4d] focus:ring-[#7367f0]/20 @endif focus:border-[#7367f0] text-[#c2c6dc] focus:ring-4 transition-all text-sm">{{ old('alamat', $guru->alamat) }}</textarea>
                        @error('alamat') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    {{-- Foto Profil --}}
                    <div>
                        <label class="block text-xs font-semibold text-[#c2c6dc] uppercase tracking-wider mb-2">Foto Profil</label>
                        <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center p-4 border border-[#242b4d] rounded-xl bg-[#10163a]/50">
                            {{-- Preview Foto Lama --}}
                            <div class="w-16 h-16 rounded-xl overflow-hidden bg-[#161d3f] border border-[#242b4d] shadow-sm flex-shrink-0">
                                @if ($guru->foto_profil)
                                    <img src="{{ asset('storage/' . $guru->foto_profil) }}" alt="Foto Lama" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-[#626885] text-[10px] text-center p-1 font-semibold uppercase">No Pic</div>
                                @endif
                            </div>
                            
                            <div class="flex-1 text-sm w-full">
                                <input type="file" name="foto_profil" accept="image/*"
                                        class="block w-full text-xs text-[#626885] file:mr-4 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-[#7367f0]/20 file:text-[#7367f0] hover:file:bg-[#7367f0]/30 transition-all cursor-pointer">
                                <p class="text-xs text-[#626885] mt-1">Pilih file baru jika ingin mengganti foto. Maksimal ukuran file 2MB.</p>
                            </div>
                        </div>
                        @error('foto_profil') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    {{-- BUTTON SUBMIT --}}
                    <div class="border-t border-[#242b4d] pt-4 flex justify-end gap-3">
                        <a href="{{ route('admin.guru.index') }}" class="px-5 py-2.5 rounded-xl bg-[#10163a] border border-[#242b4d] text-[#c2c6dc] hover:bg-[#242b4d] font-semibold text-sm transition-all">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#ff9f43] hover:bg-[#ffb167] text-white font-semibold text-sm shadow-md shadow-[#ff9f43]/20 transition-all flex items-center gap-2">
                            <i class="fa-solid fa-floppy-disk text-xs"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </div>
@endsection