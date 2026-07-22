<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotifikasiGuru extends Model
{
    use HasFactory;

    protected $table = 'notifikasi_gurus';

    protected $fillable = [
        'guru_id',
        'absensi_id',
        'tipe',
        'status_persetujuan',
        'pesan',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    /**
     * Relasi ke Guru
     */
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    /**
     * Relasi ke Absensi
     */
    public function absensi()
    {
        return $this->belongsTo(Absensi::class, 'absensi_id');
    }
}