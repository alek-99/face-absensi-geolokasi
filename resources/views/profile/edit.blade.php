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
                <h3 class="text-sm font-bold text-[#1b1b18] dark:text-[#EDEDEC]">Kartu Identitas Digital</h3>
                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Klik kartu untuk melihat sisi belakang.</p>
            </div>
            
            {{-- Tombol Cetak / Download --}}
            <button id="btn-download-card" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition shadow-sm cursor-pointer w-full sm:w-auto justify-center">
                <i class="fa-solid fa-download"></i>
                Unduh ID Card (PNG)
            </button>
        </div>

        {{-- Perspektif 3D Flip Card Container --}}
        <div class="w-full flex justify-center [perspective:1000px]">
            <div id="id-card-flip" class="relative w-full max-w-[430px] aspect-[1.586/1] transition-transform duration-500 [transform-style:preserve-3d] cursor-pointer shadow-md rounded-2xl">
                
                {{-- SISI DEPAN KARTU (FRONT) --}}
                <div id="id-card-front" class="absolute inset-0 w-full h-full [backface-visibility:hidden] rounded-2xl p-5 flex flex-col justify-between bg-gradient-to-br from-indigo-600 to-teal-600 dark:from-[#1e1b4b] dark:to-[#0f172a] text-white overflow-hidden border border-white/10">
                    {{-- Ornamen Latar Belakang --}}
                    <div class="absolute -right-6 -bottom-6 w-32 h-32 opacity-10 rotate-12 pointer-events-none">
                        <img src="{{ asset('storage/images/logo.png') }}" alt="SIAFI Ornament" class="w-full h-full object-contain grayscale brightness-200">
                    </div>
                    <div class="absolute -left-12 -top-12 w-36 h-36 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>

                    {{-- Header Kartu --}}
                    <div class="flex items-center justify-between border-b border-white/20 pb-3 z-10">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 bg-white/90 p-1 rounded-lg">
                                <img src="{{ asset('storage/images/logo.png') }}" alt="Logo" class="w-full h-full object-contain">
                            </div>
                            <div>
                                <h4 class="text-[11px] font-black tracking-wider uppercase leading-none">SIAFI DIGITAL</h4>
                                <span class="text-[8px] text-indigo-200 dark:text-zinc-400 font-medium tracking-tight">Sistem Informasi Akademik & Presensi</span>
                            </div>
                        </div>
                        <span class="text-[9px] bg-white/10 border border-white/20 px-2 py-0.5 rounded-md font-bold tracking-widest uppercase">GURU</span>
                    </div>

                    {{-- Konten Utama Sisi Depan --}}
                    <div class="flex gap-4 items-center my-auto z-10">
                        {{-- Foto --}}
                        <div class="w-20 h-24 bg-white/95 p-1 rounded-xl shrink-0 shadow-md">
                            <img src="{{ $guru->foto_profil ? asset('storage/' . $guru->foto_profil) : asset('images/default-avatar.png') }}" 
                                 alt="Foto {{ $guru->nama }}" 
                                 class="w-full h-full object-cover rounded-lg">
                        </div>
                        {{-- Biodata Singkat --}}
                        <div class="space-y-1 overflow-hidden">
                            <span class="text-[9px] font-semibold text-teal-200 dark:text-indigo-300 tracking-wide block uppercase">NIP. {{ $guru->nip }}</span>
                            <h3 class="text-base font-bold tracking-tight leading-tight truncate">{{ $guru->nama }}</h3>
                            
                            <div class="flex gap-1.5 items-center pt-1">
                                <span class="text-[9px] bg-black/15 dark:bg-white/10 px-2 py-0.5 rounded-md text-zinc-100 font-medium">
                                    {{ $guru->jenis_kelamin == 'Laki-laki' ? 'L' : 'P' }}
                                </span>
                                <span class="text-[9px] text-indigo-100 dark:text-zinc-400 truncate">
                                    {{ $guru->tempat_lahir }}, {{ \Carbon\Carbon::parse($guru->tanggal_lahir)->translatedFormat('d M Y') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Footer Sisi Depan --}}
                    <div class="flex justify-between items-end border-t border-white/15 pt-2 text-[8px] text-indigo-100/80 dark:text-zinc-400 z-10">
                        <span class="font-medium tracking-wide"><i class="fa-solid fa-phone mr-1"></i>{{ $guru->no_hp }}</span>
                        <span class="font-mono text-white/40 tracking-wider text-[7px]">SECURE DIGITAL CARD</span>
                    </div>
                </div>

                {{-- SISI BELAKANG KARTU (BACK) --}}
                <div id="id-card-back" class="absolute inset-0 w-full h-full [backface-visibility:hidden] [transform:rotateY(180deg)] rounded-2xl p-5 flex flex-col justify-between bg-zinc-900 text-zinc-300 overflow-hidden border border-zinc-800">
                    <div class="absolute right-4 top-4 w-16 h-16 bg-zinc-800/40 rounded-full blur-xl pointer-events-none"></div>

                    {{-- Pita Magnetik Simulasi --}}
                    <div class="absolute top-4 left-0 w-full h-7 bg-zinc-800 border-y border-zinc-700/50"></div>

                    {{-- Konten Alamat & Ketentuan --}}
                    <div class="mt-9 space-y-2.5 text-[8.5px] leading-relaxed">
                        <div class="bg-zinc-950/40 p-2 rounded-xl border border-zinc-800/60">
                            <span class="font-bold text-zinc-400 block mb-0.5 uppercase tracking-wide text-[7.5px]"><i class="fa-solid fa-map-marker-alt text-rose-400 mr-1"></i>Alamat Rumah :</span>
                            <p class="text-zinc-300 font-medium line-clamp-2">{{ $guru->alamat }}</p>
                        </div>
                        
                        <div class="px-1 space-y-1 text-zinc-400">
                            <p class="font-bold text-zinc-300 text-[8px]">Ketentuan Penggunaan:</p>
                            <ul class="list-disc list-inside space-y-0.5 text-[7.5px]">
                                <li>Kartu ini merupakan identitas resmi tenaga pendidik SIAFI.</li>
                                <li>Wajib dibawa/diakses selama lingkungan sekolah berlangsung.</li>
                                <li>Gunakan QR/Barcode internal untuk pemindaian presensi cadangan.</li>
                            </ul>
                        </div>
                    </div>

                    {{-- Footer Sisi Belakang --}}
                    <div class="border-t border-zinc-800/80 pt-2 flex justify-between items-center text-[7.5px] text-zinc-500">
                        <span>Dicetak secara otomatis oleh Sistem</span>
                        <span class="font-bold tracking-widest text-zinc-400">SIAFI VERIFIED</span>
                    </div>
                </div>

            </div>
        </div>
    </div>
    @endif

    {{-- GRUP FORM INFORMASI DAN PASSWORD --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4">
        {{-- Form Informasi Profil --}}
        <div class="p-5 sm:p-6 bg-white dark:bg-[#161615] rounded-2xl border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-xs">
            <div class="max-w-xl">
                <div class="mb-4">
                    <h3 class="text-sm font-bold text-[#1b1b18] dark:text-[#EDEDEC]">Data Akun & Personal</h3>
                    <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Perbarui informasi profil utama dan email Anda.</p>
                </div>
                <div class="border-t border-gray-100 dark:border-zinc-900 my-4"></div>
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        {{-- Form Ubah Password --}}
        <div class="p-5 sm:p-6 bg-white dark:bg-[#161615] rounded-2xl border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-xs">
            <div class="max-w-xl">
                <div class="mb-4">
                    <h3 class="text-sm font-bold text-[#1b1b18] dark:text-[#EDEDEC]">Keamanan Sandi</h3>
                    <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Pastikan akun Anda menggunakan kata sandi yang panjang dan acak.</p>
                </div>
                <div class="border-t border-gray-100 dark:border-zinc-900 my-4"></div>
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