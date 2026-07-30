<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. DATA UNTUK GRAFIK MINGGUAN (7 Hari Terakhir)
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $trenAbsensiRaw = Absensi::select(
                'tanggal',
                DB::raw("SUM(CASE WHEN status IN ('hadir', 'terlambat') THEN 1 ELSE 0 END) as total_masuk"),
                DB::raw("SUM(CASE WHEN status IN ('izin', 'sakit', 'cuti', 'tugas_luar') THEN 1 ELSE 0 END) as total_absen"),
                DB::raw("SUM(CASE WHEN status = 'alpha' THEN 1 ELSE 0 END) as total_alpha")
            )
            ->whereBetween('tanggal', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->groupBy('tanggal')
            ->get()
            ->keyBy(function ($item) {
                return Carbon::parse($item->tanggal)->format('Y-m-d');
            });

        // Loop 7 hari presisi agar hari tanpa data tetap terisi nilai 0
        $chartLabels = [];
        $chartDataMasuk = [];
        $chartDataAbsen = [];
        $chartDataAlpha = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dateKey = $date->format('Y-m-d');

            $chartLabels[] = $date->translatedFormat('l (d/m)');

            if (isset($trenAbsensiRaw[$dateKey])) {
                $chartDataMasuk[] = (int) $trenAbsensiRaw[$dateKey]->total_masuk;
                $chartDataAbsen[] = (int) $trenAbsensiRaw[$dateKey]->total_absen;
                $chartDataAlpha[] = (int) $trenAbsensiRaw[$dateKey]->total_alpha;
            } else {
                $chartDataMasuk[] = 0;
                $chartDataAbsen[] = 0;
                $chartDataAlpha[] = 0;
            }
        }

        // 2. DATA UNTUK RATIO HARI INI (Doughnut / Pie Chart)
        $hariIni = Carbon::now()->format('Y-m-d');
        $statusHariIniRaw = Absensi::select('status', DB::raw('count(*) as total'))
            ->whereDate('tanggal', $hariIni)
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $statusList = ['hadir', 'terlambat', 'izin', 'sakit', 'cuti', 'tugas_luar', 'alpha'];
        $chartPieData = [];
        
        foreach ($statusList as $status) {
            $chartPieData[] = $statusHariIniRaw[$status] ?? 0;
        }

        // 3. STATISTIK COUNTER
        $totalGuru     = Guru::count(); 
        $totalJadwal   = Jadwal::count();
        $totalPengguna = User::count();
        $totalSiswa    = Siswa::count();
        $totalMapel    = Mapel::count();
        $totalKelas    = Kelas::count();

        return view('admin.dashboard', compact(
            'totalGuru', 
            'totalJadwal', 
            'totalPengguna',
            'chartLabels',
            'chartDataMasuk',
            'chartDataAbsen',
            'chartDataAlpha',
            'chartPieData',
            'totalKelas',
            'totalMapel',
            'totalSiswa'
        ));
    }
}