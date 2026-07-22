<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeviceGuru extends Model
{
    protected $table = 'device_gurus';

    protected $fillable = [
        'guru_id',
        'device_id',
        'nama_perangkat',
        'fcm_token',
        'push_subscription', // JSON payload subscription (endpoint + keys)
    ];

    protected $casts = [
        'push_subscription' => 'array',
    ];

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class);
    }
}