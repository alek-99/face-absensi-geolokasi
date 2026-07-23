<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Guru extends Model
{
    use HasFactory;

    protected $table = 'gurus';

    protected $fillable = [
        'user_id',
        'foto_profil',
        'nip',
        'jenis_kelamin',
        'nama',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'no_hp',
        'is_active',
    ];

    /**
     * Relasi ke User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function absensiHariIni()
{
    return $this->hasOne(Absensi::class)
                ->whereDate('tanggal', today());
}

    /**
     * Relasi ke Jadwal
     */
    public function jadwals(): HasMany
    {
        return $this->hasMany(Jadwal::class);
    }

    /**
     * Relasi ke Rekap Bulanan
     */
    public function rekapBulanans(): HasMany
    {
        return $this->hasMany(RekapBulanan::class);
    }

    /**
     * Relasi ke Absensi
     */
    public function absensis(): HasMany
    {
        return $this->hasMany(Absensi::class);
    }

    /**
     * Relasi ke Face Descriptor
     */
    public function faceDescriptor(): HasOne
    {
        return $this->hasOne(FaceDescriptor::class);
    }

    /**
     * Relasi ke Device Guru
     */
    public function device(): HasMany
    {
        return $this->hasMany(DeviceGuru::class);
    }

    /**
     * Relasi ke Pengajuan Cuti
     */
    public function pengajuans(): HasMany
    {
        return $this->hasMany(Cuti::class);
    }

    public function kelas(){
        return $this->hasOne(Kelas::class);
    }
}