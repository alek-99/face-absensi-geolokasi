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
        Schema::create('device_gurus', function (Blueprint $table) {
            $table->id();
        
            $table->foreignId('guru_id')->constrained('gurus')->cascadeOnDelete();
    $table->string('device_id')->unique(); // UUID perangkat keras HP
    $table->string('os_version')->nullable(); // Android/iOS version
    $table->string('fcm_token')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_gurus');
    }
};
