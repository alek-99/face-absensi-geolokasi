@extends('layouts.admin')
@section('title', 'Detail Guru & Akun')
@section('content')
<div class="space-y-6 font-sans">

    {{-- BREADCRUMB & BACK BUTTON --}}
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 bg-white/80 backdrop-blur-md p-6 rounded-2xl shadow-xs border border-slate-200/80">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight flex items-center gap-2.5">
                <span class="p-2 rounded-xl bg-indigo-50 text-indigo-600 border border-indigo-100/50">
                    <i class="fa-solid fa-id-badge text-xl"></i>
                </span>
                Profil & Manajemen Akun
            </h2>
            <p class="text-sm text-slate-500 mt-1 pl-11">Detail informasi personal guru beserta pengaturan keamanan login.</p>
        </div>
        <div>
            <a href="{{ route('admin.guru.index') }}" class="inline-flex items-center gap-2 bg-slate-50 hover:bg-slate-100 text-slate-600 font-semibold px-4 py-2.5 rounded-xl border border-slate-200/80 transition-all text-xs shadow-2xs">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span>Kembali ke Daftar</span>
            </a>
        </div>
    </div>

    {{-- FLASH MESSAGES --}}
    @if(session('success'))
        <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200/60 text-emerald-700 px-4 py-3.5 rounded-2xl text-sm shadow-2xs animate-fade-in">
            <i class="fa-solid fa-circle-check text-lg text-emerald-500"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="flex items-start gap-3 bg-rose-50 border border-rose-200/60 text-rose-700 px-4 py-3.5 rounded-2xl text-sm shadow-2xs animate-fade-in">
            <i class="fa-solid fa-triangle-exclamation text-lg text-rose-500 mt-0.5"></i>
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
        <div class="bg-white p-6 rounded-2xl shadow-xs border border-slate-200/80 space-y-4 h-fit">
            <div class="border-b border-slate-100 pb-3">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-key text-amber-500"></i> Reset Password Akun
                </h3>
                <p class="text-xs text-slate-400 mt-0.5">Perbarui kata sandi login guru secara langsung tanpa mengubah data profil.</p>
            </div>

            <form action="{{ route('admin.guru.password', $guru) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Password Baru</label>
                    <input type="password" name="password" required placeholder="Minimal 8 karakter"
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-50/50 border border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/20 text-slate-800 focus:outline-none transition-all text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" required placeholder="Ulangi password baru"
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-50/50 border border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/20 text-slate-800 focus:outline-none transition-all text-sm">
                </div>

                <button type="submit" class="w-full px-4 py-2.5 text-center rounded-xl bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs transition-all shadow-2xs flex items-center justify-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-floppy-disk text-xs"></i>
                    <span>Perbarui Password</span>
                </button>
            </form>
        </div>

        {{-- [GRID KANAN]: KARTU DATA LENGKAP GURU --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- BLOK IDENTITAS UTAMA --}}
            <div class="bg-white border border-slate-200/80 p-6 rounded-2xl shadow-xs flex flex-col sm:flex-row items-center gap-5">
                <div class="w-24 h-24 rounded-2xl overflow-hidden bg-slate-100 p-1 flex-shrink-0 border border-slate-200 shadow-2xs">
                    @if($guru->foto_profil)
                        <img src="{{ asset('storage/' . $guru->foto_profil) }}" alt="Foto {{ $guru->nama }}" class="w-full h-full object-cover rounded-xl">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-indigo-50 text-indigo-600 font-bold uppercase text-2xl rounded-xl">
                            {{ substr($guru->nama, 0, 2) }}
                        </div>
                    @endif
                </div>
                <div class="text-center sm:text-left flex-1 space-y-1">
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200/80 mb-1">
                        NIP. {{ $guru->nip }}
                    </div>
                    <h3 class="text-2xl font-bold tracking-tight text-slate-800">{{ $guru->nama }}</h3>
                    <p class="text-slate-500 text-sm font-medium flex items-center justify-center sm:justify-start gap-2">
                        <i class="fa-solid fa-venus-mars text-xs text-slate-400"></i> {{ $guru->jenis_kelamin }}
                    </p>
                </div>
                <div>
                    <a href="{{ route('admin.guru.edit', $guru->id) }}" class="inline-flex items-center gap-2 bg-indigo-50 text-indigo-600 border border-indigo-200/60 hover:bg-indigo-600 hover:text-white font-semibold px-4 py-2.5 rounded-xl text-xs transition-all shadow-2xs">
                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                        <span>Edit Profil</span>
                    </a>
                </div>
            </div>

            {{-- BIODATA DETAIL --}}
            <div class="bg-white p-6 rounded-2xl shadow-xs border border-slate-200/80 grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- Kelompok Informasi Personal --}}
                <div class="space-y-4">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-2 flex items-center gap-2">
                        <i class="fa-solid fa-address-card text-indigo-600"></i> Informasi Pribadi
                    </h4>
                    <div>
                        <span class="block text-xs font-medium text-slate-400">Tempat, Tanggal Lahir</span>
                        <span class="text-sm font-semibold text-slate-800 mt-0.5 block">{{ $guru->tempat_lahir }}, {{ \Carbon\Carbon::parse($guru->tanggal_lahir)->translatedFormat('d F Y') }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-medium text-slate-400">Alamat Rumah</span>
                        <span class="text-sm font-semibold text-slate-800 block leading-relaxed mt-0.5">{{ $guru->alamat }}</span>
                    </div>
                </div>

                {{-- Kelompok Kontak & Log Login --}}
                <div class="space-y-4">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-2 flex items-center gap-2">
                        <i class="fa-solid fa-user-shield text-indigo-600"></i> Kontak & Akun Login
                    </h4>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <span class="block text-xs font-medium text-slate-400">No. WhatsApp</span>
                            <span class="text-sm font-semibold text-slate-800 mt-0.5 block">{{ $guru->no_hp }}</span>
                        </div>
                        <div>
                            <span class="block text-xs font-medium text-slate-400">Username</span>
                            <span class="text-sm font-mono font-bold text-slate-700 bg-slate-50 px-2.5 py-0.5 rounded-lg border border-slate-200/80 w-fit block mt-0.5">{{ $guru->user->name ?? '-' }}</span>
                        </div>
                    </div>
                    <div>
                        <span class="block text-xs font-medium text-slate-400">Alamat Email</span>
                        <span class="text-sm font-semibold text-slate-800 mt-0.5 block">{{ $guru->user->email ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-medium text-slate-400 mb-1.5">Status Akun Aplikasi</span>
                        <div>
                            @if($guru->is_active)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60 shadow-2xs">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif & Dapat Login
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200/60 shadow-2xs">
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