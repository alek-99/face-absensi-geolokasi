<?php

namespace App\Exports;

use App\Models\Jadwal;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class JadwalExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $no = 0;

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
    * Header Judul Kolom Excel
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
        $highestRow = $sheet->getHighestRow();

        // Styling untuk Header (Baris 1)
        $sheet->getStyle('A1:H1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F46E5'], // Warna Indio-600
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Styling untuk Semua Sel Data (Border & Alignment)
        $sheet->getStyle("A1:H{$highestRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Rata Tengah Kolom No, Hari, Jam Masuk, Jam Pulang, Kelas, Status
        $sheet->getStyle("A2:D{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("G2:H{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        return [];
    }
}