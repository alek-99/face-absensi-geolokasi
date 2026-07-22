<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Cuti;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CutiController extends Controller
{

    /**
     * Daftar seluruh pengajuan
     */
    public function index()
    {
        $cutis = Cuti::with('guru')
            ->latest()
            ->paginate(15);

        return view('admin.cuti.index', compact('cutis'));
    }

    /**
     * Detail
     */
    public function show(Cuti $cuti)
    {
        $cuti->load('guru');

        return view('admin.cuti.show', compact('cuti'));
    }

    /**
     * Setujui
     */
    public function approve(Cuti $cuti)
    {
        if ($cuti->status_persetujuan != 'pending') {

            return back()->with('error', 'Pengajuan sudah diproses.');

        }

        DB::transaction(function () use ($cuti) {

            $cuti->update([

                'status_persetujuan' => 'disetujui',

                'approved_by' => Auth::id()

            ]);

            $periode = CarbonPeriod::create(

                $cuti->tanggal_mulai,

                $cuti->tanggal_selesai

            );

            foreach ($periode as $tanggal) {

                Absensi::updateOrCreate(

                    [

                        'guru_id' => $cuti->guru_id,

                        'tanggal' => $tanggal->format('Y-m-d')

                    ],

                    [

                        'status' => $cuti->jenis,

                        'keterangan' => $cuti->alasan

                    ]

                );

            }

        });

        return back()->with('success', 'Pengajuan berhasil disetujui.');
    }

    /**
     * Tolak
     */
    public function reject(Request $request, Cuti $cuti)
    {

        if ($cuti->status_persetujuan != 'pending') {

            return back()->with('error', 'Pengajuan sudah diproses.');

        }

        $request->validate([

            'alasan_penolakan' => 'nullable|string|max:500'

        ]);

        $cuti->update([

            'status_persetujuan' => 'ditolak',

            'approved_by' => Auth::id(),

            'alasan' => $cuti->alasan .
                ($request->alasan_penolakan
                    ? "\n\nPenolakan Admin : ".$request->alasan_penolakan
                    : '')

        ]);

        return back()->with('success', 'Pengajuan berhasil ditolak.');
    }

}