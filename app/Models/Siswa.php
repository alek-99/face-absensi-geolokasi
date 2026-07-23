<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswas';

    protected $fillable = [
        'nama',
        'nisn',
        'nik',
        'nipd',
        'kelas_id',
        'alamat',
        'no_hp',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'sekolah_asal',
        'nama_ayah',
        'nama_ibu',
        'pekerjaan_ayah',
        'pekerjaan_ibu',
    ];

    /**
     * Konversi tipe data otomatis untuk tanggal lahir
     */
    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    /**
     * Relasi ke Model Kelas (Setiap siswa milik 1 Kelas)
     */
    public function kelas()
    {
     
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
}