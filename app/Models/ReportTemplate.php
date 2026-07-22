<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportTemplate extends Model
{
    use HasFactory;

   protected $fillable = [

    'kode',
    'slug',
    'nama_laporan',
    'judul_laporan',
    'subjudul',
    'deskripsi',
    'is_active',

];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Ambil template berdasarkan kode unik, mis: 'JADWAL_GURU', 'ABSENSI_GURU'
     */
    public static function findBySlug(string $slug): ?self
{
    return static::active()
        ->where('slug',$slug)
        ->first();
}
}