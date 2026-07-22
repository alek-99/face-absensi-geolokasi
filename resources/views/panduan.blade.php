@extends('layouts.guru')
@section('page_title','Panduan Penggunaan')
@section('title','Panduan')
@section('content')

<!-- Font Awesome via Cloudflare CDN untuk Icon -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

<div class="max-w-md mx-auto px-2 py-4 space-y-6">
    
    <!-- Hero Header Dashboard Style -->
    <div class="relative overflow-hidden bg-gradient-to-br from-blue-600 via-indigo-600 to-indigo-700 text-white p-6 rounded-3xl shadow-md">
        <!-- Aksen lingkaran estetik latar belakang -->
        <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-white/10 rounded-full blur-xl"></div>
        <div class="absolute right-12 top-2 w-16 h-16 bg-white/5 rounded-full blur-lg"></div>
        
        <div class="relative z-10 flex items-center justify-between">
            <div class="space-y-1">
                <span class="inline-block bg-white/20 text-white text-[10px] font-bold uppercase tracking-widest px-2.5 py-1 rounded-full backdrop-blur-md">
                    Pusat Informasi
                </span>
                <h1 class="text-2xl font-bold tracking-tight">Panduan SIAFI</h1>
                <p class="text-xs text-blue-100/90">Ikuti langkah berikut untuk kemudahan absensi Face ID Anda.</p>
            </div>
            <div class="text-4xl text-white/20 pr-2">
                <i class="fa-solid fa-book-open-reader"></i>
            </div>
        </div>
    </div>

    <!-- Alur Langkah-Langkah (Card Grid Mobile Responsive) -->
    <div class="grid grid-cols-1 gap-4">
        
        <!-- Langkah 1 -->
        <div class="group flex gap-4 bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] p-4 rounded-2xl shadow-sm transition-all duration-200 active:scale-[0.99] active:bg-gray-50 dark:active:bg-zinc-900">
            <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xl font-bold font-mono">
                <i class="fa-solid fa-face-smile"></i>
            </div>
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-bold uppercase bg-blue-100 dark:bg-blue-950 text-blue-700 dark:text-blue-300 px-2 py-0.5 rounded">Langkah 1</span>
                    <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 flex items-center gap-1">
                        <i class="fa-solid fa-circle-check text-[10px]"></i> Wajib
                    </span>
                </div>
                <h3 class="text-sm font-bold text-[#1b1b18] dark:text-[#EDEDEC]">Registrasi Face ID</h3>
                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A] leading-relaxed">Daftarkan sampel wajah Anda terlebih dahulu di menu <span class="font-medium text-blue-600">Face ID</span> sebelum melakukan absensi pertama kali.</p>
            </div>
        </div>

        <!-- Langkah 2 -->
        <div class="group flex gap-4 bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] p-4 rounded-2xl shadow-sm transition-all duration-200 active:scale-[0.99] active:bg-gray-50 dark:active:bg-zinc-900">
            <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xl font-bold font-mono">
                <i class="fa-solid fa-clipboard-user"></i>
            </div>
            <div class="space-y-1">
                <span class="text-[10px] font-bold uppercase bg-indigo-100 dark:bg-indigo-950 text-indigo-700 dark:text-indigo-300 px-2 py-0.5 rounded">Langkah 2</span>
                <h3 class="text-sm font-bold text-[#1b1b18] dark:text-[#EDEDEC]">Menu Utama Absensi</h3>
                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A] leading-relaxed">Pergi ke menu <span class="font-medium text-indigo-600">Absensi</span> untuk melakukan scan kehadiran mandiri atau mengajukan surat izin.</p>
            </div>
        </div>

        <!-- Langkah 3 -->
        <div class="group flex gap-4 bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] p-4 rounded-2xl shadow-sm transition-all duration-200 active:scale-[0.99] active:bg-gray-50 dark:active:bg-zinc-900">
            <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 flex items-center justify-center text-xl font-bold font-mono">
                <i class="fa-solid fa-calendar-clock"></i>
            </div>
            <div class="space-y-1">
                <span class="text-[10px] font-bold uppercase bg-amber-100 dark:bg-amber-950 text-amber-700 dark:text-amber-300 px-2 py-0.5 rounded">Langkah 3</span>
                <h3 class="text-sm font-bold text-[#1b1b18] dark:text-[#EDEDEC]">Validasi Jadwal Mengajar</h3>
                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A] leading-relaxed">Sistem mengecek jadwal mengajar Anda otomatis. Absen hanya aktif jika Anda terdaftar <span class="font-medium text-amber-600">ada jam mengajar</span> di hari tersebut.</p>
            </div>
        </div>

        <!-- Langkah 4 -->
        <div class="group flex gap-4 bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] p-4 rounded-2xl shadow-sm transition-all duration-200 active:scale-[0.99] active:bg-gray-50 dark:active:bg-zinc-900">
            <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 flex items-center justify-center text-xl font-bold font-mono">
                <i class="fa-solid fa-envelope-open-text"></i>
            </div>
            <div class="space-y-1">
                <span class="text-[10px] font-bold uppercase bg-purple-100 dark:bg-purple-950 text-purple-700 dark:text-purple-300 px-2 py-0.5 rounded">Langkah 4</span>
                <h3 class="text-sm font-bold text-[#1b1b18] dark:text-[#EDEDEC]">Batasan Pengajuan Izin</h3>
                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A] leading-relaxed">Pengajuan izin dibatasi maksimal <span class="font-medium text-purple-600">1 kali per tanggal</span> yang dipilih untuk menghindari duplikasi data.</p>
            </div>
        </div>

        <!-- Langkah 5 -->
        <div class="group flex gap-4 bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] p-4 rounded-2xl shadow-sm transition-all duration-200 active:scale-[0.99] active:bg-gray-50 dark:active:bg-zinc-900">
            <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-sky-50 dark:bg-sky-950/40 text-sky-600 dark:text-sky-400 flex items-center justify-center text-xl font-bold font-mono">
                <i class="fa-solid fa-user-check"></i>
            </div>
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-bold uppercase bg-sky-100 dark:bg-sky-950 text-sky-700 dark:text-sky-300 px-2 py-0.5 rounded">Langkah 5</span>
                    <span class="text-[11px] text-sky-600 dark:text-sky-400 animate-pulse"><i class="fa-solid fa-spinner fa-spin"></i> Pending</span>
                </div>
                <h3 class="text-sm font-bold text-[#1b1b18] dark:text-[#EDEDEC]">Persetujuan Admin</h3>
                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A] leading-relaxed">Seluruh pengajuan izin tidak langsung disetujui, melainkan masuk ke antrean untuk <span class="font-medium text-sky-600">ditinjau oleh Admin</span> pusat.</p>
            </div>
        </div>

    </div>

    <!-- Tombol Bantuan / Hubungi Admin Utama (Bukan Teks Biasa) -->
    <a href="https://wa.me/6281389090873?text=Halo%20Admin%20SIAFI,%20saya%20guru%20mengalami%20kendala%20aplikasi." 
       target="_blank" rel="noopener noreferrer"
       class="flex items-center justify-between w-full bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-900/60 p-4 rounded-2xl text-emerald-800 dark:text-emerald-300 transition-all duration-200 active:scale-[0.98] hover:bg-emerald-100/50">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center text-lg shadow-sm">
                <i class="fa-brands fa-whatsapp"></i>
            </div>
            <div class="text-left">
                <h4 class="text-xs font-bold">Butuh Bantuan Langsung?</h4>
                <p class="text-[11px] text-emerald-700/80 dark:text-emerald-400/80">Hubungi WhatsApp Layanan Admin SIAFI</p>
            </div>
        </div>
        <i class="fa-solid fa-chevron-right text-xs opacity-60"></i>
    </a>

</div>

@endsection