<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $table = 'jadwals';
    protected $fillable = [
        'guru_id',
        'hari',
        'jam_masuk',
        'jam_pulang',
        'mata_pelajaran',
        'kelas',
        'ruangan',
        'is_active'
    ];

 protected $casts = [
    'jam_masuk' => 'datetime:H:i:s',
    'jam_pulang' => 'datetime:H:i:s',
    'is_active' => 'boolean',
];

    

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
}