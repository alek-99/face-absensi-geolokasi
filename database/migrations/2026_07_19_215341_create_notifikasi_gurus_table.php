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
       Schema::create('notifikasi_gurus', function (Blueprint $table) {
    $table->id();
    $table->foreignId('guru_id')->constrained('gurus')->cascadeOnDelete();
    $table->foreignId('absensi_id')->constrained('absensis')->cascadeOnDelete();
    $table->string('tipe')->default('persetujuan_izin'); // persetujuan_izin
    $table->string('status_persetujuan'); // disetujui / ditolak
    $table->text('pesan');
    $table->timestamp('read_at')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasi_gurus');
    }
};
