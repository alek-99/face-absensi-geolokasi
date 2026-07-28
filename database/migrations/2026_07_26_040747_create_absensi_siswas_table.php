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
        Schema::create('absensi_siswas', function (Blueprint $table) {
            $table->id();
            // Menghubungkan langsung ke jadwal (di mana di dalam jadwal sudah ada guru_id, mapel_id, dan kelas_id)
            $table->foreignId('jadwal_id')->constrained('jadwals')->cascadeOnDelete();
            // Menghubungkan ke siswa yang diabsen
            $table->foreignId('siswa_id')->constrained('siswas')->cascadeOnDelete();
            // Tanggal pelaksanaan absensi
            $table->date('tanggal');
            // Status kehadiran
            $table->enum('status', ['Hadir', 'Izin', 'Sakit', 'Alfa'])->default('Hadir');
            // Catatan tambahan jika siswa izin/sakit/alfa (opsional)
            $table->string('keterangan')->nullable();
            $table->timestamps();
            // Mencegah duplikasi data: 1 siswa hanya bisa diabsen 1 kali untuk 1 jadwal di tanggal yang sama
            $table->unique(['jadwal_id', 'siswa_id', 'tanggal'], 'absensi_unique_per_jadwal');
            // Index untuk mempercepat query laporan/rekap
            $table->index(['jadwal_id', 'tanggal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi_siswas');
    }
};
