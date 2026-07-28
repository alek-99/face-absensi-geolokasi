<?php

namespace App\Exports;

use App\Models\Jadwal;
use App\Models\Siswa;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AbsenSiswaExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithCustomStartCell, WithStyles, ShouldAutoSize
{
    protected Jadwal $jadwal;
    protected ?string $bulan;
    protected int $no = 1;
    protected int $totalRows = 0;

    public function __construct(Jadwal $jadwal, ?string $bulan = null)
    {
        $this->jadwal = $jadwal;
        $this->bulan = $bulan;
    }

    public function title(): string
    {
        return 'Rekap Absen ' . ($this->jadwal->kelas?->nama_kelas ?? '');
    }

    /**
     * Menentukan baris awal dimulainya tabel (Baris ke-8 agar ada ruang untuk Header Info)
     */
    public function startCell(): string
    {
        return 'A8';
    }

    public function collection()
    {
        $siswas = Siswa::where('kelas_id', $this->jadwal->kelas_id)
            ->with(['absenSiswa' => function ($query) {
                $query->where('jadwal_id', $this->jadwal->id);
                if ($this->bulan) {
                    $query->whereMonth('tanggal', substr($this->bulan, 5, 2))
                          ->whereYear('tanggal', substr($this->bulan, 0, 4));
                }
            }])
            ->orderBy('nama')
            ->get();

        $this->totalRows = $siswas->count();

        return $siswas;
    }

    public function headings(): array
    {
        return [
            'No',
            'NISN',
            'Nama Siswa',
            'Hadir',
            'Izin',
            'Sakit',
            'Alfa',
            'Total Pertemuan',
        ];
    }

    /**
     * @param Siswa $siswa
     */
    public function map($siswa): array
    {
        $absen = $siswa->absenSiswa;

        $hadir = $absen->where('status', 'Hadir')->count();
        $izin  = $absen->where('status', 'Izin')->count();
        $sakit = $absen->where('status', 'Sakit')->count();
        $alfa  = $absen->where('status', 'Alfa')->count();

        return [
            $this->no++,
            $siswa->nisn ?? '-',
            $siswa->nama,
            $hadir,
            $izin,
            $sakit,
            $alfa,
            $absen->count(),
        ];
    }

    /**
     * Styling untuk Header Informasi dan Tabel
     */
    public function styles(Worksheet $sheet)
    {
        // 1. Format Nama Bulan & Tahun untuk Header
        $periodeText = 'Semua Periode';
        if ($this->bulan) {
            $dt = Carbon::createFromFormat('Y-m', $this->bulan);
            $periodeText = $dt->translatedFormat('F Y'); // Contoh: Juli 2026
        }

        // 2. Mengisi Data Header & Detail Informasi (Baris 1 - 6)
        $sheet->setCellValue('A1', 'REKAPITULASI ABSENSI SISWA');
        $sheet->setCellValue('A3', 'Mata Pelajaran');
        $sheet->setCellValue('B3', ': ' . ($this->jadwal->mapel?->nama_mapel ?? '-'));
        
        $sheet->setCellValue('A4', 'Kelas');
        $sheet->setCellValue('B4', ': ' . ($this->jadwal->kelas?->nama_kelas ?? '-'));
        
        $sheet->setCellValue('A5', 'Periode');
        $sheet->setCellValue('B5', ': ' . $periodeText);

        // 3. Merge Cell Judul Utama
        $sheet->mergeCells('A1:H1');

        // 4. Styling Judul Utama
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['rgb' => '1E293B'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // 5. Styling Information Label (A3:A5)
        $sheet->getStyle('A3:A5')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => '475569']],
        ]);

        // 6. Styling Table Header (Baris 8)
        $sheet->getStyle('A8:H8')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F46E5'], // Warna Indigo-600
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // 7. Styling Data & Border Tabel (Baris 8 s/d Selesai)
        $lastRow = 8 + $this->totalRows;

        $sheet->getStyle("A8:H{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CBD5E1'],
                ],
            ],
        ]);

        // Alignment Rata Tengah untuk kolom No, NISN, Hadir, Izin, Sakit, Alfa, Total
        $sheet->getStyle("A9:B{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("D9:H{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        return [];
    }
}