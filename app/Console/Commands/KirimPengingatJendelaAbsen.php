<?php

namespace App\Console\Commands;

use App\Models\Guru;
use App\Services\AbsensiService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class KirimPengingatJendelaAbsen extends Command
{
    /**
     * Dijadwalkan jalan tiap menit.
     * Mengecek apakah waktu SEKARANG sama dengan jam mulai jendela absen.
     */
    protected $signature = 'absensi:pengingat-jendela';

    protected $description = 'Kirim notifikasi WA & push ke guru saat jendela absen masuk/pulang mulai dibuka';

    public function handle(AbsensiService $absensiService): int
    {
        $now = now();
        $today = $now->toDateString();

        try {
            $sekolah = $absensiService->getSekolah();
            $konfig = $absensiService->getKonfigurasi();
        } catch (\Throwable $e) {
            Log::warning('Pengingat jendela absen dilewati: ' . $e->getMessage());
            return self::SUCCESS;
        }

        // Kalkulasi jam buka absen secara presisi
        $bukaAbsenMasuk = Carbon::parse($sekolah->jam_masuk)
            ->setDateFrom($now)
            ->subMinutes($konfig->buka_absen_masuk_menit);

        $bukaAbsenPulang = Carbon::parse($sekolah->jam_keluar)
            ->setDateFrom($now)
            ->subMinutes($konfig->buka_absen_pulang_menit);

        // Bandingkan format H:i (jam:menit)
        if ($now->format('H:i') === $bukaAbsenMasuk->format('H:i')) {
            $this->kirimSekaliSehari($absensiService, 'masuk', $today);
        }

        if ($now->format('H:i') === $bukaAbsenPulang->format('H:i')) {
            $this->kirimSekaliSehari($absensiService, 'pulang', $today);
        }

        return self::SUCCESS;
    }

    protected function kirimSekaliSehari(AbsensiService $absensiService, string $jenis, string $today): void
    {
        $cacheKey = "notif_buka_absen_{$jenis}:{$today}";

        if (Cache::has($cacheKey)) {
            return;
        }

        $this->kirimKeGuruBerjadwalHariIni($absensiService, $jenis);

        // Simpan cache sampai akhir hari untuk mencegah pengiriman ganda (idempotent)
        Cache::put($cacheKey, true, now()->endOfDay());

        $this->info("Notifikasi buka absen {$jenis} terkirim pada " . now()->format('H:i'));
    }

    protected function kirimKeGuruBerjadwalHariIni(AbsensiService $absensiService, string $jenis): void
    {
        $hari = $absensiService->namaHariIndonesia(now());

        // Optimasi: Filter guru yang memiliki jadwal aktif hari ini
        $queryGuru = Guru::whereHas('jadwals', function ($query) use ($hari) {
            $query->where('hari', $hari)->where('is_active', true);
        });

        if (!$queryGuru->exists()) {
            $this->info("Tidak ada guru berjadwal hari {$hari}, notifikasi {$jenis} tidak dikirim.");
            return;
        }

        $url = url('/guru/absensi');

        // Menentukan konten teks berdasarkan jenis jendela absen
        if ($jenis === 'masuk') {
            $title = 'Absen Masuk Sudah Dibuka 🟢';
            $body = "Halo Bapak/Ibu, jendela absen masuk saat ini sudah dibuka. Silakan segera melakukan absensi melalui aplikasi.\n\nLink: {$url}";
        } else {
            $title = 'Absen Pulang Sudah Dibuka 🔵';
            $body = "Halo Bapak/Ibu, jam pelajaran hari ini telah usai dan jendela absen pulang sudah dibuka. Silakan melakukan absen pulang.\n\nLink: {$url}";
        }

        $totalTerkirim = 0;

        // Memproses data secara bertahap (chunking)
        $queryGuru->chunkById(100, function ($gurus) use ($absensiService, $title, $body, $jenis, &$totalTerkirim) {
            foreach ($gurus as $guru) {
                try {
                    // kirimNotifikasiKeGuru SUDAH menangani Push Notification & Fonnte WhatsApp sekaligus!
                    $absensiService->kirimNotifikasiKeGuru($guru, $title, $body, url('/guru/absensi'));
                    $totalTerkirim++;
                } catch (\Throwable $e) {
                    Log::error("Gagal kirim notifikasi pengingat absen {$jenis} ke guru #{$guru->id}: " . $e->getMessage());
                }
            }
        });

        $this->info("Berhasil memproses notifikasi (Push & WA) untuk {$totalTerkirim} guru pada absen {$jenis}.");
    }
}