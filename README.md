# Sistem Informasi Absensi Guru — Face Recognition & Geolocation

<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="350" alt="Laravel Logo">
</p>

<h3 align="center">Sistem Informasi Manajemen Absensi Guru Berbasis Web</h3>

<p align="center">
  Sistem absensi digital dengan teknologi pengenalan wajah dan validasi lokasi untuk membantu lembaga pendidikan mengelola kehadiran guru secara lebih efektif, terstruktur, dan terintegrasi.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-Framework-FF2D20?style=for-the-badge&logo=laravel&logoColor=white">
  <img src="https://img.shields.io/badge/PHP-Backend-777BB4?style=for-the-badge&logo=php&logoColor=white">
  <img src="https://img.shields.io/badge/Tailwind_CSS-UI-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white">
  <img src="https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white">
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Face_Recognition-Face_JS-6366F1?style=flat-square">
  <img src="https://img.shields.io/badge/Authentication-Laravel_Breeze-FF2D20?style=flat-square">
  <img src="https://img.shields.io/badge/Export-Maatwebsite_Excel-217346?style=flat-square">
  <img src="https://img.shields.io/badge/WhatsApp-Fonnte-25D366?style=flat-square">
  <img src="https://img.shields.io/badge/PWA-Service_Worker-5A0FC8?style=flat-square">
  <img src="https://img.shields.io/badge/Status-Pengembangan-orange?style=flat-square">
</p>

---

## Tentang Proyek

**Sistem Informasi Absensi Guru** merupakan aplikasi berbasis web yang dikembangkan untuk membantu lembaga pendidikan dalam mengelola proses kehadiran guru secara digital.

Aplikasi ini mengintegrasikan teknologi **Face Recognition (Pengenalan Wajah)** dan **Geolocation (Lokasi GPS)** sebagai metode validasi kehadiran guru. Dengan integrasi tersebut, proses absensi dapat dilakukan berdasarkan identitas wajah, lokasi pengguna, serta jadwal mengajar yang telah ditentukan.

Sistem memiliki dua jenis hak akses utama, yaitu Admin dan Guru. Admin bertanggung jawab mengelola seluruh data operasional dan memantau aktivitas absensi, sedangkan Guru dapat melakukan absensi, mengelola kehadiran siswa, serta mengajukan izin melalui aplikasi.

Proyek ini dikembangkan sebagai bagian dari inisiatif startup teknologi pendidikan untuk menghadirkan solusi digital dalam pengelolaan administrasi dan kehadiran tenaga pendidik.

## Fitur Utama

### 1. Dashboard Admin

Dashboard administrator dirancang sebagai pusat kendali seluruh aktivitas sistem.

- Ringkasan jumlah guru dan siswa.
- Statistik kehadiran guru.
- Monitoring aktivitas absensi.
- Informasi guru yang hadir, terlambat, izin, dan sakit.
- Ringkasan pengajuan izin guru.
- Statistik absensi berdasarkan periode.
- Visualisasi data kehadiran.
- Tampilan dashboard modern dan responsif.

### 2. Manajemen Akun Guru

Admin memiliki akses untuk mengelola seluruh akun guru.

- Menambahkan akun guru.
- Mengedit informasi guru.
- Menghapus akun guru.
- Mengelola identitas dan data pribadi guru.
- Mengelola informasi akun login.
- Mengatur status akun guru.

### 3. Manajemen Data Siswa

Pengelolaan data siswa yang digunakan dalam proses absensi kelas.

- Menambahkan data siswa.
- Mengedit informasi siswa.
- Menghapus data siswa.
- Mengelola identitas siswa.
- Mengelola data kelas.
- Melihat daftar siswa berdasarkan kelas.

### 4. Manajemen Mata Pelajaran

Mengelola seluruh data mata pelajaran yang digunakan dalam kegiatan belajar mengajar.

- Menambahkan mata pelajaran.
- Mengedit informasi mata pelajaran.
- Menghapus mata pelajaran.
- Mengelola kode dan nama mata pelajaran.
- Mengatur status mata pelajaran.

### 5. Manajemen Jadwal Mengajar

Admin dapat mengatur jadwal mengajar guru berdasarkan mata pelajaran dan kelas.

- Menambahkan jadwal mengajar.
- Mengedit jadwal.
- Menghapus jadwal.
- Menghubungkan guru dengan mata pelajaran.
- Menentukan kelas yang diajar.
- Mengatur hari dan jam mengajar.
- Mengatur tahun ajaran dan semester.
- Melihat daftar jadwal mengajar.

### 6. Pengaturan Absensi

Admin dapat mengatur ketentuan absensi sesuai kebijakan lembaga pendidikan.

