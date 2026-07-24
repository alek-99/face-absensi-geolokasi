<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    protected $table ='mapels';
    protected $fillable =[
        'nama_mapel',
        'catatan',
    ];
    public function guru(){
        return $this->hasOne(Guru::class);
    }

    public function jadwal(){
        return $this->hasOne(Jadwal::class);
    }
}
