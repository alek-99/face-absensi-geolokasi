<?php

namespace App\Exports;

use App\Models\Absensi;
use App\Models\Guru;
use App\Models\Sekolah;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class AbsensiGuruExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    protected int $rowNumber = 0;

    public function __construct(
        protected Guru $guru,
        protected int $bulan,
        protected int $tahun
    ) {}

    public function collection()
    {
        return Absensi::where('guru_id', $this->guru->id)
            ->whereMonth('tanggal', $this->bulan)
            ->whereYear('tanggal', $this->tahun)
            ->orderBy('tanggal', 'asc')
            ->get();
    }

    public function title(): string
    {
        return "Absensi " . Carbon::create()->month($this->bulan)->translatedFormat('F') . " {$this->tahun}";
    }

    public function headings(): array
    {
        $sekolah = Sekolah::first();
        $namaSekolah = $sekolah?->nama_sekolah ?? 'SEKOLAH';
        $namaBulan = Carbon::createFromDate($this->tahun, $this->bulan, 1)->translatedFormat('F Y');

        // Menghitung Statistik
        $dataAbsensi = $this->collection();
        
        $totalMasuk      = $dataAbsensi->whereNotNull('jam_masuk')->count();
        $totalPulang     = $dataAbsensi->whereNotNull('jam_pulang')->count();
        $totalHadir      = $dataAbsensi->where('status', 'hadir')->count();
        $totalTerlambat  = $dataAbsensi->where('status', 'terlambat')->count();
        $totalIzin       = $dataAbsensi->where('status', 'izin')->count();
        $totalSakit      = $dataAbsensi->where('status', 'sakit')->count();
        $totalCuti       = $dataAbsensi->where('status', 'cuti')->count();
        $totalTugasLuar  = $dataAbsensi->where('status', 'tugas_luar')->count();
        $totalAlpa       = $dataAbsensi->where('status', 'alpha')->count();

        return [
            [$namaSekolah],
            ["LAPORAN REKAPITULASI ABSENSI GURU"],
            ["Periode: {$namaBulan}"],
            ["Nama Guru: {$this->guru->nama} (NIP: " . ($this->guru->nip ?? '-') . ")"],
            [], // Baris kosong
            ["RINGKASAN STATISTIK ABSENSI"],
            ["Total Absen Masuk", $totalMasuk, "", "Total Izin", $totalIzin],
            ["Total Absen Pulang", $totalPulang, "", "Total Sakit", $totalSakit],
            ["Total Hadir Tepat Waktu", $totalHadir, "", "Total Cuti", $totalCuti],
            ["Total Terlambat", $totalTerlambat, "", "Total Tugas Luar", $totalTugasLuar],
            ["Total Alpa (Tanpa Keterangan)", $totalAlpa, "", "", ""],
            [], // Baris kosong
            // Header Tabel Utama
            [
                'No',
                'Tanggal',
                'Hari',
                'Jam Masuk',
                'Jam Pulang',
                'Status',
                'Keterangan'
            ]
        ];
    }

  
    public function styles(Worksheet $sheet)
    {
        // Styling Judul
        $sheet->mergeCells('A1:G1');
        $sheet->mergeCells('A2:G2');
        $sheet->mergeCells('A3:G3');
        $sheet->mergeCells('A4:G4');
        
        $sheet->getStyle('A1:A4')->getFont()->setBold(true);
        $sheet->getStyle('A1:A2')->getFont()->setSize(14);
        $sheet->getStyle('A1:A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Styling Blok Statistik
        $sheet->mergeCells('A6:G6');
        $sheet->getStyle('A6')->getFont()->setBold(true);
        $sheet->getStyle('A6')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('E2EFDA'); // Hijau Muda

        $sheet->getStyle('A7:E11')->getFont()->setBold(true);

        // Header Tabel Data (Baris ke-13)
        $sheet->getStyle('A13:G13')->getFont()->setBold(true);
        $sheet->getStyle('A13:G13')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('4F81BD'); // Biru
        $sheet->getStyle('A13:G13')->getFont()->getColor()->setARGB('FFFFFF');
        $sheet->getStyle('A13:G13')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Alignment Kolom Tabel Data
        $highestRow = $sheet->getHighestRow();
        if ($highestRow >= 14) {
            $sheet->getStyle("A14:A{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("B14:E{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("F14:F{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            
            // Border untuk seluruh tabel data
            $sheet->getStyle("A13:G{$highestRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        }

        // Border untuk Ringkasan Statistik
        $sheet->getStyle("A6:E11")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        return [];
    }

    private function namaHariIndonesia(Carbon $date): string
    {
        $map = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ];

        return $map[$date->format('l')] ?? '-';
    }

    public function map($row): array
{
    $this->rowNumber++;
    $tanggal = Carbon::parse($row->tanggal);

    // Karena jam_masuk & jam_pulang di model adalah instance Carbon:
    $jamMasuk = $row->jam_masuk ? $row->jam_masuk->format('H:i') : '-';
    $jamPulang = $row->jam_pulang ? $row->jam_pulang->format('H:i') : '-';

    return [
        $this->rowNumber,
        $tanggal->format('d/m/Y'),
        $this->namaHariIndonesia($tanggal),
        $jamMasuk,
        $jamPulang,
        strtoupper(str_replace('_', ' ', $row->status)),
        $row->keterangan ?? '-'
    ];
}
}