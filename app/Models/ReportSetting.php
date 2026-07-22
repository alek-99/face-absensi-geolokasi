<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        // Header
        'show_logo',
        'show_school_name',
        'show_address',
        'show_email',
        'show_phone',
        'show_header_line',
        'header_line_style',

        // Body
        'font_family',
        'font_size',
        'paper_size',
        'orientation',
        'margin_top',
        'margin_bottom',
        'margin_left',
        'margin_right',

        // Footer
        'show_signature',
        'show_school_stamp',
        'city',
        'signer_name',
        'signer_position',
        'signer_nip',
        'signature_file',
        'stamp_file',

        // Extra
        'show_print_date',
        'show_page_number',
        'show_qrcode',
    ];

    protected $casts = [
        'show_logo'         => 'boolean',
        'show_school_name'  => 'boolean',
        'show_address'      => 'boolean',
        'show_email'        => 'boolean',
        'show_phone'        => 'boolean',
        'show_header_line'  => 'boolean',
        'show_signature'    => 'boolean',
        'show_school_stamp' => 'boolean',
        'show_print_date'   => 'boolean',
        'show_page_number'  => 'boolean',
        'show_qrcode'       => 'boolean',
        'font_size'         => 'integer',
        'margin_top'        => 'integer',
        'margin_bottom'     => 'integer',
        'margin_left'       => 'integer',
        'margin_right'      => 'integer',
    ];

    /**
     * Pengaturan laporan bersifat singleton (hanya 1 baris pengaturan aktif).
     */
    public static function current(): self
    {
        return static::first() ?? static::create([
            'signer_name' => 'Nama Kepala Sekolah',
        ]);
    }

    public function getSignatureUrlAttribute(): ?string
    {
        return $this->signature_file ? asset('storage/'.$this->signature_file) : null;
    }

    public function getStampUrlAttribute(): ?string
    {
        return $this->stamp_file ? asset('storage/'.$this->stamp_file) : null;
    }
}