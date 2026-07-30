<?php

namespace App\Exports;

use App\Models\Absensi;
use App\Models\Guru;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class AbsensiExport implements WithEvents
{
    protected $bulan;
    protected $tahun;
    protected $guruId;
    protected $status;

    public function __construct($bulan = null, $tahun = null, $guruId = null, $status = null)
    {
        $this->bulan = $bulan ?? date('m');
        $this->tahun = $tahun ?? date('Y');
        $this->guruId = $guruId;
        $this->status = $status;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // -------------------------------------------------------------
                // 1. QUERY DATA & STATISTIK
                // -------------------------------------------------------------
                $query = Absensi::with('guru.user')
                    ->whereMonth('tanggal', $this->bulan)
                    ->whereYear('tanggal', $this->tahun)
                    ->orderBy('tanggal', 'asc');

                if ($this->guruId) {
                    $query->where('guru_id', $this->guruId);
                }

                if ($this->status) {
                    $query->where('status', $this->status);
                }

                $data = $query->get();

                // Hitung Statistik
                $totalRecord   = $data->count();
                $totalHadir    = $data->where('status', 'hadir')->count();
                $totalIzin     = $data->where('status', 'izin')->count();
                $totalSakit    = $data->where('status', 'sakit')->count();
                $totalAlpha    = $data->where('status', 'alpha')->count();
                $totalDisetujui= $data->where('status_persetujuan', 'disetujui')->count();
                $totalDitolak  = $data->where('status_persetujuan', 'ditolak')->count();

                $namaBulan = Carbon::createFromDate($this->tahun, $this->bulan, 1)->translatedFormat('F');

                // -------------------------------------------------------------
                // 2. HEADER REKAPITULASI
                // -------------------------------------------------------------
                $sheet->mergeCells('A1:J1');
                $sheet->setCellValue('A1', 'REKAPITULASI ABSENSI GURU SMK MAULANA HASANUDIN CILELES BANTEN');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('1F2937'));
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->mergeCells('A2:J2');
                $sheet->setCellValue('A2', "PERIODE: " . strtoupper($namaBulan) . " " . $this->tahun);
                $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(11)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('4B5563'));
                $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // -------------------------------------------------------------
                // 3. STATISTIK LENGKAP (RINGKASAN)
                // -------------------------------------------------------------
                $sheet->setCellValue('A4', 'STATISTIK KEHADIRAN');
                $sheet->getStyle('A4')->getFont()->setBold(true)->setSize(11);

                // Box Statistik Grid 1
                $sheet->setCellValue('A5', 'Total Hadir:');
                $sheet->setCellValue('B5', $totalHadir . ' Hari');
                $sheet->setCellValue('D5', 'Total Izin:');
                $sheet->setCellValue('E5', $totalIzin . ' Hari');
                $sheet->setCellValue('G5', 'Total Sakit:');
                $sheet->setCellValue('H5', $totalSakit . ' Hari');

                $sheet->setCellValue('A6', 'Total Alpha:');
                $sheet->setCellValue('B6', $totalAlpha . ' Hari');
                $sheet->setCellValue('D6', 'Izin Disetujui:');
                $sheet->setCellValue('E6', $totalDisetujui);
                $sheet->setCellValue('G6', 'Izin Ditolak:');
                $sheet->setCellValue('H6', $totalDitolak);

                // Format Statistik Label Bold
                $sheet->getStyle('A5:A6')->getFont()->setBold(true);
                $sheet->getStyle('D5:D6')->getFont()->setBold(true);
                $sheet->getStyle('G5:G6')->getFont()->setBold(true);

                // Style Box Statistik
                $sheet->getStyle('A4:H6')->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('F3F4F6');

                // -------------------------------------------------------------
                // 4. HEADER TABEL UTAMA
                // -------------------------------------------------------------
                $startRow = 9;
                $headers = [
                    'No',
                    'Tanggal',
                    'Nama Guru',
                    'Jam Masuk',
                    'Jam Pulang',
                    'Status',
                    'Status Persetujuan',
                    'Keterangan / Catatan',
                    'Confidence Masuk',
                    'Confidence Pulang'
                ];

                $sheet->fromArray([$headers], NULL, 'A' . $startRow);

                // Header Table Styling
                $headerRange = "A{$startRow}:J{$startRow}";
                $sheet->getStyle($headerRange)->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFF'));
                $sheet->getStyle($headerRange)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('1E3A8A'); // Navy Blue
                $sheet->getStyle($headerRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // -------------------------------------------------------------
                // 5. ISI DATA TABEL
                // -------------------------------------------------------------
                $currentRow = $startRow + 1;
                $no = 1;

                foreach ($data as $item) {
                    $namaGuru = $item->guru->user->name ?? $item->guru->nama ?? '-';
                    $tanggal  = Carbon::parse($item->tanggal)->translatedFormat('d/m/Y');
                    $jamMasuk = $item->jam_masuk ? Carbon::parse($item->jam_masuk)->format('H:i:s') : '-';
                    $jamPulang = $item->jam_pulang ? Carbon::parse($item->jam_pulang)->format('H:i:s') : '-';
                    $confMasuk = $item->confidence_masuk ? number_format($item->confidence_masuk, 2) . '%' : '-';
                    $confPulang = $item->confidence_pulang ? number_format($item->confidence_pulang, 2) . '%' : '-';

                    $sheet->setCellValue('A' . $currentRow, $no++);
                    $sheet->setCellValue('B' . $currentRow, $tanggal);
                    $sheet->setCellValue('C' . $currentRow, $namaGuru);
                    $sheet->setCellValue('D' . $currentRow, $jamMasuk);
                    $sheet->setCellValue('E' . $currentRow, $jamPulang);
                    $sheet->setCellValue('F' . $currentRow, strtoupper($item->status));
                    $sheet->setCellValue('G' . $currentRow, ucfirst($item->status_persetujuan ?? 'Otomatis'));
                    $sheet->setCellValue('H' . $currentRow, $item->keterangan ?? '-');
                    $sheet->setCellValue('I' . $currentRow, $confMasuk);
                    $sheet->setCellValue('J' . $currentRow, $confPulang);

                    // Zebra striping & alignment
                    if ($currentRow % 2 == 0) {
                        $sheet->getStyle("A{$currentRow}:J{$currentRow}")->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()->setARGB('F9FAFB');
                    }

                    $sheet->getStyle("A{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("B{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("D{$currentRow}:G{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("I{$currentRow}:J{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                    $currentRow++;
                }

                $endRow = $currentRow - 1;

                // -------------------------------------------------------------
                // 6. BORDER & COLUMN WIDTH ADJUSTMENT
                // -------------------------------------------------------------
                if ($endRow >= $startRow) {
                    $tableRange = "A{$startRow}:J{$endRow}";
                    $sheet->getStyle($tableRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                    $sheet->getStyle($tableRange)->getBorders()->getAllBorders()->getColor()->setARGB('D1D5DB');
                }

                // Auto width untuk tiap kolom
                foreach (range('A', 'J') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}