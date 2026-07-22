<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Cuti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CutiController extends Controller
{
    /**
     * Daftar pengajuan guru
     */
    public function index()
    {
        $guru = Auth::user()->guru;

        $cutis = Cuti::where('guru_id', $guru->id)
            ->latest()
            ->paginate(10);

        return view('guru.cuti.index', compact('cutis'));
    }

    /**
     * Form tambah pengajuan
     */
    public function create()
    {
        return view('guru.cuti.create');
    }

    /**
     * Simpan pengajuan
     */
    public function store(Request $request)
    {
        $request->validate([
            'jenis' => 'required|in:izin,sakit,cuti,tugas_luar',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alasan' => 'required|string|max:1000',
            'bukti_dokumen' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $guru = Auth::user()->guru;

        $path = null;

        if ($request->hasFile('bukti_dokumen')) {
            $path = $request->file('bukti_dokumen')
                ->store('cuti', 'public');
        }

        Cuti::create([
            'guru_id' => $guru->id,
            'jenis' => $request->jenis,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'alasan' => $request->alasan,
            'bukti_dokumen' => $path,
            'status_persetujuan' => 'pending'
        ]);

        return redirect()
            ->route('guru.cuti.index')
            ->with('success', 'Pengajuan berhasil dikirim.');
    }

    /**
     * Detail pengajuan
     */
    public function show(Cuti $cuti)
    {
        abort_if($cuti->guru_id != Auth::user()->guru->id, 403);

        return view('guru.cuti.show', compact('cuti'));
    }

    /**
     * Form edit
     */
    public function edit(Cuti $cuti)
    {
        abort_if($cuti->guru_id != Auth::user()->guru->id, 403);

        if ($cuti->status_persetujuan != 'pending') {
            return back()->with('error', 'Pengajuan yang sudah diproses tidak dapat diubah.');
        }

        return view('guru.cuti.edit', compact('cuti'));
    }

    /**
     * Update pengajuan
     */
    public function update(Request $request, Cuti $cuti)
    {
        abort_if($cuti->guru_id != Auth::user()->guru->id, 403);

        if ($cuti->status_persetujuan != 'pending') {
            return back()->with('error', 'Pengajuan sudah diproses.');
        }

        $request->validate([
            'jenis' => 'required|in:izin,sakit,cuti,tugas_luar',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alasan' => 'required|string|max:1000',
            'bukti_dokumen' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('bukti_dokumen')) {

            if ($cuti->bukti_dokumen) {
                Storage::disk('public')->delete($cuti->bukti_dokumen);
            }

            $cuti->bukti_dokumen = $request->file('bukti_dokumen')
                ->store('cuti', 'public');
        }

        $cuti->jenis = $request->jenis;
        $cuti->tanggal_mulai = $request->tanggal_mulai;
        $cuti->tanggal_selesai = $request->tanggal_selesai;
        $cuti->alasan = $request->alasan;

        $cuti->save();

        return redirect()
            ->route('guru.cuti.index')
            ->with('success', 'Pengajuan berhasil diperbarui.');
    }

    /**
     * Hapus pengajuan
     */
    public function destroy(Cuti $cuti)
    {
        abort_if($cuti->guru_id != Auth::user()->guru->id, 403);

        if ($cuti->status_persetujuan != 'pending') {
            return back()->with('error', 'Pengajuan yang sudah diproses tidak dapat dihapus.');
        }

        if ($cuti->bukti_dokumen) {
            Storage::disk('public')->delete($cuti->bukti_dokumen);
        }

        $cuti->delete();

        return back()->with('success', 'Pengajuan berhasil dihapus.');
    }
}
