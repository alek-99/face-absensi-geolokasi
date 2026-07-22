<?php

namespace App\Exceptions;

use Exception;

/**
 * Exception khusus untuk error validasi proses absensi
 * (jadwal, waktu, lokasi, wajah, dsb).
 *
 * Dilempar oleh AbsensiService dan ditangkap oleh AbsensiController
 * agar pesan errornya rapi dikirim sebagai response JSON (422),
 * tanpa perlu try-catch generic Exception di controller.
 */
class AbsensiException extends Exception
{
    //
}