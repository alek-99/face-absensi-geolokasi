<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambahkan indeks pada tabel jadwals
        Schema::table('jadwals', function (Blueprint $table) {
            // Mempercepat query: Where guru_id = X AND hari = Y AND is_active = 1
            $table->index(['guru_id', 'hari', 'is_active']);
        });

        // 2. Tambahkan indeks pada tabel absensis
        Schema::table('absensis', function (Blueprint $table) {
            // Mempercepat query pencarian riwayat absensi harian & bulanan
            $table->index(['guru_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::table('jadwals', function (Blueprint $table) {
            $table->dropIndex(['guru_id', 'hari', 'is_active']);
        });

        Schema::table('absensis', function (Blueprint $table) {
            $table->dropIndex(['guru_id', 'tanggal']);
        });
    }
};