<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('konfigurasi_absens', function (Blueprint $table) {
            $table->id();
            $table->integer('buka_absen_masuk_menit')->default(45); 
    // Berapa menit setelah jam_masuk sekolah, guru dianggap terlambat
    $table->integer('toleransi_terlambat_menit')->default(15); 
    // Berapa menit sebelum jam_keluar sekolah, guru sudah diizinkan absen pulang
    $table->integer('buka_absen_pulang_menit')->default(15);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konfigurasi_absens');
    }
};
