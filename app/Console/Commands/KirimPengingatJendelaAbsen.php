<?php

namespace App\Console\Commands;

use App\Models\Guru;
use App\Services\AbsensiService;
use App\Services\FonnteService;
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

        // Set waktu tanggal hari ini dengan jam dari data sekolah secara aman
        $bukaAbsenMasuk = now()->setTimeFromTimeString($sekolah->jam_masuk)
            ->subMinutes($konfig->buka_absen_masuk_menit);

        $bukaAbsenPulang = now()->setTimeFromTimeString($sekolah->jam_keluar)
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

        // Optimasi: Filter guru yang memiliki jadwal aktif hari ini menggunakan database level filtering
        $queryGuru = Guru::whereHas('jadwals', function ($query) use ($hari) {
            $query->where('hari', $hari)->where('is_active', true);
        });

        if (!$queryGuru->exists()) {
            $this->info("Tidak ada guru berjadwal hari {$hari}, notifikasi {$jenis} tidak dikirim.");
            return;
        }

        $url = url('/login');
        
        // Menentukan konten teks berdasarkan jenis jendela absen
        if ($jenis === 'masuk') {
            $title = 'Absen Masuk Sudah Dibuka 🟢';
            $body = 'Jendela absen masuk sudah dibuka. Silakan lakukan absen masuk sekarang.';
            $waMessage = "*PENGINGAT ABSEN MASUK*\n\nHalo Bapak/Ibu Guru, jendela absen masuk saat ini sudah dibuka. Silakan segera melakukan absensi melalui aplikasi.\n\nLink Absensi: " . $url;
        } else {
            $title = 'Absen Pulang Sudah Dibuka 🔵';
            $body = 'Jendela absen pulang sudah dibuka. Silakan lakukan absen pulang sekarang.';
            $waMessage = "*PENGINGAT ABSEN PULANG*\n\nHalo Bapak/Ibu Guru, jam pelajaran hari ini telah usai dan jendela absen pulang sudah dibuka. Silakan melakukan absen pulang sebelum meninggalkan sekolah.\n\nLink Absensi: " . $url;
        }

        $totalTerkirimNotif = 0;
        $totalTerkirimWa = 0;

        // Memproses data secara bertahap (chunking) untuk menjaga kestabilan alokasi RAM server
        $queryGuru->chunkById(100, function ($gurus) use ($absensiService, $title, $body, $waMessage, $jenis, &$totalTerkirimNotif, &$totalTerkirimWa) {
            foreach ($gurus as $guru) {
                // 1. Proses Kirim Push Notification
                try {
                    $absensiService->kirimNotifikasiKeGuru($guru, $title, $body);
                    $totalTerkirimNotif++;
                } catch (\Throwable $e) {
                    Log::error("Gagal kirim PUSH pengingat absen {$jenis} ke guru #{$guru->id}: " . $e->getMessage());
                }

                // 2. Proses Kirim WhatsApp jika nomor HP tersedia
                if (!empty($guru->no_hp)) {
                    try {
                        $kirimWa = FonnteService::sendMessage($guru->no_hp, $waMessage);
                        if ($kirimWa) {
                            $totalTerkirimWa++;
                        }
                    } catch (\Throwable $e) {
                        Log::error("Gagal kirim WA pengingat absen {$jenis} ke guru #{$guru->id}: " . $e->getMessage());
                    }
                }
            }
        });

        $this->info("Berhasil mengirim {$totalTerkirimNotif} Push Notif dan {$totalTerkirimWa} WhatsApp untuk absen {$jenis}.");
    }
}