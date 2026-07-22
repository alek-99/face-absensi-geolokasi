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
        Schema::create('rekap_bulanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->constrained('gurus')->cascadeOnDelete();
    $table->integer('bulan'); // 1 - 12
    $table->integer('tahun');
    $table->integer('hadir')->default(0);
    $table->integer('terlambat')->default(0);
    $table->integer('izin')->default(0);
    $table->integer('sakit')->default(0);
    $table->integer('cuti')->default(0);
    $table->integer('alpha')->default(0);
    $table->timestamps();
    
    $table->unique(['guru_id', 'bulan', 'tahun']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekap_bulanans');
    }
};
