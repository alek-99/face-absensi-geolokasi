@extends('layouts.guru')

@section('title', 'Profile')
@section('page_title', 'PROFILE')

@section('content')
{{-- Library html2canvas dimasukkan via CDN untuk menangkap elemen HTML menjadi gambar --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<div class="max-w-4xl mx-auto px-2 py-4 space-y-8 animate-fade-in">

    {{-- KARTU ID CARD INTERAKTIF --}}
    @if($guru)
    <div class="space-y-4">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 px-1">
            <div>
                <h3 class="text-sm font-bold text-emerald-950 dark:text-emerald-100 flex items-center gap-2">
                    <i class="fa-solid fa-address-card text-emerald-600 dark:text-emerald-400"></i>
                    Kartu Identitas Digital
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">Klik kartu untuk memutar dan melihat sisi belakang.</p>
            </div>
            
            {{-- Tombol Cetak / Download --}}
            <button id="btn-download-card" class="inline-flex items-center gap-2 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition shadow-md hover:shadow-lg cursor-pointer w-full sm:w-auto justify-center">
                <i class="fa-solid fa-download"></i>
                Unduh ID Card (PNG)
            </button>
        </div>

        {{-- Perspektif 3D Flip Card Container --}}
        <div class="w-full flex justify-center [perspective:1000px]">
            <div id="id-card-flip" class="relative w-full max-w-[430px] aspect-[1.586/1] transition-transform duration-500 [transform-style:preserve-3d] cursor-pointer shadow-xl rounded-2xl">
                
                {{-- SISI DEPAN KARTU (FRONT) --}}
                <div id="id-card-front" class="absolute inset-0 w-full h-full [backface-visibility:hidden] rounded-2xl p-5 flex flex-col justify-between bg-gradient-to-br from-emerald-800 via-emerald-900 to-teal-950 text-white overflow-hidden border border-emerald-500/30 shadow-inner">
                    
                    {{-- Ornamen Latar Belakang --}}
                    <div class="absolute -right-8 -bottom-8 w-40 h-40 opacity-10 rotate-12 pointer-events-none">
                        <img src="{{ asset('storage/images/logo.png') }}" alt="SIAFI Ornament" class="w-full h-full object-contain filter brightness-200">
                    </div>
                    <div class="absolute -left-10 -top-10 w-36 h-36 bg-emerald-400/10 rounded-full blur-2xl pointer-events-none"></div>
                    <div class="absolute right-1/4 top-0 w-24 h-full bg-gradient-to-r from-transparent via-emerald-400/5 to-transparent -skew-x-12 pointer-events-none"></div>

                    {{-- Header Kartu --}}
                    <div class="flex items-center justify-between border-b border-emerald-500/30 pb-2.5 z-10">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 bg-white p-1 rounded-lg shadow-sm flex items-center justify-center">
                                <img src="{{ asset('storage/images/logo.png') }}" alt="Logo" class="w-full h-full object-contain">
                            </div>
                            <div>
                                <h4 class="text-[11px] font-extrabold tracking-wider uppercase leading-none text-emerald-100">SIAFI DIGITAL</h4>
                                <span class="text-[8px] text-emerald-300 font-medium tracking-tight">Kartu Identitas Pendidik</span>
                            </div>
                        </div>
                        <span class="text-[9px] bg-emerald-500/20 border border-emerald-400/40 text-emerald-200 px-2.5 py-0.5 rounded-full font-bold tracking-widest uppercase backdrop-blur-md">GURU</span>
                    </div>

                    {{-- Konten Utama Sisi Depan --}}
                    <div class="flex gap-4 items-center my-auto z-10">
                        {{-- Foto Profil --}}
                        <div class="w-20 h-24 bg-white/10 p-1 rounded-xl shrink-0 border border-emerald-400/30 backdrop-blur-sm shadow-md">
                            <img src="{{ $guru->foto_profil ? asset('storage/' . $guru->foto_profil) : asset('images/default-avatar.png') }}" 
                                 alt="Foto {{ $guru->nama }}" 
                                 class="w-full h-full object-cover rounded-lg">
                        </div>
                        
                        {{-- Biodata Singkat --}}
                        <div class="space-y-1 overflow-hidden">
                            <span class="text-[9px] font-mono font-semibold text-emerald-300 tracking-wider block">NIP. {{ $guru->nip }}</span>
                            <h3 class="text-base font-bold tracking-tight leading-snug text-white truncate">{{ $guru->nama }}</h3>
                            
                            <div class="flex items-center gap-1.5 pt-1">
                                <span class="text-[9px] bg-emerald-950/60 border border-emerald-500/30 px-2 py-0.5 rounded-md text-emerald-200 font-semibold">
                                    {{ $guru->jenis_kelamin == 'Laki-laki' ? 'Laki-Laki' : 'Perempuan' }}
                                </span>
                                <span class="text-[9px] text-emerald-200/80 truncate">
                                    {{ $guru->tempat_lahir }}, {{ \Carbon\Carbon::parse($guru->tanggal_lahir)->translatedFormat('d M Y') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Footer Sisi Depan --}}
                    <div class="flex justify-between items-end border-t border-emerald-500/30 pt-2 text-[8px] text-emerald-200/80 z-10">
                        <span class="font-medium tracking-wide flex items-center gap-1">
                            <i class="fa-solid fa-phone text-emerald-400"></i>{{ $guru->no_hp }}
                        </span>
                        <span class="font-mono text-emerald-400/60 tracking-wider text-[7px] uppercase">Official Professional Card</span>
                    </div>
                </div>

                {{-- SISI BELAKANG KARTU (BACK) --}}
                <div id="id-card-back" class="absolute inset-0 w-full h-full [backface-visibility:hidden] [transform:rotateY(180deg)] rounded-2xl p-5 flex flex-col justify-between bg-gradient-to-br from-slate-900 via-emerald-950 to-slate-900 text-slate-300 overflow-hidden border border-emerald-800/50">
                    <div class="absolute right-4 top-4 w-20 h-20 bg-emerald-500/10 rounded-full blur-xl pointer-events-none"></div>

                    {{-- Pita Magnetik Simulasi --}}
                    <div class="absolute top-4 left-0 w-full h-8 bg-slate-950 border-y border-emerald-900/50 flex items-center px-4">
                        <div class="w-full h-2 bg-emerald-900/30 rounded-full"></div>
                    </div>

                    {{-- Konten Alamat & Ketentuan --}}
                    <div class="mt-10 space-y-2.5 text-[8.5px] leading-relaxed">
                        <div class="bg-slate-950/60 p-2.5 rounded-xl border border-emerald-800/40 backdrop-blur-sm">
                            <span class="font-bold text-emerald-400 block mb-0.5 uppercase tracking-wide text-[7.5px] flex items-center gap-1">
                                <i class="fa-solid fa-location-dot text-emerald-400"></i> Alamat Rumah :
                            </span>
                            <p class="text-slate-200 font-medium line-clamp-2 leading-tight">{{ $guru->alamat }}</p>
                        </div>
                        
                        <div class="px-1 space-y-1 text-slate-400">
                            <p class="font-bold text-emerald-300 text-[8px]">Ketentuan Penggunaan:</p>
                            <ul class="list-disc list-inside space-y-0.5 text-[7.5px] text-slate-300">
                                <li>Kartu ini merupakan identitas resmi tenaga pendidik SIAFI.</li>
                                <li>Wajib dibawa/diakses selama kegiatan belajar mengajar berlangsung.</li>
                                <li>Hanya dapat dipergunakan oleh pemilik sah kartu ini.</li>
                            </ul>
                        </div>
                    </div>

                    {{-- Footer Sisi Belakang --}}
                    <div class="border-t border-emerald-900/60 pt-2 flex justify-between items-center text-[7.5px] text-slate-400">
                        <span>Sistem Informasi Akademik & Presensi</span>
                        <span class="font-bold tracking-widest text-emerald-400 flex items-center gap-1">
                            <i class="fa-solid fa-shield-halved text-emerald-400"></i> VERIFIED
                        </span>
                    </div>
                </div>

            </div>
        </div>
    </div>
    @endif

    {{-- GRUP FORM INFORMASI DAN PASSWORD --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
        {{-- Form Informasi Profil --}}
        <div class="p-5 sm:p-6 bg-white dark:bg-slate-900 rounded-2xl border border-emerald-100 dark:border-emerald-900/30 shadow-sm hover:shadow-md transition">
            <div class="max-w-xl">
                <div class="mb-4 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400 flex items-center justify-center font-bold text-sm">
                        <i class="fa-solid fa-user-pen"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Data Akun & Personal</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Perbarui informasi profil utama dan email Anda.</p>
                    </div>
                </div>
                <div class="border-t border-emerald-50 dark:border-slate-800 my-4"></div>
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        {{-- Form Ubah Password --}}
        <div class="p-5 sm:p-6 bg-white dark:bg-slate-900 rounded-2xl border border-emerald-100 dark:border-emerald-900/30 shadow-sm hover:shadow-md transition">
            <div class="max-w-xl">
                <div class="mb-4 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400 flex items-center justify-center font-bold text-sm">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Keamanan Sandi</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Pastikan akun Anda menggunakan kata sandi yang kuat.</p>
                    </div>
                </div>
                <div class="border-t border-emerald-50 dark:border-slate-800 my-4"></div>
                @include('profile.partials.update-password-form')
            </div>
        </div>
    </div>
</div>

<script>
    const flipCard = document.getElementById('id-card-flip');
    const btnDownload = document.getElementById('btn-download-card');

    // 1. Logika Klik untuk Memutar Kartu (Flip Effect)
    flipCard.addEventListener('click', () => {
        flipCard.classList.toggle('[transform:rotateY(180deg)]');
    });

    // 2. Logika Pembuatan Gambar & Unduh ID Card via html2canvas
    btnDownload.addEventListener('click', async (e) => {
        e.stopPropagation(); // Mencegah pemicuan efek flip saat menekan tombol unduh
        
        // Simpan posisi rotasi asli
        const hasFlip = flipCard.classList.contains('[transform:rotateY(180deg)]');
        
        // Paksa kartu kembali ke posisi depan agar tangkapan gambar tidak terbalik secara cermin
        if (hasFlip) {
            flipCard.classList.remove('[transform:rotateY(180deg)]');
            // Beri jeda animasi rotasi balik selesai sebentar
            await new Promise(resolve => setTimeout(resolve, 300));
        }

        btnDownload.disabled = true;
        btnDownload.innerText = "Memproses Gambar...";

        const targetElemen = document.getElementById('id-card-front');

        // Render HTML target menjadi elemen Canvas
        html2canvas(targetElemen, {
            scale: 3, // Meningkatkan resolusi/skala gambar agar tidak buram saat diunduh
            useCORS: true, // Izinkan cross-origin jika gambar/foto profil berada di storage eksternal
            backgroundColor: null, // Output background transparan di luar sudut lengkung (border-radius)
            logging: false
        }).then(canvas => {
            // Ubah canvas menjadi URL Data Gambar PNG
            const imageURL = canvas.toDataURL('image/png');
            
            // Buat elemen link trigger download otomatis tersembunyi
            const downloadLink = document.createElement('a');
            downloadLink.href = imageURL;
            downloadLink.download = 'ID_Card_{{ $guru ? Str::slug($guru->nama) : "Guru" }}.png';
            
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);

            // Kembalikan keadaan tombol semula
            btnDownload.disabled = false;
            btnDownload.innerHTML = `<i class="fa-solid fa-download"></i> Unduh ID Card (PNG)`;

            // Kembalikan posisi rotasi ke sisi semula pilihan user sebelum mengunduh
            if (hasFlip) {
                flipCard.classList.add('[transform:rotateY(180deg)]');
            }
        }).catch(err => {
            console.error("Gagal mengekspor kartu identitas:", err);
            alert("Gagal memproses gambar ID Card.");
            btnDownload.disabled = false;
            btnDownload.innerHTML = `<i class="fa-solid fa-download"></i> Unduh ID Card (PNG)`;
        });
    });
</script>
@endsection