<?php

namespace App\Exports;

use App\Models\Guru;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class JadwalGuruExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithCustomStartCell, WithEvents
{
    protected Collection $jadwals;
    protected ?Guru $guru;
    protected int $rowNumber = 0;

    public function __construct(Collection $jadwals, ?Guru $guru = null)
    {
        $this->jadwals = $jadwals;
        $this->guru = $guru;
    }

    public function collection(): Collection
    {
        return $this->jadwals;
    }

    /**
     * Memulai penulisan tabel dari baris ke-4 agar baris 1-2 bisa diisi Title Header
     */
    public function startCell(): string
    {
        return 'A4';
    }

    public function headings(): array
    {
        return [
            'No',
            'Hari',
            'Jam Masuk',
            'Jam Pulang',
            'Mata Pelajaran',
            'Kelas',
            'Ruangan',
            'Status',
        ];
    }

    /**
     * Format data per baris
     * @param mixed $jadwal
     */
    public function map($jadwal): array
    {
        $this->rowNumber++;

        $jamMasuk = $jadwal->jam_masuk ? \Carbon\Carbon::parse($jadwal->jam_masuk)->format('H:i') : '-';
        $jamPulang = $jadwal->jam_pulang ? \Carbon\Carbon::parse($jadwal->jam_pulang)->format('H:i') : '-';

        return [
            $this->rowNumber,
            $jadwal->hari ?? '-',
            $jamMasuk,
            $jamPulang,
            $jadwal->mapel->nama_mapel ?? '-',
            $jadwal->kelas->nama_kelas ?? '-',
            $jadwal->kelas->ruangan ?? '-',
            $jadwal->is_active ? 'Aktif' : 'Non-Aktif',
        ];
    }

    /**
     * Styling untuk area Header dan Data
     */
    public function styles(Worksheet $sheet): array
    {
        $lastRow = $this->rowNumber + 4; // Baris terakhir data

        // Style untuk Header Tabel (Baris 4)
        $sheet->getStyle('A4:H4')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1E4D2B'], // Vocal Green / Emerald Dark
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Alignment Data Tabel
        $sheet->getStyle("A5:A{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // No
        $sheet->getStyle("B5:B{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Hari
        $sheet->getStyle("C5:D{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Jam
        $sheet->getStyle("H5:H{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Status

        // Border & Padding untuk seluruh isi tabel
        $sheet->getStyle("A4:H{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'D3D3D3'],
                ],
            ],
        ]);

        return [];
    }

    /**
     * Event Handler untuk Menambahkan Judul / Sub-Header di Atas Tabel
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $namaGuru = strtoupper($this->guru->nama ?? 'GURU');

                // 1. Set Title Header (Row 1)
                $sheet->mergeCells('A1:H1');
                $sheet->setCellValue('A1', "JADWAL MENGAJAR - {$namaGuru}");
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                        'color' => ['rgb' => '1E4D2B'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // 2. Set Subtitle Header (Row 2)
                $sheet->mergeCells('A2:H2');
                $sheet->setCellValue('A2', 'Agenda Mengajar Aktif');
                $sheet->getStyle('A2')->applyFromArray([
                    'font' => [
                        'italic' => true,
                        'size' => 10,
                        'color' => ['rgb' => '555555'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Adjust Tinggi Baris
                $sheet->getRowDimension(1)->setRowHeight(30);
                $sheet->getRowDimension(2)->setRowHeight(20);
                $sheet->getRowDimension(4)->setRowHeight(25); // Header Table
            },
        ];
    }
}