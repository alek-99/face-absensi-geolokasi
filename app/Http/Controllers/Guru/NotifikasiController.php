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
        // Mendapatkan ID guru dari user yang sedang login
        $guruId = Auth::user()->guru->id;

        $notifikasis = NotifikasiGuru::where('guru_id', $guruId)
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
    $user = Auth::user();

    // 1. Pastikan user punya relasi guru dan ID-nya cocok
    // Gunakan ?-> (nullsafe operator) agar aman jika $user->guru bernilai null
    $guruId = $user->guru?->id;

    if (!$guruId || (string) $notifikasi->guru_id !== (string) $guruId) {
        abort(403, 'Aksi tidak sah.');
    }

    // 2. Tandai sudah dibaca jika belum
    if (is_null($notifikasi->read_at)) {
        $notifikasi->update(['read_at' => now()]);
    }

    return redirect('/guru/riwayat-absen')->with('info', $notifikasi->pesan);
}

    /**
     * Tandai semua notifikasi guru sebagai dibaca sekaligus
     */
    public function readAll()
    {
        $guruId = Auth::user()->guru->id;

        NotifikasiGuru::where('guru_id', $guruId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return back()->with('success', 'Semua notifikasi berhasil ditandai sebagai dibaca.');
    }
}