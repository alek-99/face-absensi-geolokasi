<?php

namespace App\Exports;

use App\Models\Jadwal;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class JadwalExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithCustomStartCell
{
    protected $no = 0;

    /**
    * Menentukan sel awal tempat tabel dimulai (karena baris 1-3 digunakan untuk Judul Kop)
    */
    public function startCell(): string
    {
        return 'A4';
    }

    /**
    * Mengambil data jadwal yang aktif
    */
    public function collection()
    {
        return Jadwal::with(['guru.user', 'mapel', 'kelas'])
            ->where('is_active', true)
            ->orderByRaw("FIELD(hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu')")
            ->orderBy('jam_masuk')
            ->get();
    }

    /**
    * Header Judul Kolom Excel (Mulai di baris A4)
    */
    public function headings(): array
    {
        return [
            'No',
            'Hari',
            'Jam Masuk',
            'Jam Pulang',
            'Guru Pengajar',
            'Mata Pelajaran',
            'Kelas',
            'Status',
        ];
    }

    /**
    * Pemetaan Data per Baris
    */
    public function map($jadwal): array
    {
        $this->no++;

        $jamMasuk = $jadwal->jam_masuk ? Carbon::parse($jadwal->jam_masuk)->format('H:i') : '-';
        $jamPulang = $jadwal->jam_pulang ? Carbon::parse($jadwal->jam_pulang)->format('H:i') : '-';
        $namaGuru = $jadwal->guru->nama ?? $jadwal->guru->user->name ?? '-';

        return [
            $this->no,
            $jadwal->hari,
            $jamMasuk,
            $jamPulang,
            $namaGuru,
            $jadwal->mapel->nama_mapel ?? '-',
            $jadwal->kelas->nama_kelas ?? '-',
            $jadwal->is_active ? 'Aktif' : 'Nonaktif',
        ];
    }

    /**
    * Styling Tampilan Excel
    */
    public function styles(Worksheet $sheet)
    {
        // 1. Tambahkan Judul di Baris 1 & 2
        $sheet->setCellValue('A1', 'JADWAL MENGAJAR GURU ');
        $sheet->setCellValue('A2', 'SMK MAULANA HASANUDIN');

        // Merge Cell Judul dari Kolom A sampai H
        $sheet->mergeCells('A1:H1');
        $sheet->mergeCells('A2:H2');

        // Style untuk Judul Baris 1
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Style untuk Judul Baris 2
        $sheet->getStyle('A2')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 11,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        $highestRow = $sheet->getHighestRow();

        // 2. Styling Header Tabel (Baris 4)
        $sheet->getStyle('A4:H4')->applyFromArray([
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

        // 3. Border untuk seluruh tabel data (Mulai Baris 4 sampai Baris Terakhir)
        if ($highestRow >= 4) {
            $sheet->getStyle("A4:H{$highestRow}")->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ]);

            // Rata Tengah Kolom No, Hari, Jam Masuk, Jam Pulang, Kelas, Status
            $sheet->getStyle("A5:D{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("G5:H{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        return [];
    }
}