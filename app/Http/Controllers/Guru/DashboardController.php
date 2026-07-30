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
        $now = Carbon::now();

        // Hitung statistik khusus bulan ini dalam satu query efisien
        $statistikBulanIni = Absensi::where('guru_id', $guruId)
            ->whereMonth('tanggal', $now->month)
            ->whereYear('tanggal', $now->year)
            ->selectRaw("
                COALESCE(SUM(CASE WHEN status IN ('hadir', 'terlambat') THEN 1 ELSE 0 END), 0) as total_hadir,
                COALESCE(SUM(CASE WHEN status IN ('izin', 'sakit', 'cuti', 'tugas_luar') THEN 1 ELSE 0 END), 0) as total_izin,
                COALESCE(SUM(CASE WHEN status = 'alpha' THEN 1 ELSE 0 END), 0) as total_alpha
            ")
            ->first();

        // Casting ke integer untuk memastikan nilai numeric
        $totalHadir = (int) ($statistikBulanIni->total_hadir ?? 0);
        $totalIzin  = (int) ($statistikBulanIni->total_izin ?? 0);
        $totalAlpha = (int) ($statistikBulanIni->total_alpha ?? 0);

        return view('guru.dashboard', compact('totalHadir', 'totalIzin', 'totalAlpha'));
    }
}