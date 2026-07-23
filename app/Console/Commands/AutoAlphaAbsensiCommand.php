<?php

namespace App\Console\Commands;

use App\Models\Absensi;
use App\Models\Guru;
use App\Services\AbsensiService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AutoAlphaAbsensiCommand extends Command
{
    /**
     * Nama perintah artisan yang akan dipanggil
     */
    protected $signature = 'absensi:auto-alpha';

    /**
     * Deskripsi perintah
     */
    protected $description = 'Otomatis mencatat status Alpa bagi guru yang memiliki jadwal mengajar tetapi tidak melakukan absen masuk hingga batas toleransi.';

    public function __construct(protected AbsensiService $absensiService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $now = now();
        $today = $now->toDateString();

        try {
            $sekolah = $this->absensiService->getSekolah();
            $konfig = $this->absensiService->getKonfigurasi();

            // Hitung batas waktu akhir toleransi terlambat hari ini
            $jamMasukSekolah = Carbon::parse($sekolah->jam_masuk)->setDateFrom($now);
            $batasToleransi = $jamMasukSekolah->copy()->addMinutes($konfig->toleransi_terlambat_menit);

            // Keamanan: Hanya eksekusi jika waktu sekarang sudah melewati batas toleransi
            if ($now->lt($batasToleransi)) {
                $this->info("Batas toleransi absen masuk ({$batasToleransi->format('H:i')}) belum terlewati. Ditunda.");
                return Command::SUCCESS;
            }

            // Ambil semua data guru
            $gurus = Guru::all();
            $countAlpha = 0;

            foreach ($gurus as $guru) {
                // 1. Cek apakah guru punya jadwal mengajar aktif hari ini
                $jadwal = $this->absensiService->cekJadwalHariIni($guru, $now);

                if ($jadwal) {
                    // 2. Cek apakah sudah ada record absensi (masuk/izin/sakit/cuti/dll) untuk hari ini
                    $absensi = Absensi::where('guru_id', $guru->id)
                        ->where('tanggal', $today)
                        ->first();

                    // 3. Jika BELUM ada record sama sekali, buatkan record ALPA
                    if (!$absensi) {
                        Absensi::create([
                            'guru_id' => $guru->id,
                            'tanggal' => $today,
                            'status' => 'alpha',
                            'keterangan' => 'Sistem: Otomatis Alpa (Melewati batas waktu toleransi presensi masuk).',
                        ]);

                        $countAlpha++;
                    }
                }
            }

            $this->info("Proses Auto-Alpha selesai. {$countAlpha} record alpa berhasil dibuat.");
            Log::info("Cron Auto-Alpha dijalankan: {$countAlpha} guru ditandai Alpa.");

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            Log::error('Gagal menjalankan AutoAlphaAbsensiCommand: ' . $e->getMessage());
            $this->error('Terjadi kesalahan: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}