- Mengatur batas waktu absensi.
- Mengatur toleransi keterlambatan.
- Mengatur waktu mulai dan berakhir absensi.
- Mengatur ketentuan absensi berdasarkan jadwal.
- Mengelola konfigurasi validasi kehadiran.

### 7. Pengaturan Geolokasi

Fitur validasi lokasi untuk memastikan proses absensi dilakukan di area yang telah ditentukan.

- Mengatur koordinat lokasi lembaga.
- Menentukan titik latitude dan longitude.
- Mengatur radius maksimal absensi.
- Memvalidasi jarak pengguna dari lokasi lembaga.
- Menggunakan GPS perangkat untuk mendapatkan lokasi pengguna.

### 8. Monitoring Absensi Guru

Admin dapat memantau aktivitas kehadiran guru secara terpusat.

- Melihat riwayat absensi guru.
- Memantau status kehadiran.
- Melihat waktu absensi.
- Melihat informasi keterlambatan.
- Memantau pengajuan izin dan sakit.
- Melakukan pencarian dan penyaringan data absensi.

### 9. Notifikasi WhatsApp

Integrasi Fonnte WhatsApp Gateway untuk mendukung penyampaian informasi secara otomatis.

- Notifikasi pengajuan izin guru.
- Informasi pengajuan izin kepada administrator.
- Pemberitahuan terkait aktivitas pengajuan izin.
- Mendukung komunikasi administrasi melalui WhatsApp.

### 10. Cetak dan Ekspor Laporan

Sistem menyediakan fitur ekspor data untuk mempermudah administrasi sekolah.

- Ekspor laporan absensi guru ke Excel.
- Rekapitulasi kehadiran berdasarkan periode.
- Penyaringan data berdasarkan tanggal.
- Laporan kehadiran guru.
- Format laporan yang mudah diolah kembali.

---

## Fitur Guru

### 1. Dashboard Guru

Guru memiliki dashboard pribadi untuk memantau aktivitas mengajar dan kehadiran.

- Ringkasan jadwal mengajar.
- Informasi mata pelajaran.
- Informasi kelas yang diajar.
- Ringkasan riwayat absensi.
- Informasi status kehadiran.
- Informasi pengajuan izin.
- Tampilan dashboard responsif.

### 2. Absensi Guru dengan Face Recognition

Fitur absensi menggunakan teknologi pengenalan wajah sebagai metode validasi identitas pengguna.

- Melakukan absensi menggunakan kamera perangkat.
- Validasi wajah menggunakan teknologi Face Recognition.
- Pencocokan wajah dengan data yang telah terdaftar.
- Mengurangi potensi penyalahgunaan identitas.
- Menampilkan hasil validasi wajah sebelum absensi diproses.

### 3. Absensi Guru Berbasis Geolokasi

Absensi guru juga menggunakan validasi lokasi GPS.

- Mengakses lokasi perangkat.
- Memvalidasi posisi pengguna.
- Memastikan pengguna berada dalam radius lokasi lembaga.
- Menyesuaikan proses absensi dengan lokasi yang telah ditentukan.
- Mendukung proses absensi berdasarkan jadwal mengajar.

### 4. Absensi Berdasarkan Jadwal Mengajar

Proses absensi disesuaikan dengan jadwal mengajar yang telah dibuat administrator.

- Melihat jadwal mengajar harian.
- Melakukan absensi sesuai jadwal.
- Menampilkan informasi waktu mengajar.
- Memantau status kehadiran.
- Menyesuaikan proses absensi dengan ketentuan waktu yang berlaku.

### 5. Absensi Siswa

Guru dapat melakukan pencatatan kehadiran siswa berdasarkan kelas yang diajar.

- Melihat daftar siswa berdasarkan kelas.
- Melakukan absensi siswa secara langsung.
- Mencatat status kehadiran siswa.
- Mengelola data kehadiran siswa.
- Melihat riwayat absensi siswa.

### 6. Ekspor Laporan Absensi Siswa

Guru dapat menghasilkan laporan kehadiran siswa berdasarkan periode tertentu.

- Ekspor laporan absensi bulanan.
- Ekspor laporan absensi per semester.
- Rekapitulasi kehadiran siswa.
- Penyaringan laporan berdasarkan kelas.
- Format laporan Excel.

### 7. Pengajuan Izin

Guru dapat mengajukan izin apabila tidak dapat melaksanakan kegiatan mengajar.

- Mengirim pengajuan izin.
- Mengajukan izin sakit.
- Menambahkan keterangan pengajuan.
- Memantau status pengajuan.
- Melihat riwayat pengajuan izin.
- Mendapatkan informasi proses pengajuan.

---

