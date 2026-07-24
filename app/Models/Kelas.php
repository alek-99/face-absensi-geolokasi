<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    // Menentukan nama tabel secara eksplisit
    protected $table = 'kelas';

    protected $fillable = [
        'nama_kelas',
        'wali_kelas', // Merupakan foreign key ke tabel gurus (id)
        'jumlah_siswa',
        'ruangan',
        'catatan',
    ];

    /**
     * Relasi ke Model Guru (Wali Kelas)
     */
    public function waliKelas()
    {
        return $this->belongsTo(Guru::class, 'wali_kelas');
    }

    /**
     * Relasi ke Model Siswa (1 Kelas punya banyak Siswa)
     */
    public function siswas()
    {
        return $this->hasMany(Siswa::class, 'kelas_id');
    }

    public function jadwal(){
        return $this->hasOne(Jadwal::class);
    }
    /**
     * Boot function untuk auto-update jumlah_siswa pada tabel Kelas
     */
    protected static function booted()
    {
        // Saat siswa baru dibuat
        static::created(function ($siswa) {
            if ($siswa->kelas_id) {
                $siswa->kelas()->increment('jumlah_siswa');
            }
        });

        // Saat siswa dihapus
        static::deleted(function ($siswa) {
            if ($siswa->kelas_id) {
                $siswa->kelas()->decrement('jumlah_siswa');
            }
        });

        // Saat kelas_id siswa diubah (pindah kelas)
        static::updated(function ($siswa) {
            if ($siswa->isDirty('kelas_id')) {
                $oldKelasId = $siswa->getOriginal('kelas_id');
                $newKelasId = $siswa->kelas_id;

                if ($oldKelasId) {
                    Kelas::where('id', $oldKelasId)->decrement('jumlah_siswa');
                }
                if ($newKelasId) {
                    Kelas::where('id', $newKelasId)->increment('jumlah_siswa');
                }
            }
        });
    }
}