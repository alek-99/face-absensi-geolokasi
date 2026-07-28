<?php

namespace App\Exceptions;

use Exception;

/**
 * Dilempar untuk semua kegagalan alur bisnis absen siswa (jadwal bukan milik guru,
 * siswa di luar kelas, tanggal tidak valid, dsb) supaya controller cukup punya satu
 * blok catch dan tidak perlu membedakan setiap kondisi dengan if/else berlapis.
 *
 * Gunakan parameter kedua untuk menentukan status HTTP (default 422). Contoh:
 *   throw new AbsenSiswaException('Jadwal tidak ditemukan.', 404);
 *   throw new AbsenSiswaException('Jadwal ini bukan milik Anda.', 403);
 */
class AbsenSiswaException extends Exception
{
    public function __construct(string $message, protected int $status = 422)
    {
        parent::__construct($message);
    }

    public function status(): int
    {
        return $this->status;
    }
}