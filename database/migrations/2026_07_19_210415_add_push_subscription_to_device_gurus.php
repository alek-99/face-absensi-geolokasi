<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan kolom yang dibutuhkan oleh model DeviceGuru
     * (nama_perangkat & push_subscription) tapi belum pernah dibuat
     * di migration awal. Inilah penyebab error 500 saat menyimpan
     * subscription Web Push: kolomnya belum ada di database.
     */
    public function up(): void
    {
        Schema::table('device_gurus', function (Blueprint $table) {
            if (!Schema::hasColumn('device_gurus', 'nama_perangkat')) {
                $table->string('nama_perangkat')->nullable()->after('device_id');
            }

            if (!Schema::hasColumn('device_gurus', 'push_subscription')) {
                // Pakai JSON (bukan string/varchar) karena isinya object
                // besar (endpoint + keys.p256dh + keys.auth), bisa lebih
                // dari 255 karakter dan perlu bisa di-cast ke array.
                $table->json('push_subscription')->nullable()->after('fcm_token');
            }
        });
    }

    public function down(): void
    {
        Schema::table('device_gurus', function (Blueprint $table) {
            if (Schema::hasColumn('device_gurus', 'nama_perangkat')) {
                $table->dropColumn('nama_perangkat');
            }

            if (Schema::hasColumn('device_gurus', 'push_subscription')) {
                $table->dropColumn('push_subscription');
            }
        });
    }
};