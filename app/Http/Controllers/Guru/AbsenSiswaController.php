<?php

namespace App\Http\Controllers\Guru;

use App\Exceptions\AbsenSiswaException;
use App\Http\Controllers\Controller;
use App\Models\AbsenSiswa;
use App\Models\Jadwal;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AbsenSiswaController extends Controller
{
    protected const STATUS_VALID = ['Hadir', 'Izin', 'Sakit', 'Alfa'];
    protected const STATUS_WAJIB_KETERANGAN = ['Izin', 'Sakit'];
    protected const MAKS_HARI_KOREKSI_MUNDUR = 14;

    protected function guruAktif()
    {
        return Auth::user()?->guru;
    }

    protected function namaHariIndonesia(Carbon $date): string
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

        return $map[$date->format('l')] ?? 'Minggu';
    }

    protected function jadwalMilikGuru(int $jadwalId, $guru): Jadwal
    {
        $jadwal = Jadwal::with(['mapel', 'kelas'])->find($jadwalId);

        if (!$jadwal) {
            throw new AbsenSiswaException('Jadwal tidak ditemukan.', 404);
        }

        if ((int) $jadwal->guru_id !== (int) $guru->id) {
            throw new AbsenSiswaException('Jadwal ini bukan milik Anda.', 403);
        }

        if (!$jadwal->is_active) {
            throw new AbsenSiswaException('Jadwal ini sudah tidak aktif.', 422);
        }

        if (!$jadwal->kelas_id) {
            throw new AbsenSiswaException('Jadwal ini belum terhubung dengan kelas manapun.', 422);
        }

        return $jadwal;
    }

    protected function resolveTanggal(?string $tanggalInput, Jadwal $jadwal): Carbon
    {
        try {
            $tanggal = $tanggalInput 
                ? Carbon::createFromFormat('Y-m-d', $tanggalInput)->startOfDay() 
                : now()->startOfDay();
        } catch (\Throwable $e) {
            throw new AbsenSiswaException('Format tanggal harus YYYY-MM-DD.', 422);
        }

        if ($tanggal->gt(now()->endOfDay())) {
            throw new AbsenSiswaException('Tidak dapat mengisi/mengubah absen untuk tanggal di masa depan.', 422);
        }

        $hariTanggal = $this->namaHariIndonesia($tanggal);
        if ($hariTanggal !== $jadwal->hari) {
            throw new AbsenSiswaException(
                "Tanggal tersebut jatuh pada hari {$hariTanggal}, sedangkan jadwal ini untuk hari {$jadwal->hari}.",
                422
            );
        }

        if ($tanggal->lt(now()->subDays(self::MAKS_HARI_KOREKSI_MUNDUR)->startOfDay())) {
            throw new AbsenSiswaException('Tanggal terlalu jauh di masa lalu untuk diabsen/diubah.', 422);
        }

        return $tanggal;
    }

    public function halaman()
    {
        $guru = $this->guruAktif();
        abort_unless($guru, 403, 'Akun ini tidak terhubung dengan data guru.');

        return view('guru.absen-siswa.index');
    }

    public function halamanJadwal(int $jadwal)
    {
        $guru = $this->guruAktif();
        abort_unless($guru, 403, 'Akun ini tidak terhubung dengan data guru.');

        try {
            $jadwalModel = $this->jadwalMilikGuru($jadwal, $guru);
        } catch (AbsenSiswaException $e) {
            abort($e->status(), $e->getMessage());
        }

        return view('guru.absen-siswa.form', ['jadwal' => $jadwalModel]);
    }

   public function jadwalHariIni(Request $request): JsonResponse
{
    $guru = $this->guruAktif();
    if (!$guru) {
        return response()->json(['message' => 'Akun ini tidak terhubung dengan data guru.'], 403);
    }

    try {
        $tanggalInput = $request->query('tanggal');
        $tanggal = $tanggalInput 
            ? Carbon::createFromFormat('Y-m-d', $tanggalInput)->startOfDay() 
            : now()->startOfDay();
    } catch (\Throwable $e) {
        return response()->json(['message' => 'Format tanggal tidak valid (gunakan YYYY-MM-DD).'], 422);
    }

    $hari = $this->namaHariIndonesia($tanggal);

    try {
        // OPTIMASI: Hitung total_siswa dan sudah_diabsen langsung di level SQL Query (Zero N+1 Query)
        $jadwals = Jadwal::with(['mapel:id,nama_mapel', 'kelas:id,nama_kelas'])
            ->withCount([
                'siswas as total_siswa',
                'absensiswa as sudah_diabsen' => function ($query) use ($tanggal) {
                    $query->whereDate('tanggal', $tanggal->toDateString());
                }
            ])
            ->where('guru_id', $guru->id)
            ->where('hari', $hari)
            ->where('is_active', true)
            ->orderBy('jam_masuk')
            ->get();

        $data = $jadwals->map(function (Jadwal $jadwal) {
            $totalSiswa = (int) $jadwal->total_siswa;
            $sudahDiabsen = (int) $jadwal->sudah_diabsen;

            return [
                'id' => $jadwal->id,
                'mapel_id' => $jadwal->mapel_id,
                'nama_mapel' => $jadwal->mapel?->nama_mapel ?? '-',
                'kelas_id' => $jadwal->kelas_id,
                'nama_kelas' => $jadwal->kelas?->nama_kelas ?? '-',
                'jam_masuk' => $jadwal->jam_masuk?->format('H:i') ?? '-',
                'jam_pulang' => $jadwal->jam_pulang?->format('H:i') ?? '-',
                'total_siswa' => $totalSiswa,
                'sudah_diabsen' => $sudahDiabsen,
                'selesai' => $totalSiswa > 0 && $sudahDiabsen >= $totalSiswa,
            ];
        })->values();

        return response()->json([
            'data' => [
                'tanggal' => $tanggal->toDateString(),
                'hari' => $hari,
                'jadwal' => $data,
            ],
        ]);
    } catch (\Throwable $e) {
        Log::error('Error AbsenSiswaController@jadwalHariIni: ' . $e->getMessage());
        return response()->json(['message' => 'Gagal memuat jadwal.'], 500);
    }
}

    public function daftarSiswa(Request $request, int $jadwal): JsonResponse
    {
        $guru = $this->guruAktif();
        if (!$guru) {
            return response()->json(['message' => 'Akun ini tidak terhubung dengan data guru.'], 403);
        }

        try {
            $jadwalModel = $this->jadwalMilikGuru($jadwal, $guru);
            $tanggal = $this->resolveTanggal($request->query('tanggal'), $jadwalModel);

            $siswas = Siswa::where('kelas_id', $jadwalModel->kelas_id)
                ->orderBy('nama')
                ->get(['id', 'nama', 'nisn']);

            $existing = AbsenSiswa::where('jadwal_id', $jadwalModel->id)
                ->whereDate('tanggal', $tanggal)
                ->get()
                ->keyBy('siswa_id');

            $daftarSiswa = $siswas->map(function (Siswa $siswa) use ($existing) {
                $absen = $existing->get($siswa->id);

                return [
                    'siswa_id' => $siswa->id,
                    'nama' => $siswa->nama,
                    'nisn' => $siswa->nisn,
                    'status' => $absen->status ?? 'Hadir',
                    'keterangan' => $absen->keterangan ?? '',
                    'sudah_diabsen' => (bool) $absen,
                ];
            })->values();

            return response()->json([
                'data' => [
                    'jadwal' => [
                        'id' => $jadwalModel->id,
                        'nama_mapel' => $jadwalModel->mapel?->nama_mapel ?? '-',
                        'nama_kelas' => $jadwalModel->kelas?->nama_kelas ?? '-',
                        'jam_masuk' => is_string($jadwalModel->jam_masuk) ? $jadwalModel->jam_masuk : $jadwalModel->jam_masuk?->format('H:i'),
                        'jam_pulang' => is_string($jadwalModel->jam_pulang) ? $jadwalModel->jam_pulang : $jadwalModel->jam_pulang?->format('H:i'),
                        'hari' => $jadwalModel->hari,
                    ],
                    'tanggal' => $tanggal->toDateString(),
                    'siswa' => $daftarSiswa,
                    'status_valid' => self::STATUS_VALID,
                ],
            ]);
        } catch (AbsenSiswaException $e) {
            return response()->json(['message' => $e->getMessage()], $e->status());
        } catch (\Throwable $e) {
            Log::error('Error AbsenSiswaController@daftarSiswa: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal memuat daftar siswa.'], 500);
        }
    }

    public function simpan(Request $request, int $jadwal): JsonResponse
    {
        $guru = $this->guruAktif();
        if (!$guru) {
            return response()->json(['message' => 'Akun ini tidak terhubung dengan data guru.'], 403);
        }

        try {
            $jadwalModel = $this->jadwalMilikGuru($jadwal, $guru);
        } catch (AbsenSiswaException $e) {
            return response()->json(['message' => $e->getMessage()], $e->status());
        }

        $validator = Validator::make($request->all(), [
            'tanggal' => ['nullable', 'date_format:Y-m-d'],
            'kehadiran' => ['required', 'array', 'min:1'],
            'kehadiran.*.siswa_id' => ['required', 'integer', 'distinct', 'exists:siswas,id'],
            'kehadiran.*.status' => ['required', 'string', Rule::in(self::STATUS_VALID)],
            'kehadiran.*.keterangan' => ['nullable', 'string', 'max:255'],
        ], [
            'kehadiran.*.siswa_id.distinct' => 'Ada siswa yang dikirim lebih dari sekali.',
            'kehadiran.*.siswa_id.exists' => 'Salah satu siswa tidak ditemukan.',
            'kehadiran.*.status.in' => 'Status kehadiran tidak valid.',
        ]);

        $validator->after(function ($v) use ($request) {
            foreach ((array) $request->input('kehadiran', []) as $i => $item) {
                $status = $item['status'] ?? null;
                $keterangan = trim((string) ($item['keterangan'] ?? ''));
                if (in_array($status, self::STATUS_WAJIB_KETERANGAN, true) && $keterangan === '') {
                    $v->errors()->add("kehadiran.$i.keterangan", "Keterangan wajib diisi untuk status {$status}.");
                }
            }
        });

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Data absensi tidak valid.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        try {
            $tanggal = $this->resolveTanggal($data['tanggal'] ?? null, $jadwalModel);

            $siswaIdsDikirim = collect($data['kehadiran'])->pluck('siswa_id')->all();
            $siswaIdsValid = Siswa::where('kelas_id', $jadwalModel->kelas_id)
                ->whereIn('id', $siswaIdsDikirim)
                ->pluck('id')
                ->all();

            $siswaTidakValid = array_values(array_diff($siswaIdsDikirim, $siswaIdsValid));
            if (!empty($siswaTidakValid)) {
                throw new AbsenSiswaException(
                    'Terdapat siswa yang bukan bagian dari kelas pada jadwal ini.',
                    422
                );
            }

            $tersimpan = DB::transaction(function () use ($jadwalModel, $tanggal, $data) {
                $hasil = [];
                foreach ($data['kehadiran'] as $item) {
                    $hasil[] = AbsenSiswa::updateOrCreate(
                        [
                            'jadwal_id' => $jadwalModel->id,
                            'siswa_id' => $item['siswa_id'],
                            'tanggal' => $tanggal->toDateString(),
                        ],
                        [
                            'status' => $item['status'],
                            'keterangan' => in_array($item['status'], self::STATUS_WAJIB_KETERANGAN, true) 
                                ? trim($item['keterangan']) 
                                : null,
                        ]
                    );
                }
                return $hasil;
            });

            return response()->json([
                'message' => 'Absensi siswa berhasil disimpan.',
                'data' => [
                    'jadwal_id' => $jadwalModel->id,
                    'tanggal' => $tanggal->toDateString(),
                    'total_disimpan' => count($tersimpan),
                ],
            ]);
        } catch (AbsenSiswaException $e) {
            return response()->json(['message' => $e->getMessage()], $e->status());
        } catch (\Throwable $e) {
            Log::error('Error AbsenSiswaController@simpan: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan sistem saat menyimpan absen siswa.'], 500);
        }
    }
}