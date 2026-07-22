<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FaceDescriptor extends Model
{
    protected $table = 'face_descriptors';
    protected $fillable = [
        'guru_id',
        'descriptor',
        'algorithm',
        'version',
    ];

    protected $casts = [
        // Sangat krusial! Menjadikan array koordinat wajah (JSON) otomatis 
        // menjadi Array PHP saat dipanggil, dan menjadi JSON saat disimpan.
        'descriptor' => 'array', 
    ];

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class);
    }
}