<?php

namespace App\Http\Controllers\Guru;

use App\Exports\JadwalGuruExport;
use App\Http\Controllers\Controller;
use App\Services\ReportPdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel; 

class JadwalController extends Controller
{
    protected ReportPdfService $reportPdfService;

    public function __construct(ReportPdfService $reportPdfService)
    {
        $this->reportPdfService = $reportPdfService;
    }

    public function index()
    {
        $guru = Auth::user()->guru;

        abort_if(!$guru, 404);

        $jadwals = $guru->jadwals()
            ->with(['kelas', 'mapel'])
            ->where('is_active', true)
            ->orderByRaw("
                FIELD(
                    hari,
                    'Senin',
                    'Selasa',
                    'Rabu',
                    'Kamis',
                    'Jumat',
                    'Sabtu'
                )
            ")
            ->orderBy('jam_masuk')
            ->get();

        $jadwalPerHari = $jadwals->groupBy('hari');

        $statistik = [
            'total_jadwal'  => $jadwals->count(),
            'total_mapel'   => $jadwals->pluck('mapel.nama_mapel')->filter()->unique()->count(),
            'hari_mengajar' => $jadwals->pluck('hari')->unique()->count(),
            'total_kelas'   => $jadwals->pluck('kelas.nama_kelas')->filter()->unique()->count(),
            'total_ruangan' => $jadwals->pluck('kelas.ruangan')->filter()->unique()->count(),
        ];

        return view('guru.jadwal.index', compact(
            'guru',
            'jadwalPerHari',
            'statistik'
        ));
    }

    public function cetak(Request $request)
    {
        $guru = Auth::user()->guru;

        abort_if(!$guru, 404);

        $jadwals = $this->getJadwalData($guru);

        return $this->reportPdfService->stream(
            view: 'pdf.guru.jadwal',
            data: [
                'judul_laporan' => 'JADWAL MENGAJAR GURU',
                'subjudul'      => 'Agenda Kegiatan Pembelajaran',
                'guru'          => $guru,
                'jadwals'       => $jadwals,
            ],
            filename: 'jadwal-' . Str::slug($guru->nama ?? 'guru') . '.pdf'
        );
    }

    /**
     * Fitur Ekspor Jadwal ke File Excel (.xlsx)
     */
    public function exportExcel()
    {
        $guru = Auth::user()->guru;

        abort_if(!$guru, 404);

        $jadwals = $this->getJadwalData($guru);

        $filename = 'jadwal-' . Str::slug($guru->nama ?? 'guru') . '.xlsx';

        return Excel::download(new JadwalGuruExport($jadwals), $filename);
    }

    /**
     * Helper privat untuk query jadwal (agar DRY)
     */
    private function getJadwalData($guru)
    {
        return $guru->jadwals()
            ->with(['kelas', 'mapel'])
            ->where('is_active', true)
            ->orderByRaw("
                FIELD(
                    hari,
                    'Senin',
                    'Selasa',
                    'Rabu',
                    'Kamis',
                    'Jumat',
                    'Sabtu'
                )
            ")
            ->orderBy('jam_masuk')
            ->get();
    }
}