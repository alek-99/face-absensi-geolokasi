<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KonfigurasiAbsen extends Model
{
    protected $table = 'konfigurasi_absens';

    protected $fillable = [
        'buka_absen_masuk_menit',
        'toleransi_terlambat_menit',
        'buka_absen_pulang_menit',
    ];

    protected $casts = [
        'buka_absen_masuk_menit' => 'integer',
        'toleransi_terlambat_menit' => 'integer',
        'buka_absen_pulang_menit' => 'integer',
    ];
}