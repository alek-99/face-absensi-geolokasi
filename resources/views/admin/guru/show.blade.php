@extends('layouts.admin')
@section('title', 'Detail Guru & Akun')
@section('content')
<div class="space-y-6">

    {{-- BREADCRUMB & BACK BUTTON --}}
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 bg-[#161d3f] p-6 rounded-2xl shadow-md border border-[#242b4d]">
        <div>
            <h2 class="text-2xl font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-id-badge text-[#7367f0]"></i> Profil & Manajemen Akun
            </h2>
            <p class="text-sm text-[#626885] mt-1">Detail informasi personal guru beserta pengaturan keamanan login.</p>
        </div>
        <div>
            <a href="{{ route('admin.guru.index') }}" class="inline-flex items-center gap-2 bg-[#10163a] hover:bg-[#242b4d] text-[#c2c6dc] font-medium px-4 py-2 rounded-xl border border-[#242b4d] transition-all">
                <i class="fa-solid fa-arrow-left text-sm"></i>
                <span>Kembali ke Daftar</span>
            </a>
        </div>
    </div>

    {{-- FLASH MESSAGES --}}
    @if(session('success'))
        <div class="flex items-center gap-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-4 py-3 rounded-xl shadow-sm">
            <i class="fa-solid fa-circle-check text-lg"></i>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="flex items-start gap-3 bg-rose-500/10 border border-rose-500/20 text-rose-400 px-4 py-3 rounded-xl shadow-sm">
            <i class="fa-solid fa-triangle-exclamation text-lg mt-0.5"></i>
            <div>
                <span class="font-semibold block text-sm">Gagal memperbarui password:</span>
                <ul class="list-disc list-inside text-xs mt-1 opacity-90">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- MAIN GRID LAYOUT --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- [GRID KIRI]: FORM UPDATE PASSWORD (KEAMANAN) --}}
        <div class="bg-[#161d3f] p-6 rounded-2xl shadow-md border border-[#242b4d] space-y-4 h-fit">
            <div class="border-b border-[#242b4d] pb-3">
                <h3 class="font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-key text-[#ff9f43]"></i> Reset Password Akun
                </h3>
                <p class="text-xs text-[#626885] mt-0.5">Perbarui kata sandi login guru secara langsung tanpa mengubah data profil.</p>
            </div>

            <form action="{{ route('admin.guru.password', $guru) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-semibold text-[#c2c6dc] uppercase tracking-wider mb-2">Password Baru</label>
                    <input type="password" name="password" required placeholder="Minimal 8 karakter"
                           class="w-full px-4 py-2.5 rounded-xl bg-[#10163a] border border-[#242b4d] focus:border-[#7367f0] focus:ring-4 focus:ring-[#7367f0]/20 text-[#c2c6dc] transition-all text-sm">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#c2c6dc] uppercase tracking-wider mb-2">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" required placeholder="Ulangi password baru"
                           class="w-full px-4 py-2.5 rounded-xl bg-[#10163a] border border-[#242b4d] focus:border-[#7367f0] focus:ring-4 focus:ring-[#7367f0]/20 text-[#c2c6dc] transition-all text-sm">
                </div>

                <button type="submit" class="w-full px-4 py-2.5 text-center rounded-xl bg-[#10163a] hover:bg-[#242b4d] border border-[#242b4d] text-white font-semibold text-sm transition-all shadow-sm flex items-center justify-center gap-2">
                    <i class="fa-solid fa-floppy-disk text-xs"></i>
                    <span>Perbarui Password</span>
                </button>
            </form>
        </div>

        {{-- [GRID KANAN]: KARTU DATA LENGKAP GURU --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- BLOK IDENTITAS UTAMA --}}
            <div class="bg-[#161d3f] border border-[#242b4d] p-6 rounded-2xl shadow-md flex flex-col sm:flex-row items-center gap-5">
                <div class="w-24 h-24 rounded-2xl overflow-hidden bg-[#10163a] p-1 flex-shrink-0 border border-[#242b4d]">
                    @if($guru->foto_profil)
                        <img src="{{ asset('storage/' . $guru->foto_profil) }}" alt="Foto {{ $guru->nama }}" class="w-full h-full object-cover rounded-xl">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-[#7367f0]/20 text-[#7367f0] font-bold uppercase text-2xl rounded-xl">
                            {{ substr($guru->nama, 0, 2) }}
                        </div>
                    @endif
                </div>
                <div class="text-center sm:text-left flex-1 space-y-1">
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-semibold bg-[#10163a] text-[#c2c6dc] border border-[#242b4d] mb-1">
                        NIP. {{ $guru->nip }}
                    </div>
                    <h3 class="text-2xl font-bold tracking-tight text-white">{{ $guru->nama }}</h3>
                    <p class="text-[#626885] text-sm font-light flex items-center justify-center sm:justify-start gap-2">
                        <i class="fa-solid fa-venus-mars text-xs"></i> {{ $guru->jenis_kelamin }}
                    </p>
                </div>
                <div>
                    <a href="{{ route('admin.guru.edit', $guru->id) }}" class="inline-flex items-center gap-2 bg-[#7367f0]/10 text-[#7367f0] border border-[#7367f0]/20 hover:bg-[#7367f0]/20 font-semibold px-4 py-2 rounded-xl text-sm transition-all">
                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                        <span>Edit Profil</span>
                    </a>
                </div>
            </div>

            {{-- BIODATA DETAIL --}}
            <div class="bg-[#161d3f] p-6 rounded-2xl shadow-md border border-[#242b4d] grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- Kelompok Informasi Personal --}}
                <div class="space-y-4">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-[#626885] border-b border-[#242b4d] pb-2 flex items-center gap-2">
                        <i class="fa-solid fa-address-card text-[#7367f0]"></i> Informasi Pribadi
                    </h4>
                    <div>
                        <span class="block text-xs text-[#626885]">Tempat, Tanggal Lahir</span>
                        <span class="text-sm font-medium text-white">{{ $guru->tempat_lahir }}, {{ \Carbon\Carbon::parse($guru->tanggal_lahir)->translatedFormat('d F Y') }}</span>
                    </div>
                    <div>
                        <span class="block text-xs text-[#626885]">Alamat Rumah</span>
                        <span class="text-sm font-medium text-white block leading-relaxed">{{ $guru->alamat }}</span>
                    </div>
                </div>

                {{-- Kelompok Kontak & Log Login --}}
                <div class="space-y-4">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-[#626885] border-b border-[#242b4d] pb-2 flex items-center gap-2">
                        <i class="fa-solid fa-user-shield text-[#7367f0]"></i> Kontak & Akun Login
                    </h4>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <span class="block text-xs text-[#626885]">No. WhatsApp</span>
                            <span class="text-sm font-medium text-white">{{ $guru->no_hp }}</span>
                        </div>
                        <div>
                            <span class="block text-xs text-[#626885]">Username</span>
                            <span class="text-sm font-mono font-semibold text-white bg-[#10163a] px-2 py-0.5 rounded border border-[#242b4d] w-fit block">{{ $guru->user->name ?? '-' }}</span>
                        </div>
                    </div>
                    <div>
                        <span class="block text-xs text-[#626885]">Alamat Email</span>
                        <span class="text-sm font-medium text-white">{{ $guru->user->email ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs text-[#626885] mb-1">Status Akun Aplikasi</span>
                        <div>
                            @if($guru->is_active)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif & Dapat Login
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-rose-500/10 text-rose-400 border border-rose-500/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Ditangguhkan
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

</div>
@endsection