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
        Schema::create('report_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('show_logo')->default(true);

            $table->boolean('show_school_name')->default(true);

            $table->boolean('show_address')->default(true);

            $table->boolean('show_email')->default(true);

            $table->boolean('show_phone')->default(true);

            $table->boolean('show_header_line')->default(true);

            $table->enum('header_line_style', [
                'single',
                'double'
            ])->default('double');

            /*
    |--------------------------------------------------------------------------
    | Body
    |--------------------------------------------------------------------------
    */

            $table->string('font_family')->default('Times New Roman');

            $table->integer('font_size')->default(11);

            $table->enum('paper_size', [
                'A4',
                'F4',
                'LETTER'
            ])->default('A4');

            $table->enum('orientation', [
                'portrait',
                'landscape'
            ])->default('portrait');

            $table->integer('margin_top')->default(15);

            $table->integer('margin_bottom')->default(15);

            $table->integer('margin_left')->default(15);

            $table->integer('margin_right')->default(15);

            /*
    |--------------------------------------------------------------------------
    | Footer
    |--------------------------------------------------------------------------
    */

            $table->boolean('show_signature')->default(true);

            $table->boolean('show_school_stamp')->default(true);

            $table->string('city')->default('Serang');

            $table->string('signer_name');

            $table->string('signer_position')
                ->default('Kepala Sekolah');

            $table->string('signer_nip')->nullable();

            $table->string('signature_file')->nullable();

            $table->string('stamp_file')->nullable();

            /*
    |--------------------------------------------------------------------------
    | Extra
    |--------------------------------------------------------------------------
    */

            $table->boolean('show_print_date')->default(true);

            $table->boolean('show_page_number')->default(true);

            $table->boolean('show_qrcode')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_settings');
    }
};
