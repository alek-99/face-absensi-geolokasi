<?php

namespace App\Events;

use App\Models\Absensi;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

/**
 * Dibroadcast setiap kali guru mengajukan izin/sakit/cuti/tugas luar
 * (lihat AbsensiController::ajukanIzin). Admin yang sedang membuka
 * halaman akan menerima notifikasi ini secara realtime lewat Pusher,
 * tanpa perlu refresh halaman.
 *
 * Pakai ShouldBroadcastNow (bukan ShouldBroadcast) supaya tidak perlu
 * queue worker jalan untuk notifikasi ini terkirim. Kalau volume
 * pengajuan izin cukup tinggi dan tidak masalah delay beberapa detik,
 * silakan ganti ke ShouldBroadcast agar lewat queue.
 */
class PengajuanIzinEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Absensi $absensi)
    {
        $this->absensi->loadMissing('guru');
    }

    /**
     * Channel private khusus admin. Otorisasi diatur di routes/channels.php.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('admin-notifikasi'),
        ];
    }

    /**
     * Nama event yang didengarkan di JS: .pengajuan.izin.baru
     */
    public function broadcastAs(): string
    {
        return 'pengajuan.izin.baru';
    }

    public function broadcastWith(): array
    {
        return [
            'absensi_id' => $this->absensi->id,
            'guru_id' => $this->absensi->guru_id,
            'nama_guru' => $this->absensi->guru?->nama,
            'tanggal' => $this->absensi->tanggal,
            'status' => $this->absensi->status,
            'keterangan' => $this->absensi->keterangan,
            'bukti_dokumen_url' => $this->absensi->bukti_dokumen
                ? Storage::disk('public')->url($this->absensi->bukti_dokumen)
                : null,
            'created_at_human' => $this->absensi->created_at?->diffForHumans(),
        ];
    }
}