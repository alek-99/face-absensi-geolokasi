<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Services\ReportPdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class JadwalController extends Controller
{
     public function __construct(protected ReportPdfService $reportPdfService)
    {
    }
    public function index(){
          $guru = Auth::user()->guru;

    abort_if(!$guru, 404);

    $jadwals = $guru->jadwals()
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

    // Kelompokkan berdasarkan hari
    $jadwalPerHari = $jadwals->groupBy('hari');

    // Ringkasan statistik
    $statistik = [
        'total_jadwal'  => $jadwals->count(),
        'total_mapel'   => $jadwals->pluck('mata_pelajaran')->unique()->count(),
        'hari_mengajar' => $jadwals->pluck('hari')->unique()->count(),
        'total_kelas'   => $jadwals->pluck('kelas')->unique()->count(),
        'total_ruangan' => $jadwals->pluck('ruangan')->unique()->count(),
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

    $jadwals = $guru->jadwals()
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

    return $this->reportPdfService->stream(
        slug: 'jadwal-guru',

        view: 'pdf.guru.jadwal',

        data: [
            'guru' => $guru,
            'jadwals' => $jadwals,
        ],

        filename: 'jadwal-' . Str::slug($guru->name ?? 'guru') . '.pdf'
    );
}
}
