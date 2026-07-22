<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cuti extends Model
{
    protected $table = 'cutis';
    protected $fillable = [
        'guru_id',
        'jenis',
        'tanggal_mulai',
        'tanggal_selesai',
        'alasan',
        'bukti_dokumen',
        'status_persetujuan',
        'disetujui_oleh',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class);
    }

    /**
     * Admin yang menyetujui pengajuan ini.
     */
    public function verifikator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }
}