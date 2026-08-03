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
        $user = Auth::user();

        // 1. Dapatkan guru_id (fallback ke relasi jika $user->guru_id tidak langsung tersimpan)
        $guruId = $user->guru_id ?? optional($user->guru)->id;

        // 2. Tentukan range tanggal awal dan akhir bulan ini (Lebih presisi & aman index DB)
        $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
        $endOfMonth   = Carbon::now()->endOfMonth()->toDateString();

        // 3. Query Statistik Kehadiran Bulan Ini
        $statistik = Absensi::where('guru_id', $guruId)
            ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->selectRaw("
                SUM(CASE WHEN LOWER(status) IN ('hadir', 'terlambat') THEN 1 ELSE 0 END) as total_hadir,
                SUM(CASE WHEN LOWER(status) IN ('izin', 'sakit', 'cuti', 'tugas_luar', 'sakit/izin') THEN 1 ELSE 0 END) as total_izin,
                SUM(CASE WHEN LOWER(status) IN ('alpha', 'alpa') THEN 1 ELSE 0 END) as total_alpha
            ")
            ->first();

        // 4. Extract data dengan fallback angka 0
        $totalHadir = (int) ($statistik->total_hadir ?? 0);
        $totalIzin  = (int) ($statistik->total_izin ?? 0);
        $totalAlpha = (int) ($statistik->total_alpha ?? 0);

        return view('guru.dashboard', compact('totalHadir', 'totalIzin', 'totalAlpha'));
    }
}