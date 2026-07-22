<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\FaceDescriptor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FaceDescriptorController extends Controller
{
    /**
     * Panjang descriptor wajah standar face-api.js (face_recognition_model)
     * adalah 128 angka float. Divalidasi ketat supaya tidak ada data rusak
     * yang tersimpan dan bikin absen gagal terus.
     */
    protected const DESCRIPTOR_LENGTH = 128;

    protected function guruAktif()
    {
        return Auth::user()?->guru;
    }

    /**
     * GET /guru/wajah
     * Halaman pendaftaran / kelola wajah.
     */
    public function halaman()
    {
        $guru = $this->guruAktif();
        abort_unless($guru, 403, 'Akun ini tidak terhubung dengan data guru.');

        return view('guru.wajah.index');
    }

    /**
     * GET /guru/wajah/status
     * Info apakah guru sudah punya data wajah terdaftar, kapan didaftarkan/diperbarui.
     */
    public function status(): JsonResponse
    {
        $guru = $this->guruAktif();
        if (!$guru) {
            return response()->json(['message' => 'Akun ini tidak terhubung dengan data guru.'], 403);
        }

        $face = $guru->faceDescriptor;

        return response()->json([
            'data' => [
                'terdaftar' => (bool) $face,
                'algorithm' => $face?->algorithm,
                'version' => $face?->version,
                'terdaftar_pada' => $face?->created_at,
                'diperbarui_pada' => $face?->updated_at,
            ],
        ]);
    }

    /**
     * POST /guru/wajah
     * Daftarkan wajah baru ATAU perbarui (timpa) descriptor yang sudah ada
     * — dipakai juga untuk kasus "wajah berubah" (kacamata, jenggot, dsb),
     * guru tinggal scan ulang dan data lama otomatis tergantikan.
     *
     * Body: descriptor (array float 128 angka dari face-api.js), version (opsional)
     */
    public function simpan(Request $request): JsonResponse
    {
        $guru = $this->guruAktif();
        if (!$guru) {
            return response()->json(['message' => 'Akun ini tidak terhubung dengan data guru.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'descriptor' => ['required', 'array', 'size:' . self::DESCRIPTOR_LENGTH],
            'descriptor.*' => ['numeric'],
            'version' => ['nullable', 'string', 'max:50'],
        ], [
            'descriptor.size' => 'Data wajah tidak valid (jumlah titik descriptor tidak sesuai). Coba pindai ulang.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Data tidak valid.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        $sudahTerdaftarSebelumnya = (bool) $guru->faceDescriptor;

        $face = FaceDescriptor::updateOrCreate(
            ['guru_id' => $guru->id],
            [
                'descriptor' => $validated['descriptor'],
                'algorithm' => 'face-api',
                'version' => $validated['version'] ?? null,
            ]
        );

        return response()->json([
            'message' => $sudahTerdaftarSebelumnya
                ? 'Data wajah berhasil diperbarui.'
                : 'Wajah berhasil didaftarkan.',
            'data' => [
                'terdaftar' => true,
                'diperbarui_pada' => $face->updated_at,
            ],
        ]);
    }

    /**
     * DELETE /guru/wajah
     * Hapus data wajah guru yang login. Setelah dihapus, guru wajib
     * mendaftar ulang sebelum bisa absen (lihat AbsensiService::verifikasiWajah()).
     */
    public function hapus(): JsonResponse
    {
        $guru = $this->guruAktif();
        if (!$guru) {
            return response()->json(['message' => 'Akun ini tidak terhubung dengan data guru.'], 403);
        }

        $face = $guru->faceDescriptor;

        if (!$face) {
            return response()->json(['message' => 'Belum ada data wajah yang terdaftar.'], 404);
        }

        $face->delete();

        return response()->json([
            'message' => 'Data wajah berhasil dihapus.',
        ]);
    }
}