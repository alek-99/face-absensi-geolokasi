<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SiswaController extends Controller
{
    /**
     * Menampilkan daftar siswa (dengan filter, search, & pagination).
     */
   public function index(Request $request)
{
    try {
        $query = Siswa::with('kelas');

        // Filter Pencarian (Nama, NISN, NIK, NIPD)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('nipd', 'like', "%{$search}%");
            });
        }

        // Filter Berdasarkan Kelas
        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

        // Paginasi
        $perPage = $request->get('per_page', 10);
        $siswas  = $query->latest()->paginate($perPage)->withQueryString();

        // JIKA REQUEST VIA AJAX / FETCH JSON (dari JavaScript)
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status'  => 'success',
                'message' => 'Data siswa berhasil dimuat.',
                'data'    => $siswas
            ], 200);
        }

        // JIKA REQUEST BIASA (Direct Browser Loading)
        $kelas = Kelas::all();
        return view('admin.siswa.index', compact('kelas'));

    } catch (\Exception $e) {
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal memuat data siswa: ' . $e->getMessage()
            ], 500);
        }
        return back()->with('error', 'Gagal memuat data siswa: ' . $e->getMessage());
    }
}

    /**
     * Menyimpan data siswa baru via AJAX/Form.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), $this->rules());

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            $siswa = Siswa::create($request->all());
            $siswa->load('kelas');

            return response()->json([
                'status'  => 'success',
                'message' => 'Data siswa berhasil ditambahkan.',
                'data'    => $siswa
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menambahkan siswa: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menampilkan detail satu siswa berdasarkan ID.
     */
    public function show($id): JsonResponse
    {
        try {
            $siswa = Siswa::with('kelas')->find($id);

            if (!$siswa) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Data siswa tidak ditemukan.'
                ], 404);
            }

            return response()->json([
                'status'  => 'success',
                'message' => 'Detail data siswa ditemukan.',
                'data'    => $siswa
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal mengambil detail siswa: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Memperbarui data siswa berdasarkan ID.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $siswa = Siswa::find($id);

        if (!$siswa) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data siswa tidak ditemukan.'
            ], 404);
        }

        $validator = Validator::make($request->all(), $this->rules($id));

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            $siswa->update($request->all());
            $siswa->load('kelas');

            return response()->json([
                'status'  => 'success',
                'message' => 'Data siswa berhasil diperbarui.',
                'data'    => $siswa
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal memperbarui data siswa: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menghapus data siswa berdasarkan ID.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $siswa = Siswa::find($id);

            if (!$siswa) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Data siswa tidak ditemukan.'
                ], 404);
            }

            $siswa->delete();

            return response()->json([
                'status'  => 'success',
                'message' => 'Data siswa berhasil dihapus.'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menghapus siswa: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Rule Validasi Terpusat
     */
    private function rules($id = null): array
    {
        $isUpdate = !is_null($id);

        return [
            'nama'           => ($isUpdate ? 'sometimes|' : '') . 'required|string|max:255',
            'nisn'           => [
                $isUpdate ? 'sometimes' : 'required',
                'string',
                'max:20',
                Rule::unique('siswas', 'nisn')->ignore($id)
            ],
            'nik'            => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('siswas', 'nik')->ignore($id)
            ],
            'nipd'           => 'nullable|string|max:20',
            'kelas_id'       => ($isUpdate ? 'sometimes|' : '') . 'required|exists:kelas,id',
            'alamat'         => 'nullable|string',
            'no_hp'          => 'nullable|string|max:15',
            'jenis_kelamin'  => ($isUpdate ? 'sometimes|' : '') . 'required|in:laki-laki,perempuan',
            'tempat_lahir'   => 'nullable|string|max:100',
            'tanggal_lahir'  => 'nullable|date',
            'agama'          => 'nullable|string|max:20',
            'sekolah_asal'   => 'nullable|string|max:255',
            'nama_ayah'      => 'nullable|string|max:255',
            'nama_ibu'       => 'nullable|string|max:255',
            'pekerjaan_ayah' => 'nullable|string|max:100',
            'pekerjaan_ibu'  => 'nullable|string|max:100',
        ];
    }
}