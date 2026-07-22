<?php

namespace App\Services;

use App\Models\ReportSetting;
use App\Models\Sekolah;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class ReportPdfService
{
    public function __construct(
        protected ReportResolver $resolver
    ) {
    }

    /**
     * Generate PDF
     */
    public function generate(
        string $slug,
        string $view,
        array $data = []
    ) {
        $setting = ReportSetting::current();
        $sekolah = Sekolah::current();

        $template = $this->resolver->resolve($slug);

        /*
        |----------------------------------------------------------
        | Jika template belum dibuat admin
        | PDF tetap bisa dicetak menggunakan judul bawaan.
        |----------------------------------------------------------
        */
        if (!$template) {
            $template = (object) [
                'kode'            => null,
                'slug'            => $slug,
                'judul_laporan'   => 'Laporan',
                'subjudul'        => null,
                'deskripsi'       => null,
                'is_active'       => true,
            ];
        }

        $payload = array_merge($data, [
            'setting'  => $setting,
            'sekolah'  => $sekolah,
            'template' => $template,
        ]);

        return Pdf::loadView($view, $payload)
            ->setPaper(
                $this->resolvePaperSize($setting->paper_size),
                $setting->orientation
            )
            ->setOption('defaultFont', $setting->font_family)
            ->setOption('isRemoteEnabled', true)
            ->setOption('isPhpEnabled', true);
    }

    /**
     * Stream PDF
     */
    public function stream(
        string $slug,
        string $view,
        array $data = [],
        ?string $filename = null
    ) {
        $filename ??= $this->defaultFilename($slug);

        return $this->generate(
            $slug,
            $view,
            $data
        )->stream($filename);
    }

    /**
     * Download PDF
     */
    public function download(
        string $slug,
        string $view,
        array $data = [],
        ?string $filename = null
    ) {
        $filename ??= $this->defaultFilename($slug);

        return $this->generate(
            $slug,
            $view,
            $data
        )->download($filename);
    }

    private function resolvePaperSize(string $paperSize): array|string
    {
        return match ($paperSize) {
            'F4' => [0, 0, 609.4488, 935.433],
            default => strtolower($paperSize),
        };
    }

    private function defaultFilename(string $slug): string
    {
        return Str::slug($slug)
            . '-'
            . now()->format('Ymd-His')
            . '.pdf';
    }
}