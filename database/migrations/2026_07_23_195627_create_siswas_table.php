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
         Schema::create('siswas', function (Blueprint $table) {
            $table->id();
            // DATA DIRI
            $table->string('nama');
            $table->string('nisn')->unique();
            $table->string('nik');
            $table->string('nipd')->unique();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->string('alamat');
            $table->string('no_hp');
            $table->enum('jenis_kelamin',['perempuan','laki-laki'])->default('laki-laki');
             $table->string('tempat_lahir')->nullable();
             $table->date('tanggal_lahir');
             $table->string('agama')->nullable();
             $table->string('sekolah_asal')->nullable();
            //  DATA ORTU
            $table->string('nama_ayah')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->string('pekerjaan_ayah')->nullable();
            $table->string('pekerjaan_ibu')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};
