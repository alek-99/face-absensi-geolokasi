<?php

namespace Database\Seeders;

use App\Models\ReportSetting;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        if (ReportSetting::count() === 0) {

            ReportSetting::create([

                'show_logo' => true,

                'show_school_name' => true,

                'show_address' => true,

                'show_email' => true,

                'show_phone' => true,

                'show_header_line' => true,

                'header_line_style' => 'double',

                'font_family' => 'Times New Roman',

                'font_size' => 11,

                'paper_size' => 'A4',

                'orientation' => 'portrait',

                'margin_top' => 15,

                'margin_bottom' => 15,

                'margin_left' => 15,

                'margin_right' => 15,

                'show_signature' => true,

                'show_school_stamp' => true,

                'city' => 'Serang',

                'signer_name' => 'Kepala Sekolah',

                'signer_position' => 'Kepala Sekolah',

                'signer_nip' => '',

                'signature_file' => null,

                'stamp_file' => null,

                'show_print_date' => true,

                'show_page_number' => true,

                'show_qrcode' => false,

            ]);

        }
    }
}