<?php

namespace App\Http\Controllers\Guru;

use App\Exceptions\AbsensiException;
use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\NotifikasiAdmin;
use App\Models\ReportSetting;
use App\Models\Sekolah;
use App\Services\AbsensiService;
use App\Services\FonnteService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AbsensiController extends Controller
{
    public function __construct(protected AbsensiService $absensiService)
    {
    }

    protected function guruAktif()
    {
        return Auth::user()?->guru;
    }

    public function halaman()
    {
        $guru = $this->guruAktif();
        abort_unless($guru, 403, 'Akun ini tidak terhubung dengan data guru.');

        return view('guru.absensi.index');
    }

    public function halamanRiwayat()
    {
        $guru = $this->guruAktif();
        abort_unless($guru, 403, 'Akun ini tidak terhubung dengan data guru.');

        return view('guru.absensi.riwayat');
    }

    public function status(): JsonResponse
    {
        $guru = $this->guruAktif();
        if (!$guru) {
            return response()->json(['message' => 'Akun ini tidak terhubung dengan data guru.'], 403);
        }

        try {
            return response()->json([
                'data' => $this->absensiService->statusHariIni($guru),
            ]);
        } catch (\Throwable $e) {
            Log::error('Error AbsensiController@status: ' . $e->getMessage());
            return response()->json([
                'message' => 'Gagal memuat status absensi: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function absenMasuk(Request $request): JsonResponse
    {
        $guru = $this->guruAktif();
        if (!$guru) {
            return response()->json(['message' => 'Akun ini tidak terhubung dengan data guru.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'descriptor' => ['required', 'array', 'size:128'],
            // face-api descriptor values are always within roughly [-1, 1]; reject anything outside
            // that range early instead of letting garbage through to the distance calculation.
            'descriptor.*' => ['numeric', 'between:-1,1'],
            // foto is a base64 data URL; cap it (~3.7MB decoded) so a malicious client can't send an
            // arbitrarily large payload and exhaust disk/memory on every absen request.
            'foto' => ['nullable', 'string', 'max:5000000', 'regex:/^data:image\/(jpe?g|png);base64,/'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Data koordinat atau deteksi wajah tidak valid.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $absensi = $this->absensiService->absenMasuk($guru, $validator->validated());

            return response()->json([
                'message' => 'Absen masuk berhasil dicatat.',
                'data' => $absensi,
            ]);
        } catch (AbsensiException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        } catch (\Throwable $e) {
            Log::error('Error AbsensiController@absenMasuk: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan sistem saat memproses absen masuk.'], 500);
        }
    }

    public function absenPulang(Request $request): JsonResponse
    {
        $guru = $this->guruAktif();
        if (!$guru) {
            return response()->json(['message' => 'Akun ini tidak terhubung dengan data guru.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'descriptor' => ['required', 'array', 'size:128'],
            'descriptor.*' => ['numeric', 'between:-1,1'],
            'foto' => ['nullable', 'string', 'max:5000000', 'regex:/^data:image\/(jpe?g|png);base64,/'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Data koordinat atau deteksi wajah tidak valid.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $absensi = $this->absensiService->absenPulang($guru, $validator->validated());

            return response()->json([
                'message' => 'Absen pulang berhasil dicatat.',
                'data' => $absensi,
            ]);
        } catch (AbsensiException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        } catch (\Throwable $e) {
            Log::error('Error AbsensiController@absenPulang: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan sistem saat memproses absen pulang.'], 500);
        }
    }

    public function ajukanIzin(Request $request): JsonResponse
    {
        $guru = $this->guruAktif();

        if (!$guru) {
            return response()->json([
                'message' => 'Akun ini tidak terhubung dengan data guru.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'tanggal' => ['required', 'date'],
            'status' => ['required', 'in:izin,sakit,cuti,tugas_luar'],
            'keterangan' => ['required', 'string', 'max:1000'],
            'bukti_dokumen' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Data pengajuan izin tidak valid.',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();
        $targetTanggal = Carbon::parse($data['tanggal'])->format('Y-m-d');

        // Pastikan guru memiliki jadwal aktif pada hari pengajuan izin
        $jadwals = $this->absensiService->cekJadwalHariIni($guru, Carbon::parse($targetTanggal));
        if ($jadwals->isEmpty()) {
            return response()->json([
                'message' => 'Anda tidak memiliki jadwal mengajar pada tanggal tersebut.'
            ], 422);
        }

        try {
            $absensi = DB::transaction(function () use ($guru, $data, $targetTanggal, $request) {
                // Lock the row for this guru+tanggal (if any) so two concurrent submissions
                // (e.g. a double tap on "Kirim Pengajuan") can't both pass the checks below.
                $existing = Absensi::where('guru_id', $guru->id)
                    ->where('tanggal', $targetTanggal)
                    ->lockForUpdate()
                    ->first();

                if ($existing) {
                    $sudahDiajukan = in_array($existing->status, ['izin', 'sakit', 'cuti', 'tugas_luar'], true);

                    // Block only if there's an existing pengajuan that hasn't been rejected.
                    // A previously rejected ('ditolak') pengajuan may be resubmitted.
                    if ($sudahDiajukan && $existing->status_persetujuan !== 'ditolak') {
                        throw new AbsensiException('Anda sudah mengajukan izin/tidak hadir untuk tanggal ini.');
                    }

                    // Guru already clocked in (or has any other recorded attendance) for this date;
                    // don't silently overwrite/duplicate that record with an izin submission.
                    if (!$sudahDiajukan && ($existing->jam_masuk || $existing->jam_pulang)) {
                        throw new AbsensiException('Anda sudah melakukan absen pada tanggal tersebut, sehingga tidak dapat mengajukan izin.');
                    }
                }

                $path = null;
                if ($request->hasFile('bukti_dokumen')) {
                    $path = $request->file('bukti_dokumen')->store('pengajuan-absensi', 'public');
                }

                return Absensi::updateOrCreate(
                    ['guru_id' => $guru->id, 'tanggal' => $targetTanggal],
                    [
                        'status' => $data['status'],
                        'keterangan' => $data['keterangan'],
                        'bukti_dokumen' => $path,
                        'status_persetujuan' => 'pending',
                        'approved_by' => null,
                        'approved_at' => null,
                    ]
                );
            });
        } catch (AbsensiException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        } catch (\Throwable $e) {
            Log::error('Error AbsensiController@ajukanIzin: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan sistem saat memproses pengajuan izin.'], 500);
        }

        $tanggalFormatted = Carbon::parse($data['tanggal'])->translatedFormat('d M Y');
        $pesanNotif = "{$guru->nama} mengajukan {$data['status']} pada {$tanggalFormatted}.";

        NotifikasiAdmin::create([
            'absensi_id' => $absensi->id,
            'tipe' => 'pengajuan_izin',
            'pesan' => $pesanNotif,
        ]);

        try {
            event(new \App\Events\PengajuanIzinEvent($absensi));
            $this->kirimNotifikasiWaAdmin($guru->nama, $data['status'], $tanggalFormatted, $data['keterangan']);
        } catch (\Throwable $e) {
            Log::warning('Notifikasi gagal terkirim: ' . $e->getMessage());
        }

        return response()->json([
            'message' => 'Pengajuan berhasil dikirim dan menunggu persetujuan admin.',
            'data' => $absensi
        ]);
    }

    private function kirimNotifikasiWaAdmin(string $namaGuru, string $status, string $tanggal, string $keterangan): void
    {
        // NOTE: add 'admin_wa_number' => env('ADMIN_WA_NUMBER') under a `fonnte` key in
        // config/services.php. env() is not safe to call directly here because it returns
        // null once `php artisan config:cache` has been run in production.
        $adminPhone = config('services.fonnte.admin_wa_number');
        if (!$adminPhone) return;

        $waMessage = "*PENGAJUAN IZIN BARU*\n\n" .
                     "• *Nama:* {$namaGuru}\n" .
                     "• *Tipe:* " . strtoupper($status) . "\n" .
                     "• *Tanggal:* {$tanggal}\n" .
                     "• *Keterangan:* {$keterangan}\n\n" .
                     "Mohon segera lakukan pengecekan di sistem web admin.";

        FonnteService::sendMessage($adminPhone, $waMessage);
    }

    public function riwayat(Request $request)
    {
        $guru = $this->guruAktif();
        if (!$guru) abort(403, 'Akun ini tidak terhubung dengan data guru.');

        $bulan = (int) $request->query('bulan', now()->month);
        $tahun = (int) $request->query('tahun', now()->year);

        $riwayat = Absensi::where('guru_id', $guru->id)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderByDesc('tanggal')
            ->paginate(31);

        return view('guru.absensi.riwayat', compact('riwayat'));
    }

    public function cetakPDF(Request $request)
    {
        $guru = $this->guruAktif();
        if (!$guru) abort(403, 'Akun ini tidak terhubung dengan data guru.');

        $bulan = (int) $request->query('bulan', now()->month);
        $tahun = (int) $request->query('tahun', now()->year);

        $setting = ReportSetting::first();
        $sekolah = Sekolah::first();

        $daftarAbsensi = Absensi::where('guru_id', $guru->id)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal', 'asc')
            ->get();

        $statistik = [
            'hadir'      => $daftarAbsensi->where('status', 'hadir')->count(),
            'terlambat'  => $daftarAbsensi->where('status', 'terlambat')->count(),
            'izin'       => $daftarAbsensi->where('status', 'izin')->count(),
            'sakit'      => $daftarAbsensi->where('status', 'sakit')->count(),
            'cuti'       => $daftarAbsensi->where('status', 'cuti')->count(),
            'tugas_luar' => $daftarAbsensi->where('status', 'tugas_luar')->count(),
            'alpha'      => $daftarAbsensi->where('status', 'alpha')->count(),
            'total'      => $daftarAbsensi->count(),
        ];

        $pdf = Pdf::loadView('pdf.guru.absensi', [
            'guru'          => $guru,
            'daftarAbsensi' => $daftarAbsensi,
            'setting'       => $setting,
            'sekolah'       => $sekolah,
            'bulan'         => $bulan,
            'tahun'         => $tahun,
            'statistik'     => $statistik
        ]);

        $paperSize = $setting->paper_size ?? 'A4';
        $orientation = $setting->orientation ?? 'portrait';

        return $pdf->setPaper($paperSize, $orientation)
                   ->stream("Rapor_Absensi_{$guru->nama}_{$bulan}_{$tahun}.pdf");
    }
}