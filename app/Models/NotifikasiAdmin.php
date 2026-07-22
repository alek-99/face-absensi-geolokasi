<?php

namespace App\Models;

use App\Models\Absensi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotifikasiAdmin extends Model
{
    protected $table = 'notifikasi_admins';

    protected $fillable = [
        'absensi_id',
        'tipe',
        'pesan',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function absensi(): BelongsTo
    {
        return $this->belongsTo(Absensi::class);
    }

    public function scopeBelumDibaca($query)
    {
        return $query->whereNull('read_at');
    }
}
