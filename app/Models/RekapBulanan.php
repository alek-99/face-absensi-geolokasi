<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekapBulanan extends Model
{
    protected $table = 'rekap_bulanans';

    protected $fillable = [
        'guru_id',
        'bulan',
        'tahun',
        'jumlah_hadir',
        'jumlah_izin',
        'jumlah_sakit',
        'jumlah_tidak_hadir',
    ];

    protected $casts = [
        'bulan' => 'integer',
        'tahun' => 'integer',
        'jumlah_hadir' => 'integer',
        'jumlah_izin' => 'integer',
        'jumlah_sakit' => 'integer',
        'jumlah_tidak_hadir' => 'integer',
    ];
    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
}
