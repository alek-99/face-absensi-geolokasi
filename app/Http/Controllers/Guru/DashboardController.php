<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
  public function index()
{
    // Ambil guru_id dari user yang login
    $guruId = Auth::user()->guru_id; 

    // Ambil bulan dan tahun real-time saat ini
    $bulanSekarang = Carbon::now()->month;
    $tahunSekarang = Carbon::now()->year;

    // Hitung statistik khusus bulan ini dalam satu query efisien
    $statistikBulanIni = Absensi::where('guru_id', $guruId)
        ->whereMonth('tanggal', $bulanSekarang)
        ->whereYear('tanggal', $tahunSekarang)
        ->selectRaw("
            SUM(CASE WHEN status IN ('hadir', 'terlambat') THEN 1 ELSE 0 END) as total_hadir,
            SUM(CASE WHEN status IN ('izin', 'sakit', 'cuti', 'tugas_luar') THEN 1 ELSE 0 END) as total_izin,
            SUM(CASE WHEN status = 'alpha' THEN 1 ELSE 0 END) as total_alpha
        ")
        ->first();

    // Mapping nilai ke variabel dengan fallback 0 jika belum ada data sama sekali
    $totalHadir = $statistikBulanIni->total_hadir ?? 0;
    $totalIzin  = $statistikBulanIni->total_izin ?? 0;
    $totalAlpha = $statistikBulanIni->total_alpha ?? 0;

    return view('guru.dashboard', compact('totalHadir', 'totalIzin', 'totalAlpha'));
}
}