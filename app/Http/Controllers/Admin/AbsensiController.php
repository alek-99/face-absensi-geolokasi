<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Guru;
use App\Models\NotifikasiGuru;
use App\Models\ReportSetting;
use App\Models\Sekolah;
use App\Services\FonnteService; // Panggil Service Fonnte
use App\Services\PushNotificationService; // Panggil Service PWA
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {

        $query = Absensi::with('guru.user')
            ->whereNotNull('status_persetujuan')
            ->latest('tanggal');

        if ($request->filled('status')) {
            $query->where('status_persetujuan', $request->status);
        }

        $pengajuan = $query->paginate(15);

        return view('admin.absensi.pengajuan', compact('pengajuan'));
    }

    public function dataAbsen(Request $request)
    {
        $query = Absensi::with('guru.user')
            ->orderByDesc('tanggal');

        // Filter Guru
        if ($request->filled('guru_id')) {
            $query->where('guru_id', $request->guru_id);
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter Approval
        if ($request->filled('approval')) {
            $query->where('status_persetujuan', $request->approval);
        }

        // Filter Tanggal Mulai
        if ($request->filled('tanggal_awal')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_awal);
        }

        // Filter Tanggal Akhir
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_akhir);
        }

        $riwayat = $query->paginate(20)->withQueryString();

        $gurus = Guru::orderBy('nama')->get();

        return view('admin.absensi.data-absen', compact(
            'riwayat',
            'gurus'
        ));
    }
    /**
     * Detail pengajuan
     */
    public function show(Absensi $absensi)
    {
        $absensi->load('guru.user');

        return response()->json($absensi);
    }


    /**
     * Approve
     */
    public function approve(Absensi $absensi)
    {
        if ($absensi->status_persetujuan != 'pending') {
            return back()->with('error', 'Pengajuan sudah diproses.');
        }

        $absensi->update([
            'status_persetujuan' => 'disetujui',
            'approved_by' => Auth::id(),
            'approved_at' => now()
        ]);

        // ================= START: KIRIM NOTIFIKASI =================
        $absensi->load(['guru.user', 'guru.device']); // Sekaligus load relasi device agar efisien
        $user = $absensi->guru->user;
        $guru = $absensi->guru; // Ambil object guru untuk akses no_hp
        $tanggalPilihan = \Carbon\Carbon::parse($absensi->tanggal)->translatedFormat('d F Y');
        $statusIzin = strtoupper($absensi->status);

        // 0. Kirim Notifikasi Internal ke Website Guru
        NotifikasiGuru::create([
            'guru_id' => $absensi->guru_id,
            'absensi_id' => $absensi->id,
            'tipe' => 'persetujuan_izin',
            'status_persetujuan' => 'disetujui',
            'pesan' => "Pengajuan izin Anda untuk tanggal {$tanggalPilihan} ({$statusIzin}) telah DISETUJUI oleh Admin."
        ]);

        // 1. Kirim Notifikasi WA (Fonnte)
        // Perbaikan: mengambil no_hp langsung dari tabel guru
        if ($guru && !empty($guru->no_hp)) {
            $namaGuru = $user ? $user->name : ($guru->nama ?? 'Guru');
            $pesanWa = "Halo *{$namaGuru}*,\n\nPengajuan izin/absen Anda untuk tanggal *{$tanggalPilihan}* (Status: {$statusIzin}) telah *DISETUJUI* oleh Admin.\n\nTerima kasih.";
            FonnteService::sendMessage($guru->no_hp, $pesanWa);
        }

        // 2. Kirim Notifikasi PWA (Chrome / Taskbar HP)
        $titlePwa = "Pengajuan Izin Disetujui! ✅";
        $bodyPwa = "Izin Anda pada tanggal {$tanggalPilihan} telah disetujui oleh admin.";
        $urlPwa = url('/guru/riwayat-absen');

        if ($absensi->guru && isset($absensi->guru->device) && count($absensi->guru->device) > 0) {
            foreach ($absensi->guru->device as $device) {
                if ($device->push_subscription) {
                    PushNotificationService::sendToUser($device->push_subscription, $titlePwa, $bodyPwa, $urlPwa);
                }
                if ($device->fcm_token) {
                    PushNotificationService::sendToUser($device->fcm_token, $titlePwa, $bodyPwa, $urlPwa);
                }
            }
        } else {
            if ($user && isset($user->fcm_token)) {
                PushNotificationService::sendToUser($user->fcm_token, $titlePwa, $bodyPwa, $urlPwa);
            }
        }
        // ================= END: KIRIM NOTIFIKASI =================

        return back()->with('success', 'Pengajuan berhasil disetujui.');
    }

    /**
     * Reject
     */
    public function reject(Request $request, Absensi $absensi)
    {
        if ($absensi->status_persetujuan != 'pending') {
            return back()->with('error', 'Pengajuan sudah diproses.');
        }

        $request->validate([
            'catatan_admin' => 'nullable|string|max:1000'
        ]);

        $keterangan = $absensi->keterangan;
        if ($request->filled('catatan_admin')) {
            $keterangan .= "\n\nCatatan Admin : " . $request->catatan_admin;
        }

        $absensi->update([
            'status_persetujuan' => 'ditolak',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'keterangan' => $keterangan
        ]);

        // ================= START: KIRIM NOTIFIKASI =================
        $absensi->load(['guru.user', 'guru.device']); 
        $user = $absensi->guru->user;
        $guru = $absensi->guru; // Ambil object guru untuk akses no_hp
        $tanggalPilihan = \Carbon\Carbon::parse($absensi->tanggal)->translatedFormat('d F Y');

        // 0. Kirim Notifikasi Internal ke Website Guru
        $pesanDatabase = "Pengajuan izin Anda untuk tanggal {$tanggalPilihan} telah DITOLAK oleh Admin.";
        if ($request->filled('catatan_admin')) {
            $pesanDatabase .= " Alasan penolakan: \"{$request->catatan_admin}\"";
        }

        NotifikasiGuru::create([
            'guru_id' => $absensi->guru_id,
            'absensi_id' => $absensi->id,
            'tipe' => 'persetujuan_izin',
            'status_persetujuan' => 'ditolak',
            'pesan' => $pesanDatabase
        ]);

        // 1. Kirim Notifikasi WA (Fonnte)
        // Perbaikan: mengambil no_hp langsung dari tabel guru
        if ($guru && !empty($guru->no_hp)) {
            $namaGuru = $user ? $user->name : ($guru->nama ?? 'Guru');
            $pesanWa = "Halo *{$namaGuru}*,\n\nMohon maaf, pengajuan izin Anda untuk tanggal *{$tanggalPilihan}* telah *DITOLAK* oleh Admin.";
            if ($request->filled('catatan_admin')) {
                $pesanWa .= "\nAlasan penolakan: _\"{$request->catatan_admin}\"_";
            }
            $pesanWa .= "\n\nSilakan hubungi admin atau ajukan kembali jika ada kekeliruan.";
            FonnteService::sendMessage($guru->no_hp, $pesanWa);
        }

        // 2. Kirim Notifikasi PWA (Taskbar HP)
        $titlePwa = "Pengajuan Izin Ditolak ❌";
        $bodyPwa = "Pengajuan izin Anda pada tanggal {$tanggalPilihan} ditolak oleh admin.";
        $urlPwa = url('/guru/riwayat-absen');

        if ($absensi->guru && isset($absensi->guru->device) && count($absensi->guru->device) > 0) {
            foreach ($absensi->guru->device as $device) {
                if ($device->push_subscription) {
                    PushNotificationService::sendToUser($device->push_subscription, $titlePwa, $bodyPwa, $urlPwa);
                }
                if ($device->fcm_token) {
                    PushNotificationService::sendToUser($device->fcm_token, $titlePwa, $bodyPwa, $urlPwa);
                }
            }
        } else {
            if ($user && isset($user->fcm_token)) {
                PushNotificationService::sendToUser($user->fcm_token, $titlePwa, $bodyPwa, $urlPwa);
            }
        }
        // ================= END: KIRIM NOTIFIKASI =================

        return back()->with('success', 'Pengajuan berhasil ditolak.');
    }
}