<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsenSiswa extends Model
{
    protected $table ='absensi_siswas';
  protected $fillable = [
        'jadwal_id',
        'siswa_id',
        'tanggal',
        'status',
        'keterangan',
    ];

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }

    /**
     * Relasi ke model Siswa.
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

}