## Sistem Hak Akses

Aplikasi menggunakan dua jenis peran pengguna dengan hak akses yang berbeda.

| Hak Akses | Admin | Guru |
|---|:---:|:---:|
| Dashboard | Ya | Ya |
| Manajemen akun guru | Ya | - |
| Manajemen data siswa | Ya | - |
| Manajemen mata pelajaran | Ya | - |
| Manajemen jadwal mengajar | Ya | - |
| Pengaturan absensi | Ya | - |
| Pengaturan geolokasi | Ya | - |
| Monitoring absensi guru | Ya | - |
| Notifikasi pengajuan izin | Ya | - |
| Absensi guru | - | Ya |
| Validasi pengenalan wajah | - | Ya |
| Validasi geolokasi | - | Ya |
| Absensi siswa | - | Ya |
| Ekspor laporan siswa | - | Ya |
| Pengajuan izin | - | Ya |

---

## Teknologi yang Digunakan

| Teknologi | Kegunaan |
|---|---|
| Laravel | Kerangka kerja pengembangan aplikasi |
| PHP | Bahasa pemrograman sisi server |
| Blade | Pengelolaan tampilan aplikasi |
| Tailwind CSS | Pengembangan antarmuka pengguna |
| JavaScript | Interaktivitas aplikasi |
| MySQL | Pengelolaan basis data |
| Laravel Breeze | Sistem autentikasi |
| Face Recognition JS | Pengenalan dan pencocokan wajah |
| Maatwebsite Excel | Ekspor laporan ke Excel |
| Fonnte Gateway | Integrasi notifikasi WhatsApp |
| Service Worker | Dukungan fitur Progressive Web App |

---

## Keunggulan Sistem

Beberapa aspek yang menjadi fokus pengembangan aplikasi ini:

- **Validasi Identitas:** Memanfaatkan teknologi pengenalan wajah untuk mendukung proses verifikasi identitas guru.
- **Validasi Lokasi:** Menggunakan GPS untuk memastikan absensi dilakukan di area yang telah ditentukan.
- **Berbasis Jadwal:** Proses kehadiran disesuaikan dengan jadwal mengajar guru.
- **Administrasi Terpusat:** Pengelolaan data guru, siswa, mata pelajaran, dan jadwal dilakukan melalui satu sistem.
- **Pelaporan Digital:** Mempermudah pembuatan laporan absensi dalam format Excel.
- **Integrasi WhatsApp:** Mendukung penyampaian informasi pengajuan izin melalui WhatsApp.
- **Dukungan PWA:** Memanfaatkan Service Worker untuk mendukung pengalaman aplikasi web yang lebih praktis pada perangkat pengguna.

---

## Cara Instalasi dan Menjalankan Proyek

Berikut langkah-langkah untuk menjalankan aplikasi pada lingkungan pengembangan lokal.

### 1. Kloning Repositori

```bash
git clone https://github.com/alek-99/face-absensi-geolokasi.git

cd face-absensi-geolokasi
```

Ganti alamat repositori dengan alamat GitHub proyek yang sebenarnya.

### 2. Instal Dependensi Laravel

```bash
composer install
```

### 3. Instal Dependensi Frontend

```bash
npm install
```

### 4. Konfigurasi Environment

Salin file `.env.example` menjadi `.env`.

```bash
cp .env.example .env
```

Sesuaikan konfigurasi database pada file `.env`.

