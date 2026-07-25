<?php

namespace App\Services;

use App\Models\ReportSetting;
use App\Models\Sekolah;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class ReportPdfService
{
    /**
     * Generate PDF stream (tampilan langsung di browser).
     *
     * @param string $view Path ke file blade (contoh: 'pdf.guru.jadwal')
     * @param array $data Data yang dikirim ke view
     * @param string $filename Nama file output PDF
     * @return Response
     */
    public function stream(string $view, array $data = [], string $filename = 'dokumen.pdf'): Response
    {
        $pdf = $this->buildPdf($view, $data);

        return $pdf->stream($filename);
    }

    /**
     * Generate PDF download (langsung unduh file).
     */
    public function download(string $view, array $data = [], string $filename = 'dokumen.pdf'): Response
    {
        $pdf = $this->buildPdf($view, $data);

        return $pdf->download($filename);
    }

    /**
     * Membangun instance PDF beserta pengaturan dari Admin & Sekolah.
     */
    protected function buildPdf(string $view, array $data = [])
    {
        // 1. Ambil pengaturan global laporan dari Admin
        $setting = ReportSetting::current();

        // 2. Ambil data profil sekolah
        $sekolah = Sekolah::first() ?? new Sekolah();

        // 3. Penggabungan data otomatis ke view
        $viewData = array_merge([
            'setting' => $setting,
            'sekolah' => $sekolah,
        ], $data);

        // 4. Load view ke DomPDF
        $pdf = Pdf::loadView($view, $viewData);

        // 5. Set Ukuran Kertas & Orientasi berdasarkan setting Admin
        $paperSize = $setting->paper_size ?? 'A4';
        $orientation = $setting->orientation ?? 'portrait';

        // Penanganan Khusus Kertas F4/Folio
        if (strtoupper($paperSize) === 'F4') {
            $pdf->setPaper([0, 0, 595.28, 935.43], $orientation);
        } else {
            $pdf->setPaper($paperSize, $orientation);
        }

        // 6. Konfigurasi tambahan DomPDF
        $pdf->setOption([
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'defaultFont' => $setting->font_family ?? 'sans-serif',
        ]);

        return $pdf;
    }
}