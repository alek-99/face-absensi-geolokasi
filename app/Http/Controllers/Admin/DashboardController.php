<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
      // 1. DATA UNTUK GRAFIK MINGGUAN (Line / Bar Chart)
        // Mengambil data 7 hari terakhir
        $tujuhHariLalu = Carbon::now()->subDays(6)->format('Y-m-d');
        $hariIni = Carbon::now()->format('Y-m-d');

        $trenAbsensiRaw = Absensi::select(
                'tanggal',
                DB::raw("SUM(CASE WHEN status IN ('hadir', 'terlambat') THEN 1 ELSE 0 END) as total_masuk"),
                DB::raw("SUM(CASE WHEN status IN ('izin', 'sakit', 'cuti', 'tugas_luar') THEN 1 ELSE 0 END) as total_absen"),
                DB::raw("SUM(CASE WHEN status = 'alpha' THEN 1 ELSE 0 END) as total_alpha")
            )
            ->whereBetween('tanggal', [$tujuhHariLalu, $hariIni])
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();

        // Formatting data agar siap dibaca oleh Javascript Chart.js
        $chartLabels = [];
        $chartDataMasuk = [];
        $chartDataAbsen = [];
        $chartDataAlpha = [];

        foreach ($trenAbsensiRaw as $data) {
            // Mengubah format tanggal menjadi nama hari (contoh: "Senin", "Selasa")
            $chartLabels[] = Carbon::parse($data->tanggal)->translatedFormat('l'); 
            $chartDataMasuk[] = (int) $data->total_masuk;
            $chartDataAbsen[] = (int) $data->total_absen;
            $chartDataAlpha[] = (int) $data->total_alpha;
        }


        // 2. DATA UNTUK RATIO HARI INI (Doughnut / Pie Chart)
        $statusHariIniRaw = Absensi::select('status', DB::raw('count(*) as total'))
            ->whereDate('tanggal', $hariIni)
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // Pastikan semua status memiliki nilai default 0 jika belum ada data hari ini
        $statusList = ['hadir', 'terlambat', 'izin', 'sakit', 'cuti', 'tugas_luar', 'alpha'];
        $chartPieData = [];
        
        foreach ($statusList as $status) {
            $chartPieData[] = $statusHariIniRaw[$status] ?? 0;
        }


        // 3. RETURNING DATA KE VIEW
        // Sertakan juga variable counter statistik lama Anda di sini
        $totalGuru = Guru::count(); 
        $totalJadwal = Jadwal::count(); // Sesuaikan nama model Anda
        $totalPengguna = User::count();

        return view('admin.dashboard', compact(
            'totalGuru', 
            'totalJadwal', 
            'totalPengguna',
            'chartLabels',
            'chartDataMasuk',
            'chartDataAbsen',
            'chartDataAlpha',
            'chartPieData'
        ));
    }
}
