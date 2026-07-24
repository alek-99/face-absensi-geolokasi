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
        'mapel_id',
        'kelas_id',
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

    public function mapel(){
        return $this->belongsTo(Mapel::class);
    }
    public function kelas(){
        return $this->belongsTo(Kelas::class);
    }
}