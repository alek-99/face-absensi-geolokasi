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
        Schema::table('absensis', function (Blueprint $table) {

            // Dokumen pendukung (surat dokter, surat tugas, dll)
            $table->string('bukti_dokumen')->nullable()->after('keterangan');

            // Status approval pengajuan
            $table->enum('status_persetujuan', [
                'pending',
                'disetujui',
                'ditolak'
            ])->nullable()->after('bukti_dokumen');

            // Admin yang menyetujui
            $table->foreignId('approved_by')
                ->nullable()
                ->after('status_persetujuan')
                ->constrained('users')
                ->nullOnDelete();

            // Waktu approval
            $table->timestamp('approved_at')
                ->nullable()
                ->after('approved_by');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensis', function (Blueprint $table) {

            $table->dropForeign(['approved_by']);

            $table->dropColumn([
                'bukti_dokumen',
                'status_persetujuan',
                'approved_by',
                'approved_at'
            ]);

        });
    }
};