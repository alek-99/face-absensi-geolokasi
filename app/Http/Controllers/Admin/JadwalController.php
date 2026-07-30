<?php

namespace App\Http\Controllers\Admin;

use App\Exports\JadwalExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJadwalRequest;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\ReportSetting;
use App\Models\Sekolah;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class JadwalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jadwals = Jadwal::with(['guru.user', 'mapel', 'kelas'])
            ->latest()
            ->paginate(10);

        // Menggunakan nama variabel $statistik sesuai dengan di Blade
        $statistik = Jadwal::select(
            'hari',
            DB::raw('COUNT(*) as jumlah_jadwal'),
            DB::raw('COUNT(DISTINCT guru_id) as jumlah_guru'),
            DB::raw('COUNT(DISTINCT mapel_id) as jumlah_mapel'),
            DB::raw('COUNT(DISTINCT kelas_id) as jumlah_kelas')
        )
        ->where('is_active', true)
        ->groupBy('hari')
        ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')")
        ->get();

        $gurus = Guru::with('user')->orderBy('nama')->get();
        $mapels = Mapel::orderBy('nama_mapel')->get();
        $kelases = Kelas::orderBy('nama_kelas')->get();

        return view('admin.jadwal.index', compact('jadwals', 'statistik', 'gurus', 'kelases', 'mapels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $gurus = Guru::with('user')->orderBy('nama')->get();
        $mapels = Mapel::orderBy('nama_mapel')->get();
        $kelases = Kelas::orderBy('nama_kelas')->get();

        return view('admin.jadwal.create', compact('gurus', 'mapels', 'kelases'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJadwalRequest $request)
    {
        // Validasi dasar
        $validated = $request->validated();

        // 1. Validasi Bentrok Guru: Guru tidak boleh mengajar di kelas/mapel lain di hari dan jam yang sama
        $bentrokGuru = Jadwal::where('hari', $validated['hari'])
            ->where('guru_id', $validated['guru_id'])
            ->where('is_active', true)
            ->where(function ($query) use ($validated) {
                $query->whereBetween('jam_masuk', [$validated['jam_masuk'], $validated['jam_pulang']])
                    ->orWhereBetween('jam_pulang', [$validated['jam_masuk'], $validated['jam_pulang']])
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('jam_masuk', '<=', $validated['jam_masuk'])
                          ->where('jam_pulang', '>=', $validated['jam_pulang']);
                    });
            })
            ->exists();

        if ($bentrokGuru) {
            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan! Guru yang dipilih sudah memiliki jadwal mengajar pada hari dan jam tersebut.');
        }

        // 2. Validasi Bentrok Kelas: Kelas tidak boleh diisi 2 jadwal/guru bersamaan di jam yang sama
        $bentrokKelas = Jadwal::where('hari', $validated['hari'])
            ->where('kelas_id', $validated['kelas_id'])
            ->where('is_active', true)
            ->where(function ($query) use ($validated) {
                $query->whereBetween('jam_masuk', [$validated['jam_masuk'], $validated['jam_pulang']])
                    ->orWhereBetween('jam_pulang', [$validated['jam_masuk'], $validated['jam_pulang']])
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('jam_masuk', '<=', $validated['jam_masuk'])
                          ->where('jam_pulang', '>=', $validated['jam_pulang']);
                    });
            })
            ->exists();

        if ($bentrokKelas) {
            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan! Kelas tersebut sudah terisi pelajaran lain pada hari dan jam tersebut.');
        }

        DB::beginTransaction();

        try {
            Jadwal::create([
                'guru_id'    => $validated['guru_id'],
                'hari'       => $validated['hari'],
                'jam_masuk'  => $validated['jam_masuk'],
                'jam_pulang' => $validated['jam_pulang'],
                'mapel_id'   => $validated['mapel_id'],
                'kelas_id'   => $validated['kelas_id'],
                'is_active'  => $request->boolean('is_active'),
            ]);

            DB::commit();

            return redirect()
                ->route('admin.jadwal.index')
                ->with('success', 'Jadwal berhasil ditambahkan.');

        } catch (\Throwable $th) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Jadwal $jadwal)
    {
        $gurus = Guru::with('user')->orderBy('nama')->get();
        $mapels = Mapel::orderBy('nama_mapel')->get();
        $kelases = Kelas::orderBy('nama_kelas')->get();

        return view('admin.jadwal.edit', compact('jadwal', 'gurus', 'mapels', 'kelases'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Jadwal $jadwal)
    {
        $validated = $request->validate([
            'guru_id'    => 'required|exists:gurus,id',
            'hari'       => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_masuk'  => 'required',
            'jam_pulang' => 'required|after:jam_masuk',
            'mapel_id'   => 'required|exists:mapels,id',
            'kelas_id'   => 'required|exists:kelas,id',
            'is_active'  => 'nullable|boolean',
        ]);

        // 1. Validasi Bentrok Guru (Kecualikan ID jadwal yang sedang diedit)
        $bentrokGuru = Jadwal::where('id', '!=', $jadwal->id)
            ->where('hari', $validated['hari'])
            ->where('guru_id', $validated['guru_id'])
            ->where('is_active', true)
            ->where(function ($query) use ($validated) {
                $query->whereBetween('jam_masuk', [$validated['jam_masuk'], $validated['jam_pulang']])
                    ->orWhereBetween('jam_pulang', [$validated['jam_masuk'], $validated['jam_pulang']])
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('jam_masuk', '<=', $validated['jam_masuk'])
                          ->where('jam_pulang', '>=', $validated['jam_pulang']);
                    });
            })
            ->exists();

        if ($bentrokGuru) {
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui! Guru yang dipilih sudah memiliki jadwal mengajar pada hari dan jam tersebut.');
        }

        // 2. Validasi Bentrok Kelas (Kecualikan ID jadwal yang sedang diedit)
        $bentrokKelas = Jadwal::where('id', '!=', $jadwal->id)
            ->where('hari', $validated['hari'])
            ->where('kelas_id', $validated['kelas_id'])
            ->where('is_active', true)
            ->where(function ($query) use ($validated) {
                $query->whereBetween('jam_masuk', [$validated['jam_masuk'], $validated['jam_pulang']])
                    ->orWhereBetween('jam_pulang', [$validated['jam_masuk'], $validated['jam_pulang']])
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('jam_masuk', '<=', $validated['jam_masuk'])
                          ->where('jam_pulang', '>=', $validated['jam_pulang']);
                    });
            })
            ->exists();

        if ($bentrokKelas) {
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui! Kelas tersebut sudah terisi pelajaran lain pada hari dan jam tersebut.');
        }

        DB::beginTransaction();

        try {
            $jadwal->update([
                'guru_id'    => $validated['guru_id'],
                'hari'       => $validated['hari'],
                'jam_masuk'  => $validated['jam_masuk'],
                'jam_pulang' => $validated['jam_pulang'],
                'mapel_id'   => $validated['mapel_id'],
                'kelas_id'   => $validated['kelas_id'],
                'is_active'  => $request->boolean('is_active'),
            ]);

            DB::commit();

            return redirect()
                ->route('admin.jadwal.index')
                ->with('success', 'Jadwal berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Cetak PDF Jadwal Pelajaran sesuai ReportSetting
     */
    public function cetakPdf()
    {
        $jadwals = Jadwal::with(['guru.user', 'mapel', 'kelas'])
            ->where('is_active', true)
            ->orderByRaw("FIELD(hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu')")
            ->orderBy('jam_masuk')
            ->get();

        $setting = ReportSetting::current();
        $sekolah = Sekolah::latest()->first();

        $paperSize = strtolower($setting->paper_size);
        if ($paperSize === 'f4') {
            $paperSize = [0, 0, 612, 936];
        }

        $pdf = Pdf::loadView('pdf.admin.jadwal', compact('jadwals', 'setting', 'sekolah'))
            ->setPaper($paperSize, $setting->orientation);

        return $pdf->stream('Jadwal_Pelajaran_' . date('Ymd_His') . '.pdf');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Jadwal $jadwal)
    {
        try {
            $jadwal->delete();

            return redirect()
                ->route('admin.jadwal.index')
                ->with('success', 'Jadwal berhasil dihapus.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    /**
     * Export Excel Data Jadwal
     */
    public function exportExcel()
    {
        $fileName = 'Jadwal_Pelajaran_' . date('Ymd_His') . '.xlsx';
        return Excel::download(new JadwalExport, $fileName);
    }
}