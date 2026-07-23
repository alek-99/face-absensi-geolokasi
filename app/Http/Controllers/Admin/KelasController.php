<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    /**
     * Tampilan utama (Halaman Blade)
     */
    public function index()
    {
        $kelases = Kelas::with('waliKelas')
            ->withCount('siswas')
            ->latest()
            ->get();

        $gurus = Guru::orderBy('nama')->get();

        return view('admin.kelas.index', compact('kelases', 'gurus'));
    }

    /**
     * Simpan data kelas baru (JSON Response)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:50|unique:kelas,nama_kelas',
            'wali_kelas' => 'required|exists:gurus,id',
            'ruangan'    => 'required|string|max:20',
            'catatan'    => 'nullable|string',
        ], [
            'nama_kelas.required' => 'Nama kelas wajib diisi.',
            'nama_kelas.unique'   => 'Nama kelas sudah ada.',
            'wali_kelas.required' => 'Wali kelas wajib dipilih.',
            'wali_kelas.exists'   => 'Wali kelas tidak valid.',
            'ruangan.required'    => 'Ruangan wajib diisi.',
        ]);

        $validated['jumlah_siswa'] = 0;
        $kelas = Kelas::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data kelas berhasil ditambahkan.',
            'data'    => $kelas->load('waliKelas')
        ], 201);
    }

    /**
     * Ambil detail data kelas untuk Modal Show / Edit (JSON Response)
     */
    public function show($id)
    {
        $kelas = Kelas::with(['waliKelas', 'siswas'])->find($id);

        if (!$kelas) {
            return response()->json([
                'success' => false,
                'message' => 'Data kelas tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $kelas
        ]);
    }

    /**
     * Update data kelas (JSON Response)
     */
    public function update(Request $request, $id)
    {
        $kelas = Kelas::find($id);

        if (!$kelas) {
            return response()->json([
                'success' => false,
                'message' => 'Data kelas tidak ditemukan.'
            ], 404);
        }

        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:50|unique:kelas,nama_kelas,' . $id,
            'wali_kelas' => 'required|exists:gurus,id',
            'ruangan'    => 'required|string|max:20',
            'catatan'    => 'nullable|string',
        ], [
            'nama_kelas.required' => 'Nama kelas wajib diisi.',
            'nama_kelas.unique'   => 'Nama kelas sudah digunakan.',
            'wali_kelas.required' => 'Wali kelas wajib dipilih.',
            'wali_kelas.exists'   => 'Wali kelas tidak valid.',
            'ruangan.required'    => 'Ruangan wajib diisi.',
        ]);

        $kelas->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data kelas berhasil diperbarui.',
            'data'    => $kelas->load('waliKelas')
        ]);
    }

    /**
     * Hapus data kelas (JSON Response)
     */
    public function destroy($id)
    {
        $kelas = Kelas::find($id);

        if (!$kelas) {
            return response()->json([
                'success' => false,
                'message' => 'Data kelas tidak ditemukan.'
            ], 404);
        }

        if ($kelas->siswas()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Kelas tidak dapat dihapus karena masih memiliki siswa terdaftar.'
            ], 422);
        }

        $kelas->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data kelas berhasil dihapus.'
        ]);
    }
}