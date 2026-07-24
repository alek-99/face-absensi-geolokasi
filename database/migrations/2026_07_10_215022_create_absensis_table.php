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
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->constrained('gurus')->cascadeOnDelete();
            $table->date('tanggal');

            // Data Absen Masuk
            $table->time('jam_masuk')->nullable();
            $table->decimal('latitude_masuk', 10, 7)->nullable();
            $table->decimal('longitude_masuk', 10, 7)->nullable();
            $table->string('foto_masuk')->nullable();
            $table->float('confidence_masuk')->nullable(); // Skor kemiripan Face ID saat masuk

            // Data Absen Pulang
            $table->time('jam_pulang')->nullable();
            $table->decimal('latitude_pulang', 10, 7)->nullable();
            $table->decimal('longitude_pulang', 10, 7)->nullable();
            $table->string('foto_pulang')->nullable();
            $table->float('confidence_pulang')->nullable(); // Skor kemiripan Face ID saat pulang

            // Status Final Absensi Hari Ini
            $table->enum('status', ['hadir', 'terlambat', 'izin', 'sakit', 'cuti', 'tugas_luar', 'alpha'])->default('alpha');
            $table->text('keterangan')->nullable(); // Catatan tambahan (misal: alasan terlambat)
            $table->string('bukti_dokumen')->nullable();

            // Status approval pengajuan
            $table->enum('status_persetujuan', [
                'pending',
                'disetujui',
                'ditolak'
            ])->nullable();;

            // Admin yang menyetujui
            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Waktu approval
            $table->timestamp('approved_at')
                ->nullable();
            $table->timestamps();

            // ANTI-BUG: Menjamin 1 Guru HANYA bisa punya 1 baris data per 1 tanggal.
            $table->unique(['guru_id', 'tanggal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
