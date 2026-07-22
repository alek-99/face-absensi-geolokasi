<?php

namespace App\Services;

use App\Exceptions\AbsensiException;
use App\Models\Absensi;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\KonfigurasiAbsen;
use App\Models\Sekolah;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Services\PushNotificationService;
use App\Services\FonnteService;

class AbsensiService
{
    protected float $faceThreshold = 0.6;

    public function getSekolah(): Sekolah
    {
        $sekolah = Sekolah::first();

        if (!$sekolah) {
            throw new AbsensiException('Data sekolah belum dikonfigurasi. Hubungi admin.');
        }

        return $sekolah;
    }

    public function getKonfigurasi(): KonfigurasiAbsen
    {
        return KonfigurasiAbsen::first() ?? KonfigurasiAbsen::create([]);
    }

    public function cekJadwalHariIni(Guru $guru, ?Carbon $tanggal = null): ?Jadwal
    {
        $tanggal ??= now();
        $hari = $this->namaHariIndonesia($tanggal);

        return Jadwal::where('guru_id', $guru->id)
            ->where('hari', $hari)
            ->where('is_active', true)
            ->first();
    }

    public function namaHariIndonesia(Carbon $date): string
    {
        $map = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ];

        return $map[$date->format('l')];
    }

    public function kirimNotifikasiKeGuru(Guru $guru, string $title, string $body, ?string $url = null): void
    {
        try {
            $url = $url ?? url('/guru/absensi');

            if (!empty($guru->no_hp)) {
                $pesanWa = "Halo {$guru->nama},\n{$body}";
                FonnteService::sendMessage($guru->no_hp, $pesanWa);
            }

            if (method_exists($guru, 'device')) {
                $devices = $guru->device()->get();

                foreach ($devices as $device) {
                    if ($device->push_subscription) {
                        PushNotificationService::sendToUser($device->push_subscription, $title, $body, $url);
                    }
                    if ($device->fcm_token) {
                        PushNotificationService::sendToUser($device->fcm_token, $title, $body, $url);
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::warning('Gagal mengisolasi pengiriman notifikasi guru: ' . $e->getMessage());
        }
    }

    public function hitungJarakMeter(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371000;

        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($lonDelta / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    public function validasiLokasi(float $lat, float $lon, Sekolah $sekolah): array
    {
        $jarak = $this->hitungJarakMeter($lat, $lon, (float) $sekolah->latitude, (float) $sekolah->longitude);

        return [
            'valid' => $jarak <= $sekolah->radius,
            'jarak' => round($jarak, 2),
        ];
    }

    public function verifikasiWajah(Guru $guru, array $descriptorInput): array
    {
        $faceDescriptor = $guru->faceDescriptor;

        if (!$faceDescriptor) {
            return ['valid' => false, 'confidence' => 0.0, 'message' => 'Data wajah guru belum terdaftar.'];
        }

        $stored = $faceDescriptor->descriptor;

        if (!is_array($stored) || count($stored) !== count($descriptorInput)) {
            return ['valid' => false, 'confidence' => 0.0, 'message' => 'Format descriptor wajah tidak sesuai.'];
        }

        $sumSquares = 0.0;
        foreach ($stored as $i => $value) {
            $sumSquares += ($value - $descriptorInput[$i]) ** 2;
        }
        $distance = sqrt($sumSquares);
        $confidence = max(0.0, 1 - $distance);

        return [
            'valid' => $distance <= $this->faceThreshold,
            'confidence' => round($confidence, 4),
            'distance' => round($distance, 4),
            'message' => null,
        ];
    }

    public function absenMasuk(Guru $guru, array $data): Absensi
    {
        $result = DB::transaction(function () use ($guru, $data) {
            $now = now();
            $today = $now->toDateString();

            $jadwal = $this->cekJadwalHariIni($guru, $now);
            if (!$jadwal) {
                throw new AbsensiException('Anda tidak memiliki jadwal mengajar hari ini, sehingga tidak bisa absen masuk.');
            }

            $sudahIzin = Absensi::where('guru_id', $guru->id)
                ->where('tanggal', $today)
                ->whereIn('status', ['izin', 'sakit', 'cuti', 'tugas_luar'])
                ->exists();
            if ($sudahIzin) {
                throw new AbsensiException('Anda sudah mengajukan izin/ketidakhadiran untuk hari ini. Tidak dapat melakukan absen masuk.');
            }

            $absensi = Absensi::firstOrNew([
                'guru_id' => $guru->id,
                'tanggal' => $today,
            ]);

            if ($absensi->exists && $absensi->jam_masuk) {
                throw new AbsensiException('Anda sudah melakukan absen masuk hari ini.');
            }

            $sekolah = $this->getSekolah();
            $konfig = $this->getKonfigurasi();

            $jamMasukSekolah = Carbon::parse($sekolah->jam_masuk)->setDateFrom($now);
            $bukaAbsen = $jamMasukSekolah->copy()->subMinutes($konfig->buka_absen_masuk_menit);
            $batasTepatWaktu = $jamMasukSekolah->copy()->addMinutes($konfig->toleransi_terlambat_menit);

            if ($now->lt($bukaAbsen)) {
                throw new AbsensiException('Absen masuk belum dibuka. Dibuka pukul ' . $bukaAbsen->format('H:i') . '.');
            }

            $status = $now->lte($batasTepatWaktu) ? 'hadir' : 'terlambat';

            $lokasi = $this->validasiLokasi((float) $data['latitude'], (float) $data['longitude'], $sekolah);
            if (!$lokasi['valid']) {
                throw new AbsensiException("Anda berada di luar radius sekolah ({$lokasi['jarak']} meter dari titik sekolah).");
            }

            $wajah = $this->verifikasiWajah($guru, $data['descriptor']);
            if (!$wajah['valid']) {
                $pesan = $wajah['message'] ?? ('Verifikasi wajah gagal (kemiripan ' . round($wajah['confidence'] * 100) . '%).');
                throw new AbsensiException($pesan);
            }

            $fotoPath = null;
            if (!empty($data['foto'])) {
                $fotoPath = $this->simpanFoto($data['foto'], $guru->id, 'masuk');
            }

            $absensi->fill([
                'jam_masuk' => $now->format('H:i:s'),
                'latitude_masuk' => $data['latitude'],
                'longitude_masuk' => $data['longitude'],
                'foto_masuk' => $fotoPath,
                'confidence_masuk' => $wajah['confidence'],
                'status' => $status,
            ]);
            $absensi->save();

            return ['absensi' => $absensi->fresh(), 'status' => $status, 'now' => $now];
        });

        // Pengiriman notifikasi dijalankan setelah DB Commit
        $timeStr = $result['now']->format('H:i');
        $title = 'Absen Masuk Tercatat ✅';
        $body = "Absen masuk Anda tercatat pada pukul *{$timeStr}* (Status: {$result['status']}).";
        $this->kirimNotifikasiKeGuru($guru->fresh(), $title, $body);

        return $result['absensi'];
    }

    public function absenPulang(Guru $guru, array $data): Absensi
    {
        $result = DB::transaction(function () use ($guru, $data) {
            $now = now();
            $today = $now->toDateString();

            $absensi = Absensi::where('guru_id', $guru->id)
                ->where('tanggal', $today)
                ->first();

            if ($absensi && in_array($absensi->status, ['izin', 'sakit', 'cuti', 'tugas_luar'])) {
                throw new AbsensiException('Anda sudah mengajukan izin/ketidakhadiran untuk hari ini. Tidak dapat melakukan absen pulang.');
            }

            if (!$absensi || !$absensi->jam_masuk) {
                throw new AbsensiException('Anda belum melakukan absen masuk hari ini.');
            }

            if ($absensi->jam_pulang) {
                throw new AbsensiException('Anda sudah melakukan absen pulang hari ini.');
            }

            $sekolah = $this->getSekolah();
            $konfig = $this->getKonfigurasi();

            $jamKeluarSekolah = Carbon::parse($sekolah->jam_keluar)->setDateFrom($now);
            $bukaAbsenPulang = $jamKeluarSekolah->copy()->subMinutes($konfig->buka_absen_pulang_menit);

            if ($now->lt($bukaAbsenPulang)) {
                throw new AbsensiException('Absen pulang belum dibuka. Dibuka pukul ' . $bukaAbsenPulang->format('H:i') . '.');
            }

            $lokasi = $this->validasiLokasi((float) $data['latitude'], (float) $data['longitude'], $sekolah);
            if (!$lokasi['valid']) {
                throw new AbsensiException("Anda berada di luar radius sekolah ({$lokasi['jarak']} meter dari titik sekolah).");
            }

            $wajah = $this->verifikasiWajah($guru, $data['descriptor']);
            if (!$wajah['valid']) {
                $pesan = $wajah['message'] ?? ('Verifikasi wajah gagal (kemiripan ' . round($wajah['confidence'] * 100) . '%).');
                throw new AbsensiException($pesan);
            }

            $fotoPath = null;
            if (!empty($data['foto'])) {
                $fotoPath = $this->simpanFoto($data['foto'], $guru->id, 'pulang');
            }

            $absensi->fill([
                'jam_pulang' => $now->format('H:i:s'),
                'latitude_pulang' => $data['latitude'],
                'longitude_pulang' => $data['longitude'],
                'foto_pulang' => $fotoPath,
                'confidence_pulang' => $wajah['confidence'],
            ]);
            $absensi->save();

            return ['absensi' => $absensi->fresh(), 'now' => $now];
        });

        // Pengiriman notifikasi dijalankan setelah DB Commit
        $timeStr = $result['now']->format('H:i');
        $title = 'Absen Pulang Tercatat ✅';
        $body = "Absen pulang Anda tercatat pada pukul *{$timeStr}*. Terima kasih.";
        $this->kirimNotifikasiKeGuru($guru->fresh(), $title, $body);

        return $result['absensi'];
    }

    public function statusHariIni(Guru $guru): array
    {
        $now = now();
        $hari = $this->namaHariIndonesia($now);

        $jadwals = Jadwal::where('guru_id', $guru->id)
            ->where('hari', $hari)
            ->where('is_active', true)
            ->orderBy('jam_masuk')
            ->get();

        $adaJadwal = $jadwals->isNotEmpty();

        $absensi = Absensi::where('guru_id', $guru->id)
            ->where('tanggal', $now->toDateString())
            ->first();

        $sudahAjukanIzin = $absensi && in_array($absensi->status, ['izin', 'sakit', 'cuti', 'tugas_luar']);
        $statusPengajuan = $absensi?->status_persetujuan;

        $bisaAjukanIzin = $adaJadwal && !$absensi;
        if ($absensi && $absensi->status_persetujuan === 'ditolak') {
            $bisaAjukanIzin = true;
        }

        $sekolah = $this->getSekolah();
        $konfig = $this->getKonfigurasi();

        $jamMasukSekolah = Carbon::parse($sekolah->jam_masuk)->setDateFrom($now);
        $bukaAbsenMasuk = $jamMasukSekolah->copy()->subMinutes($konfig->buka_absen_masuk_menit);
        $batasTepatWaktu = $jamMasukSekolah->copy()->addMinutes($konfig->toleransi_terlambat_menit);

        $jamKeluarSekolah = Carbon::parse($sekolah->jam_keluar)->setDateFrom($now);
        $bukaAbsenPulang = $jamKeluarSekolah->copy()->subMinutes($konfig->buka_absen_pulang_menit);

        return [
            'guru' => [
                'id' => $guru->id,
                'nama' => $guru->nama,
                'nip' => $guru->nip,
                'foto_profil' => $guru->foto_profil,
            ],
            'tanggal' => $now->toDateString(),
            'hari' => $hari,
            'ada_jadwal_mengajar' => $adaJadwal,
            'jadwal' => $jadwals->map(function ($jadwal) {
                return [
                    'id' => $jadwal->id,
                    'mata_pelajaran' => $jadwal->mata_pelajaran,
                    'kelas' => $jadwal->kelas,
                    'ruangan' => $jadwal->ruangan,
                    'jam_masuk' => $jadwal->jam_masuk,
                    'jam_pulang' => $jadwal->jam_pulang,
                ];
            })->values(),
            'sekolah' => [
                'nama_sekolah' => $sekolah->nama_sekolah,
                'latitude' => $sekolah->latitude,
                'longitude' => $sekolah->longitude,
                'radius' => $sekolah->radius,
                'jam_masuk' => $sekolah->jam_masuk,
                'jam_keluar' => $sekolah->jam_keluar,
            ],
            'time_windows' => [
                'buka_absen_masuk' => $bukaAbsenMasuk->format('H:i:s'),
                'batas_tepat_waktu' => $batasTepatWaktu->format('H:i:s'),
                'buka_absen_pulang' => $bukaAbsenPulang->format('H:i:s'),
            ],
            'konfigurasi' => [
                'buka_absen_masuk_menit' => $konfig->buka_absen_masuk_menit,
                'toleransi_terlambat_menit' => $konfig->toleransi_terlambat_menit,
                'buka_absen_pulang_menit' => $konfig->buka_absen_pulang_menit,
            ],
            'sudah_absen_masuk' => (bool) ($absensi?->jam_masuk),
            'sudah_absen_pulang' => (bool) ($absensi?->jam_pulang),
            'sudah_ajukan_izin' => $sudahAjukanIzin,
            'status_pengajuan' => $statusPengajuan,
            'bisa_ajukan_izin' => $bisaAjukanIzin,
            'absensi' => $absensi,
        ];
    }

    protected function simpanFoto(string $base64Image, int $guruId, string $tipe): string
    {
        $ext = 'jpg';

        if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $matches)) {
            $ext = $matches[1];
            $base64Image = substr($base64Image, strpos($base64Image, ',') + 1);
        }

        $imageData = base64_decode($base64Image);
        if ($imageData === false) {
            throw new AbsensiException('Foto absen tidak valid.');
        }

        $filename = "absensi/{$guruId}/{$tipe}_" . now()->format('Ymd_His') . "_" . uniqid() . ".{$ext}";
        Storage::disk('public')->put($filename, $imageData);

        return $filename;
    }
}