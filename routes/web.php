<?php

use App\Http\Controllers\Admin\AbsensiController as AdminAbsensiController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\GuruController as AdminGuruController;
use App\Http\Controllers\Admin\JadwalController as AdminJadwalController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\KonfigurasiAbsenController;
use App\Http\Controllers\Admin\MapelController;
use App\Http\Controllers\Admin\NotifikasiController as AdminNotifikasiController;
use App\Http\Controllers\Admin\PengaturanController;
use App\Http\Controllers\Admin\ReportSettingController;
use App\Http\Controllers\Admin\ReportTemplateController;
use App\Http\Controllers\Admin\SekolahController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Guru\AbsensiController as GuruAbsensiController;
use App\Http\Controllers\Guru\AbsenSiswaController;
use App\Http\Controllers\Guru\DashboardController as GuruDashboardController;
use App\Http\Controllers\Guru\FaceDescriptorController;
use App\Http\Controllers\Guru\JadwalController as GuruJadwalController;
use App\Http\Controllers\Guru\NotifikasiController as GuruNotifikasiController;
use App\Http\Controllers\Guru\RekapAbsenSiswaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PushSubscriptionController;
use Illuminate\Support\Facades\Route;

// --------------------------------------------------------------------------
// Public / Guest Routes
// --------------------------------------------------------------------------
Route::get('/', function () {
    return view('welcome');
});

Route::view('/offline', 'offline');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// --------------------------------------------------------------------------
// Common Authenticated User Routes
// --------------------------------------------------------------------------
Route::middleware(['auth'])->group(function () {
    Route::view('/panduan', 'panduan')->name('panduan');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Web Push Subscriptions
    Route::post('/save-push-subscription', [PushSubscriptionController::class, 'save'])
        ->name('save.push.subscription');
});

// --------------------------------------------------------------------------
// ADMIN ROUTES
// --------------------------------------------------------------------------
Route::middleware(['auth', 'role:admin'])->name('admin.')->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Notifikasi
    Route::controller(AdminNotifikasiController::class)->prefix('notifikasi')->name('notifikasi.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/halaman', 'halaman')->name('halaman');
        Route::get('/unread-count', 'unreadCount')->name('unread-count');
        Route::patch('/{notifikasi}/baca', 'markAsRead')->name('baca');
        Route::patch('/baca-semua', 'markAllAsRead')->name('baca-semua');
    });

    // Data Guru
    Route::resource('/guru', AdminGuruController::class);
    Route::put('/guru/{guru}/password', [AdminGuruController::class, 'updatePassword'])->name('guru.password');

    // Absensi Management
    Route::controller(AdminAbsensiController::class)->group(function () {
        Route::get('/pengajuan-absensi', 'index')->name('absensi.pengajuan');
        Route::get('/absensi', 'dataAbsen')->name('absensi.index');
        Route::get('/absensi/{absensi}', 'show')->name('absensi.show');
        Route::post('/absensi/{absensi}/approve', 'approve')->name('absensi.approve');
        Route::post('/absensi/{absensi}/reject', 'reject')->name('absensi.reject');
    });

    // Route Cetak Excel baru
    Route::get('/absensi/export-excel', [AdminAbsensiController::class, 'exportExcel'])->name('absensi.export-excel');
    // Sekolah & Jadwal
    Route::resource('/sekolah', SekolahController::class);
    Route::get('jadwal/cetak-pdf', [AdminJadwalController::class, 'cetakPdf'])->name('jadwal.pdf');
    Route::get('jadwal/export-excel', [AdminJadwalController::class, 'exportExcel'])->name('jadwal.exportExcel');
    Route::resource('/jadwal', AdminJadwalController::class);
    // Kelas & siswa
    Route::apiresource('/kelas', KelasController::class);
//  // siswa
    Route::resource('siswa', SiswaController::class);
    // Route Mapel
    Route::resource('/mapel',MapelController::class);
    // Konfigurasi Absen & Reports
    Route::get('/konfigurasi-absen', [KonfigurasiAbsenController::class, 'index'])->name('pengaturan.absensi');
    Route::put('/konfigurasi-absen', [KonfigurasiAbsenController::class, 'update'])->name('konfigurasi-absen.update');

    Route::get('report-setting', [ReportSettingController::class, 'index'])->name('report-setting.index');
    Route::get('report-setting/edit', [ReportSettingController::class, 'edit'])->name('report-setting.edit');
    Route::put('report-setting', [ReportSettingController::class, 'update'])->name('report-setting.update');

    Route::get('/pengaturan-sistem', [PengaturanController::class, 'index'])->name('pengaturan.index');
    Route::resource('report-template', ReportTemplateController::class)->except(['show']);
    // cetak jadwal exel
});

