<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    
    public static function normalisasiNomor(?string $nomor): ?string
    {
        if (empty($nomor)) {
            return null;
        }

        // Buang semua karakter selain digit (spasi, +, -, dsb.)
        $nomor = preg_replace('/\D/', '', $nomor);

        if (empty($nomor)) {
            return null;
        }

        // 0812xxxx -> 62812xxxx
        if (str_starts_with($nomor, '0')) {
            $nomor = '62' . substr($nomor, 1);
        }

        // 812xxxx (tanpa awalan sama sekali) -> 62812xxxx
        if (!str_starts_with($nomor, '62')) {
            $nomor = '62' . $nomor;
        }

        return $nomor;
    }

    /**
     * Kirim pesan WhatsApp menggunakan Fonnte API.
     */
    public static function sendMessage($target, $message)
    {
        $token = env('FONNTE_TOKEN'); 

        if (!$token) {
            Log::error('Fonnte Token tidak ditemukan di .env');
            return false;
        }

        $target = self::normalisasiNomor($target);

        if (!$target) {
            Log::error('Fonnte Gagal Kirim: nomor tujuan kosong/tidak valid.');
            return false;
        }

        try {
            Log::info('Fonnte sendMessage payload', [
                'target' => $target,
            ]);

            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->post('https://api.fonnte.com/send', [
                'target'  => $target,
                'message' => $message,
               
            ]);


            $result = $response->json();

         
            if (!$response->successful()) {
                Log::error('Fonnte HTTP Error: status=' . $response->status() . ' body=' . $response->body());
                return false;
            }

            if (isset($result['status']) && $result['status'] == true) {
                return true;
            }

            Log::error('Fonnte Gagal Kirim: ' . json_encode($result));
            return false;
        } catch (\Exception $e) {
            Log::error('Fonnte Exception Error: ' . $e->getMessage());
            return false;
        }
    }
}