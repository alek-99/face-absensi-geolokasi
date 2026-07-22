<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absensi extends Model
{
    protected $table = 'absensis';

    protected $fillable = [
        'guru_id',
        'tanggal',
        'jam_masuk',
        'latitude_masuk',
        'longitude_masuk',
        'foto_masuk',
        'confidence_masuk',
        'jam_pulang',
        'latitude_pulang',
        'longitude_pulang',
        'foto_pulang',
        'confidence_pulang',
        'status',
        'keterangan',
        'bukti_dokumen',
        'status_persetujuan',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jam_masuk' => 'datetime:H:i:s',
        'jam_pulang' => 'datetime:H:i:s',
        'latitude_masuk' => 'decimal:7',
        'longitude_masuk' => 'decimal:7',
        'latitude_pulang' => 'decimal:7',
        'longitude_pulang' => 'decimal:7',
        'confidence_masuk' => 'float',
        'confidence_pulang' => 'float',
    ];

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class);
    }

    /**
     * Scope untuk mempermudah query absensi hari ini di Controller.
     * Contoh penggunaan: Absensi::hariIni()->get();
     */
    public function scopeHariIni($query)
    {
        return $query->where('tanggal', today());
    }
    public function notifikasi(){
        return $this->hasOne(NotifikasiAdmin::class);
    }
}