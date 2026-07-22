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
        Schema::create('pdf_settings', function (Blueprint $table) {
            $table->id();
            $table->string('judul_instansi');
    $table->string('sub_judul')->nullable();

    $table->string('alamat');
    $table->string('email')->nullable();
    $table->string('telepon')->nullable();
    $table->string('website')->nullable();

    $table->string('logo')->nullable();

    $table->string('nama_kepala_sekolah');
    $table->string('nip_kepala_sekolah')->nullable();

    $table->string('jabatan_penandatangan')
          ->default('Kepala Sekolah');

    $table->string('ttd')->nullable();

    $table->string('stempel')->nullable();

    $table->string('tempat_ttd')
          ->default('Serang');

    $table->boolean('tampilkan_logo')->default(true);
    $table->boolean('tampilkan_email')->default(true);
    $table->boolean('tampilkan_website')->default(true);
    $table->boolean('tampilkan_telepon')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pdf_settings');
    }
};
