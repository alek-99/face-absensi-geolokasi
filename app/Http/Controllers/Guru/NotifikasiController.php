<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\NotifikasiGuru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    /**
     * Tampilkan list notifikasi guru
     */
    public function index()
    {
        $guru = Auth::user()?->guru;

        // Cegah error jika user tidak memiliki profil guru
        if (!$guru) {
            abort(403, 'Akses ditolak. Profil guru tidak ditemukan.');
        }

        $notifikasis = NotifikasiGuru::where('guru_id', $guru->id)
            ->with('absensi')
            ->latest()
            ->paginate(15);

        return view('guru.notifikasi.index', compact('notifikasis'));
    }

    /**
     * Tandai satu notifikasi telah dibaca lalu arahkan ke halaman riwayat absen
     */
    public function read(NotifikasiGuru $notifikasi)
    {
        $guruId = Auth::user()?->guru?->id;

        // Otorisasi sederhana: Pastikan notifikasi milik guru yang sedang login
        if (!$guruId || (string) $notifikasi->guru_id !== (string) $guruId) {
            abort(403, 'Aksi tidak sah.');
        }

        // Tandai sudah dibaca jika belum
        if (is_null($notifikasi->read_at)) {
            $notifikasi->update(['read_at' => now()]);
        }

        return redirect()->route('guru.riwayat-absen.index') // Disarankan gunakan route name jika ada
            ->with('info', $notifikasi->pesan);
    }

    /**
     * Tandai semua notifikasi guru sebagai dibaca sekaligus
     */
    public function readAll()
    {
        $guruId = Auth::user()?->guru?->id;

        if (!$guruId) {
            abort(403, 'Akses ditolak.');
        }

        NotifikasiGuru::where('guru_id', $guruId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return back()->with('success', 'Semua notifikasi berhasil ditandai sebagai dibaca.');
    }
}