// --------------------------------------------------------------------------
// GURU ROUTES
// --------------------------------------------------------------------------
Route::middleware(['auth', 'role:guru'])->name('guru.')->prefix('guru')->group(function () {
    Route::get('/dashboard', [GuruDashboardController::class, 'index'])->name('dashboard');

    // Notifikasi
    Route::controller(GuruNotifikasiController::class)->prefix('notifikasi')->name('notifikasi.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{id}/read', 'read')->name('read');
        Route::post('/read-all', 'readAll')->name('readAll');
    });

    // Jadwal
    Route::get('jadwal', [GuruJadwalController::class, 'index'])->name('jadwal.index');
    Route::get('jadwal/cetak', [GuruJadwalController::class, 'cetak'])->name('jadwal.cetak');
    Route::get('/jadwal/excel', [GuruJadwalController::class, 'exportExcel'])->name('jadwal.excel');

    // Muka / Face Descriptors
    Route::prefix('wajah')->name('wajah.')->group(function () {
        Route::get('/', [FaceDescriptorController::class, 'halaman'])->name('halaman');
        Route::get('/status', [FaceDescriptorController::class, 'status'])->name('status');
        Route::post('/', [FaceDescriptorController::class, 'simpan'])->name('simpan');
        Route::delete('/', [FaceDescriptorController::class, 'hapus'])->name('hapus');
    });

Route::get('/absen-siswa', [AbsenSiswaController::class, 'halaman'])->name('absen-siswa.index');
    Route::get('/absen-siswa/{jadwal}/form', [AbsenSiswaController::class, 'halamanJadwal'])->name('absen-siswa.form');

    // API Internal Endpoint (Data Fetching & Submisi)
    Route::prefix('api/absen-siswa')->name('absen-siswa.api.')->group(function () {
        Route::get('/jadwal-hari-ini', [AbsenSiswaController::class, 'jadwalHariIni'])->name('jadwal-hari-ini');
        Route::get('/{jadwal}/siswa', [AbsenSiswaController::class, 'daftarSiswa'])->name('daftar-siswa');
        Route::post('/{jadwal}/simpan', [AbsenSiswaController::class, 'simpan'])->name('simpan');
    });

    // REKAP ABSENSI SISWA
    Route::get('/rekap-absen', [RekapAbsenSiswaController::class, 'index'])->name('rekap-absen.index');
    Route::get('/rekap-absen/{jadwal}', [RekapAbsenSiswaController::class, 'detail'])->name('rekap-absen.detail');
    Route::get('/rekap-absen/{jadwal}/export', [RekapAbsenSiswaController::class, 'exportExcel'])->name('rekap-absen.export');

    // Absensi Guru
    Route::controller(GuruAbsensiController::class)->group(function () {
        Route::get('/absensi', 'halaman')->name('absensi.index');
        Route::get('/absensi/riwayat-halaman', 'halamanRiwayat')->name('absensi.riwayat.halaman');
        Route::get('/absensi/status', 'status')->name('absensi.status');
        Route::post('/absensi/masuk', 'absenMasuk')->name('absensi.masuk');
        Route::post('/absensi/pulang', 'absenPulang')->name('absensi.pulang');
        Route::get('/absensi/riwayat', 'riwayat')->name('absensi.riwayat');
        Route::post('/absensi/ajukan-izin', 'ajukanIzin')->name('absensi.ajukan-izin');
        Route::get('/laporan/absensi', 'cetakPDF')->name('absensi.pdf');
    });
    Route::get('/absensi/cetak-excel', [GuruAbsensiController::class, 'cetakExcel'])->name('absensi.cetak-excel');
});

// --------------------------------------------------------------------------
// Auth Routes (Breeze/Fortify)
// --------------------------------------------------------------------------
require __DIR__ . '/auth.php';