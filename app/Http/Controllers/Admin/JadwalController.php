<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJadwalRequest;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\ReportSetting;
use App\Models\Sekolah;
use Barryvdh\DomPDF\Facade\Pdf; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JadwalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jadwals = Jadwal::with('guru.user')
            ->latest()
            ->paginate(10);
        $statitsik = Jadwal::select(
        'hari',
        DB::raw('COUNT(*) as jumlah_jadwal'),
        DB::raw('COUNT(DISTINCT guru_id) as jumlah_guru'),
        DB::raw('COUNT(DISTINCT mata_pelajaran) as jumlah_mapel'),
        DB::raw('COUNT(DISTINCT kelas) as jumlah_kelas'),
        DB::raw('COUNT(DISTINCT ruangan) as jumlah_ruangan')
    )
    ->where('is_active', true)
    ->groupBy('hari')
    ->orderByRaw("
        FIELD(hari,
        'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu')
    ")
    ->get();
        return view('admin.jadwal.index', compact('jadwals', 'statitsik'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $gurus = Guru::with('user')
            ->orderBy('nama')
            ->get();

        return view('admin.jadwal.create', compact('gurus'));
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(StoreJadwalRequest $request)
{
    DB::beginTransaction();

    try {

        Jadwal::create([
            'guru_id'         => $request->guru_id,
            'hari'            => $request->hari,
            'jam_masuk'       => $request->jam_masuk,
            'jam_pulang'      => $request->jam_pulang,
            'mata_pelajaran'  => $request->mata_pelajaran,
            'kelas'           => $request->kelas,
            'ruangan'         => $request->ruangan,
            'is_active'       => $request->boolean('is_active'),
        ]);

        DB::commit();

        return redirect()
            ->route('admin.jadwal.index')
            ->with('success', 'Jadwal berhasil ditambahkan.');

    } catch (\Throwable $th) {

        DB::rollBack();

        return back()
            ->withInput()
            ->with('error', $th->getMessage());
    }
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Jadwal $jadwal)
    {
        $gurus = Guru::with('user')
            ->orderBy('nama')
            ->get();

        return view('admin.jadwal.edit', compact('jadwal', 'gurus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Jadwal $jadwal)
    {
        $validated = $request->validate([
            'guru_id' => 'required|exists:gurus,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_masuk' => 'required',
            'jam_pulang' => 'required|after:jam_masuk',
            'mata_pelajaran' => 'nullable|string|max:255',
            'kelas' => 'nullable|string|max:255',
            'ruangan' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        DB::beginTransaction();

        try {

            $jadwal->update([
                'guru_id' => $validated['guru_id'],
                'hari' => $validated['hari'],
                'jam_masuk' => $validated['jam_masuk'],
                'jam_pulang' => $validated['jam_pulang'],
                'mata_pelajaran' => $validated['mata_pelajaran'] ?? null,
                'kelas' => $validated['kelas'] ?? null,
                'ruangan' => $validated['ruangan'] ?? null,
                'is_active' => $request->boolean('is_active'),
            ]);

            DB::commit();

            return redirect()
                ->route('admin.jadwal.index')
                ->with('success', 'Jadwal berhasil diperbarui.');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }




/**
 * Cetak PDF Jadwal Pelajaran sesuai ReportSetting
 */
public function cetakPdf()
{
    // 1. Ambil data jadwal pelajaran
    $jadwals = Jadwal::with('guru.user')
        ->where('is_active', true)
        ->orderByRaw("FIELD(hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu')")
        ->orderBy('jam_masuk')
        ->get();

    // 2. Ambil konfigurasi laporan aktif
    $setting = ReportSetting::current();

    // 3. Ambil data sekolah terbaru/aktif
    $sekolah = Sekolah::latest()->first(); // Mengambil entitas sekolah pertama

    // Menentukan ukuran kertas
    $paperSize = strtolower($setting->paper_size);
    if ($paperSize === 'f4') {
        $paperSize = [0, 0, 612, 936]; // Ukuran standar F4 dalam Point (8.5 x 13 inch)
    }

    // 4. Load view PDF dan passing variabel $jadwals, $setting, dan $sekolah
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

            return back()->with('error', $e->getMessage());
        }
    }
}