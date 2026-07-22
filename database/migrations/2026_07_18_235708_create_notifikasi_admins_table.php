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
        Schema::create('notifikasi_admins', function (Blueprint $table) {
            $table->id();
             $table->foreignId('absensi_id')->constrained('absensis')->cascadeOnDelete();
            $table->string('tipe')->default('pengajuan_izin');
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
        Schema::dropIfExists('notifikasi_admins');
    }
};
