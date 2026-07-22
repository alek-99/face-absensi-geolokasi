<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KonfigurasiAbsen;
use Illuminate\Http\Request;

class KonfigurasiAbsenController extends Controller
{
    /**
     * Menampilkan halaman konfigurasi absensi.
     */
    public function index()
    {
        $konfigurasi = KonfigurasiAbsen::first();

        // Jika belum ada konfigurasi, buat default
        if (!$konfigurasi) {
            $konfigurasi = KonfigurasiAbsen::create([
                'buka_absen_masuk_menit'     => 30,
                'toleransi_terlambat_menit'  => 15,
                'buka_absen_pulang_menit'    => 30,
            ]);
        }

        return view('admin.pengaturan.absensi', compact('konfigurasi'));
    }

    /**
     * Update konfigurasi.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'buka_absen_masuk_menit' => [
                'required',
                'integer',
                'min:0',
                'max:180'
            ],

            'toleransi_terlambat_menit' => [
                'required',
                'integer',
                'min:0',
                'max:180'
            ],

            'buka_absen_pulang_menit' => [
                'required',
                'integer',
                'min:0',
                'max:180'
            ],
        ]);

        $konfigurasi = KonfigurasiAbsen::first();

        if (!$konfigurasi) {
            KonfigurasiAbsen::create($validated);
        } else {
            $konfigurasi->update($validated);
        }

        return redirect()
            ->back()
            ->with('success', 'Konfigurasi absensi berhasil diperbarui.');
    }
}