```env
APP_NAME="Sistem Absensi Guru"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=absensi_guru
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Konfigurasi Fonnte WhatsApp Gateway

Tambahkan token API Fonnte ke dalam file `.env`.

```env
FONNTE_TOKEN=your_fonnte_api_token
```

Gunakan token API yang valid dan jangan membagikannya ke repositori publik.

### 7. Jalankan Migrasi Database

```bash
php artisan migrate
```

Jika proyek menyediakan data awal melalui seeder, jalankan:

```bash
php artisan db:seed
```

### 8. Build Frontend

Untuk menjalankan proses pengembangan frontend:

```bash
npm run dev
```

Untuk membuat hasil build frontend:

```bash
npm run build
```

### 9. Jalankan Aplikasi Laravel

```bash
php artisan serve
```

Buka browser dan akses:

```text
http://127.0.0.1:8000
```

### 10. Menjalankan Aplikasi dalam Mode Pengembangan

Apabila ingin menjalankan server Laravel dan Vite secara bersamaan, buka dua terminal.

Terminal pertama:

```bash
php artisan serve
```

Terminal kedua:

```bash
npm run dev
```

Pastikan konfigurasi kamera, GPS, database, dan layanan eksternal sudah tersedia sebelum melakukan pengujian fitur absensi.

---

## Catatan Penting: Status Pengembangan

> **Startup Development Project — Belum Siap untuk Penggunaan Produksi**

Aplikasi ini merupakan proyek pengembangan startup di bidang teknologi pendidikan yang masih dalam tahap pengembangan, pengujian, dan penyempurnaan fitur.

Masih terdapat sejumlah fitur yang perlu dikembangkan serta diperbaiki karena keterbatasan infrastruktur dan belum tersedianya server khusus untuk melakukan pengujian secara menyeluruh.

Beberapa hal yang perlu menjadi perhatian:

- Pengujian sistem dalam lingkungan produksi belum dilakukan secara menyeluruh.
- Skalabilitas aplikasi belum dapat dipastikan.
- Performa sistem ketika menangani banyak pengguna secara bersamaan belum teruji.
- Akurasi pengenalan wajah masih memerlukan pengujian pada berbagai kondisi kamera dan pencahayaan.
- Validasi geolokasi memerlukan pengujian pada berbagai perangkat dan kondisi GPS.
- Integrasi WhatsApp Gateway bergantung pada ketersediaan layanan Fonnte.
- Keamanan dan optimasi aplikasi masih memerlukan pengembangan lebih lanjut.
- Dukungan PWA dan kompatibilitas perangkat masih perlu diuji secara berkala.

Aplikasi ini belum direkomendasikan untuk digunakan sebagai sistem absensi operasional resmi sebelum melalui pengujian keamanan, validasi data, dan pengujian fungsional secara menyeluruh.

---

## Rencana Pengembangan

Beberapa pengembangan yang direncanakan untuk menyempurnakan sistem:

- [ ] Pengujian sistem pada server produksi.
- [ ] Optimasi performa dan skalabilitas.
- [ ] Peningkatan akurasi pengenalan wajah.
- [ ] Pengembangan fitur deteksi keaslian wajah (Liveness Detection).
- [ ] Peningkatan keamanan autentikasi.
- [ ] Pengembangan sistem notifikasi yang lebih lengkap.
- [ ] Penyempurnaan laporan dan analisis kehadiran.
- [ ] Pengembangan riwayat aktivitas pengguna.
- [ ] Penyempurnaan tampilan pada perangkat seluler.
- [ ] Pengujian kompatibilitas PWA pada berbagai perangkat.

---

## Penutup

Sistem Informasi Absensi Guru ini dikembangkan sebagai upaya menghadirkan solusi digital yang dapat membantu lembaga pendidikan dalam mengelola kehadiran tenaga pendidik secara lebih modern.

Melalui kombinasi teknologi pengenalan wajah, geolokasi, dan manajemen data terintegrasi, aplikasi ini diharapkan dapat menjadi fondasi pengembangan sistem administrasi pendidikan yang lebih efektif.

**Dibangun sebagai proyek startup, dikembangkan melalui eksperimen, dan terus disempurnakan melalui proses belajar.**

<p align="center">
  <h2 align="center">SISTEM ABSENSI GURU</h2>
  <p align="center">
    Digitalisasi Kehadiran.<br>
    Validasi Identitas.<br>
    Pengelolaan Pendidikan yang Lebih Modern.
  </p>
</p>

---

<p align="center">
  Dikembangkan dengan Laravel dan semangat untuk terus belajar. Jangan Lupa START nya yah HEHE.
</p>

## Support This Project

Jika proyek ini menarik, bermanfaat, atau memberikan inspirasi bagi Anda, jangan ragu untuk memberikan **Star (GitHub)** pada repository ini.

Setiap star merupakan bentuk apresiasi kecil yang sangat berarti bagi pengembangan proyek open-source. Dukungan Anda menjadi motivasi untuk terus belajar, bereksperimen, memperbaiki, dan menghadirkan inovasi yang lebih baik.

<p align="center">
  <a href="https://github.com/alek-99/face-absensi-geolokasi">
    <img src="https://img.shields.io/badge/Star%20This%20Repository-GitHub-181717?style=for-the-badge&logo=github" alt="Star this repository" />
  </a>
</p>

<p align="center">
  <i>"Behind every great project, there are people who believe in the value of sharing, learning, and building together."</i>
</p>

<p align="center">
  <i>"Satu klik untuk memberikan star, satu semangat baru untuk terus berkarya."</i>
</p>

---

<p align="center">
  <strong>SISTEM INFORMASI ABSENSI GURU</strong>
  <br />
  <sub>Digital Attendance. Smarter Education. Better Future.</sub>
  <br />
  <br />
  Built with Laravel, Passion, and Continuous Learning.
  <br />
  <br />
  Jangan lupa kasih star-nya, ya! Hehe.
</p>
