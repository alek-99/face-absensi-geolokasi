<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSekolahRequest;
use App\Http\Requests\UpdateSekolahRequest;
use App\Models\Sekolah;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SekolahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sekolahs = Sekolah::latest()->paginate(10);

        return view('admin.sekolah.index', compact('sekolahs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.sekolah.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSekolahRequest $request)
    {
        DB::beginTransaction();

        try {

            $data = $request->validated();

            if ($request->hasFile('foto_sekolah')) {
                $data['foto_sekolah'] = $request->file('foto_sekolah')
                    ->store('sekolah/foto', 'public');
            }

            if ($request->hasFile('logo_sekolah')) {
                $data['logo_sekolah'] = $request->file('logo_sekolah')
                    ->store('sekolah/logo', 'public');
            }

            Sekolah::create($data);

            DB::commit();

            return redirect()
                ->route('admin.sekolah.index')
                ->with('success', 'Data sekolah berhasil ditambahkan.');
        } catch (\Throwable $e) {

            DB::rollBack();

            if (!empty($data['foto_sekolah'])) {
                Storage::disk('public')->delete($data['foto_sekolah']);
            }

            if (!empty($data['logo_sekolah'])) {
                Storage::disk('public')->delete($data['logo_sekolah']);
            }

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Sekolah $sekolah)
    {
        return view('admin.sekolah.show', compact('sekolah'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sekolah $sekolah)
    {
        return view('admin.sekolah.edit', compact('sekolah'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSekolahRequest $request, Sekolah $sekolah)
    {
        DB::beginTransaction();

        try {

            $data = $request->validated();

            if ($request->hasFile('foto_sekolah')) {

                if ($sekolah->foto_sekolah &&
                    Storage::disk('public')->exists($sekolah->foto_sekolah)) {

                    Storage::disk('public')->delete($sekolah->foto_sekolah);
                }

                $data['foto_sekolah'] = $request->file('foto_sekolah')
                    ->store('sekolah/foto', 'public');
            }

            if ($request->hasFile('logo_sekolah')) {

                if ($sekolah->logo_sekolah &&
                    Storage::disk('public')->exists($sekolah->logo_sekolah)) {

                    Storage::disk('public')->delete($sekolah->logo_sekolah);
                }

                $data['logo_sekolah'] = $request->file('logo_sekolah')
                    ->store('sekolah/logo', 'public');
            }

            $sekolah->update($data);

            DB::commit();

            return redirect()
                ->route('admin.sekolah.index')
                ->with('success', 'Data sekolah berhasil diperbarui.');
        } catch (\Throwable $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sekolah $sekolah)
    {
        DB::beginTransaction();

        try {

            if ($sekolah->foto_sekolah &&
                Storage::disk('public')->exists($sekolah->foto_sekolah)) {

                Storage::disk('public')->delete($sekolah->foto_sekolah);
            }

            if ($sekolah->logo_sekolah &&
                Storage::disk('public')->exists($sekolah->logo_sekolah)) {

                Storage::disk('public')->delete($sekolah->logo_sekolah);
            }

            $sekolah->delete();

            DB::commit();

            return redirect()
                ->route('admin.sekolah.index')
                ->with('success', 'Data sekolah berhasil dihapus.');
        } catch (\Throwable $e) {

            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }
}