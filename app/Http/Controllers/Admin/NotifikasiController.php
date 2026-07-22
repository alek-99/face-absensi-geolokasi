<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotifikasiAdmin;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    /**
     * GET /admin/notifikasi
     * List notifikasi terbaru (dipakai buat isi dropdown lonceng).
     */
    public function index(): JsonResponse
    {
        $notifikasi = NotifikasiAdmin::with('absensi.guru')
            ->latest()
            ->limit(20)
            ->get();

        return response()->json([
            'data' => $notifikasi,
            'unread_count' => NotifikasiAdmin::belumDibaca()->count(),
        ]);
    }

    /**
     * GET /admin/notifikasi/unread-count
     * Dipanggil ringan buat update badge tanpa narik semua list.
     */
    public function unreadCount(): JsonResponse
    {
        return response()->json([
            'unread_count' => NotifikasiAdmin::belumDibaca()->count(),
        ]);
    }

    /**
     * GET /admin/notifikasi/halaman
     */
    public function halaman()
    {
        $notifikasi = NotifikasiAdmin::with('absensi.guru')
            ->latest()
            ->paginate(20);

        return view('admin.notifikasi.index', [
            'notifikasi' => $notifikasi,
            'unread_count' => NotifikasiAdmin::belumDibaca()->count(),
        ]);
    }

    /**
     * PATCH /admin/notifikasi/{notifikasi}/baca
     */
    public function markAsRead(Request $request, NotifikasiAdmin $notifikasi)
    {
        $notifikasi->update(['read_at' => now()]);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Notifikasi ditandai sudah dibaca.']);
        }

        return redirect()->back()->with('success', 'Notifikasi ditandai sudah dibaca.');
    }

    /**
     * PATCH /admin/notifikasi/baca-semua
     */
    public function markAllAsRead(Request $request)
    {
        NotifikasiAdmin::belumDibaca()->update(['read_at' => now()]);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Semua notifikasi ditandai sudah dibaca.']);
        }

        return redirect()->back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }

    public function bulkDelete(Request $request, NotifikasiAdmin $notifikasi){
        
    }
}