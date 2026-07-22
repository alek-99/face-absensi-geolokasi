<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sekolah extends Model
{
    use HasFactory;

    protected $table = 'sekolahs';

    protected $fillable = [
        'nama_sekolah',
        'alamat',
        'latitude',
        'longitude',
        'radius',
        'foto_sekolah',
        'logo_sekolah',
        'no_telp',
        'email',
        'jam_masuk',
        'jam_keluar',
    ];

    protected $casts = [
        'latitude'  => 'decimal:7',
        'longitude' => 'decimal:7',
        'radius'    => 'integer',
    ];

    /**
     * Data sekolah bersifat singleton (hanya 1 baris).
     * Selalu gunakan helper ini agar tidak perlu cek null di controller/view.
     */
    public static function current(): self
    {
        return static::first() ?? static::create([
            'nama_sekolah' => 'Nama Sekolah',
            'alamat'       => '-',
            'latitude'     => 0,
            'longitude'    => 0,
            'no_telp'      => '-',
            'email'        => '-',
            'jam_masuk'    => '07:00:00',
            'jam_keluar'   => '15:00:00',
        ]);
    }

   
}