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
            'descriptor' => ['required', 'array', 'min:128', 'max:128'],
            'descriptor.*' => ['numeric'],
            'foto' => ['nullable', 'string'],
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
            'descriptor' => ['required', 'array', 'min:128', 'max:128'],
            'descriptor.*' => ['numeric'],
            'foto' => ['nullable', 'string'],
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

        $sudahAjukanHariIni = Absensi::where('guru_id', $guru->id)
            ->where('tanggal', $targetTanggal)
            ->whereIn('status', ['izin', 'sakit', 'cuti', 'tugas_luar'])
            ->whereNotNull('status_persetujuan')
            ->exists();

        if ($sudahAjukanHariIni) {
            return response()->json([
                'message' => 'Anda sudah mengajukan izin/tidak hadir untuk tanggal ini.'
            ], 422);
        }

        $path = null;
        if ($request->hasFile('bukti_dokumen')) {
            $path = $request->file('bukti_dokumen')->store('pengajuan-absensi', 'public');
        }

        $absensi = Absensi::create([
            'guru_id' => $guru->id,
            'tanggal' => $targetTanggal,
            'status' => $data['status'],
            'keterangan' => $data['keterangan'],
            'bukti_dokumen' => $path,
            'status_persetujuan' => 'pending',
            'approved_by' => null,
            'approved_at' => null,
        ]);

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
        $adminPhone = env('ADMIN_WA_NUMBER');
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