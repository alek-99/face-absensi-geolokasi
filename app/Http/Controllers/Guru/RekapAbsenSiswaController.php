<?php

namespace App\Http\Controllers\Guru;

use App\Exports\AbsenSiswaExport;
use App\Http\Controllers\Controller;
use App\Models\AbsenSiswa;
use App\Models\Jadwal;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class RekapAbsenSiswaController extends Controller
{
    protected function guruAktif()
    {
        return Auth::user()?->guru;
    }

    /**
     * Step 1: Menampilkan Halaman Daftar Mapel & Kelas milik Guru
     */
    public function index()
    {
        $guru = $this->guruAktif();
        abort_unless($guru, 403, 'Akun ini tidak terhubung dengan data guru.');

        // Mengambil daftar jadwal unik per Mapel & Kelas
        $jadwals = Jadwal::with(['mapel:id,nama_mapel', 'kelas:id,nama_kelas'])
            ->where('guru_id', $guru->id)
            ->where('is_active', true)
            ->get()
            ->unique(fn($item) => $item->mapel_id . '-' . $item->kelas_id);

        return view('guru.rekap-absen.index', compact('jadwals'));
    }

    /**
     * Step 2: Menampilkan Data Absensi Siswa per Mapel & Kelas
     */
    public function detail(Request $request, int $jadwalId)
    {
        $guru = $this->guruAktif();
        abort_unless($guru, 403, 'Akun ini tidak terhubung dengan data guru.');

        $jadwal = Jadwal::with(['mapel', 'kelas'])->findOrFail($jadwalId);

        if ((int) $jadwal->guru_id !== (int) $guru->id) {
            abort(403, 'Anda tidak memiliki akses ke jadwal ini.');
        }

        $bulan = $request->query('bulan', date('Y-m')); // Format: YYYY-MM

        // Query daftar siswa beserta rekap absensinya pada jadwal ini
        $siswas = Siswa::where('kelas_id', $jadwal->kelas_id)
            ->orderBy('nama')
            ->get()
            ->map(function ($siswa) use ($jadwal, $bulan) {
                $query = AbsenSiswa::where('jadwal_id', $jadwal->id)
                    ->where('siswa_id', $siswa->id);

                if ($bulan) {
                    $query->whereMonth('tanggal', substr($bulan, 5, 2))
                          ->whereYear('tanggal', substr($bulan, 0, 4));
                }

                $rekap = $query->get();

                return [
                    'id' => $siswa->id,
                    'nama' => $siswa->nama,
                    'nisn' => $siswa->nisn,
                    'hadir' => $rekap->where('status', 'Hadir')->count(),
                    'izin' => $rekap->where('status', 'Izin')->count(),
                    'sakit' => $rekap->where('status', 'Sakit')->count(),
                    'alfa' => $rekap->where('status', 'Alfa')->count(),
                    'total' => $rekap->count(),
                ];
            });

        return view('guru.rekap-absen.detail', compact('jadwal', 'siswas', 'bulan'));
    }

    /**
     * Step 3: Export Data Rekap ke Excel
     */
    public function exportExcel(Request $request, int $jadwalId)
    {
        $guru = $this->guruAktif();
        abort_unless($guru, 403, 'Akun ini tidak terhubung dengan data guru.');

        $jadwal = Jadwal::with(['mapel', 'kelas'])->findOrFail($jadwalId);

        if ((int) $jadwal->guru_id !== (int) $guru->id) {
            abort(403, 'Anda tidak memiliki akses ke jadwal ini.');
        }

        $bulan = $request->query('bulan');
        $namaKelas = str_replace(' ', '_', $jadwal->kelas?->nama_kelas ?? 'Kelas');
        $namaMapel = str_replace(' ', '_', $jadwal->mapel?->nama_mapel ?? 'Mapel');
        
        $filename = "Rekap_Absen_{$namaMapel}_{$namaKelas}_" . ($bulan ?? date('Y-m')) . ".xlsx";

        return Excel::download(new AbsenSiswaExport($jadwal, $bulan), $filename);
    }
}