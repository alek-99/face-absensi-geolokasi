# TODO: Perbaikan Bug Absensi Guru ✅ SELESAI

## Step 1: Perbaiki `app/Services/AbsensiService.php` ✅
- [x] Tambah field `sudah_ajukan_izin`, `status_pengajuan`, `bisa_ajukan_izin` di response `statusHariIni()`
- [x] Tambah pengecekan izin di `absenMasuk()` — blokir jika sudah ada izin hari ini
- [x] Tambah pengecekan izin di `absenPulang()` — blokir jika sudah ada izin hari ini

## Step 2: Perbaiki `app/Http/Controllers/Guru/AbsensiController.php` ✅
- [x] Tambah try-catch di `status()` → cegah error 500 "Gagal Memuat Data"

## Step 3: Perbaiki `public/js/absensi.js` ✅
- [x] Integrasikan `sudah_ajukan_izin` dari response status
- [x] Update `renderStatusBannerDanAksi()` — handle status pengajuan izin
- [x] Tambah retry logic saat fetch status gagal
- [x] Dispatch custom event `absensi-status-update` untuk komunikasi ke Blade

## Step 4: Perbaiki `resources/views/guru/absensi/index.blade.php` ✅
- [x] Sembunyikan tombol "Ajukan Izin" jika tidak ada jadwal & tidak bisa ajukan
- [x] Listen custom event `absensi-status-update` untuk update tombol izin
- [x] Kontrol visibility tombol berdasarkan data dari status API

