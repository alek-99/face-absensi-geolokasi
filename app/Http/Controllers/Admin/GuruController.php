<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGuruRequest;
use App\Http\Requests\UpdateGuruRequest;
use App\Models\Guru;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $status = $request->status;

        $gurus = Guru::query()
        ->when($search, function ($query) use($search){
            $query->where('nama', 'like', '%' . $search . '%');
        })
        ->when($status !== null && $status !== '', function ($query) use ($status) {
            $query->where('is_active', $status);
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();

        return view('admin.guru.index', compact('gurus'));
    }

    public function create()
    {
        return view('admin.guru.create');
    }

    public function store(StoreGuruRequest $request)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validated();

            // ================= START: NORMALISASI NO HP =================
            if (!empty($validated['no_hp'])) {
                // Hilangkan spasi, strip (-), atau karakter non-angka lainnya
                $nomor = preg_replace('/[^0-9]/', '', $validated['no_hp']);
                
                // Jika diawali dengan angka '0', ubah menjadi '62'
                if (strpos($nomor, '0') === 0) {
                    $nomor = '62' . substr($nomor, 1);
                }
                $validated['no_hp'] = $nomor;
            }
            // ================= END: NORMALISASI NO HP =================

            // Buat Akun User
            $user = User::create([
                'name' => $validated['nama'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'guru',
            ]);

            // Handle Upload Foto Profil
            $fotoPath = null;
            if ($request->hasFile('foto_profil')) {
                $fotoPath = $request->file('foto_profil')->store('foto_profil', 'public');
            }

            // Buat Data Guru
            Guru::create([
                'user_id' => $user->id,
                'nip' => $validated['nip'],
                'nama' => $validated['nama'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'tempat_lahir' => $validated['tempat_lahir'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'alamat' => $validated['alamat'],
                'no_hp' => $validated['no_hp'], // Sudah dalam format 62xxx
                'foto_profil' => $fotoPath,
                'is_active' => true,
            ]);

            DB::commit();

            return redirect()->route('admin.guru.index')
                ->with('success', 'Data Guru dan Akun Login berhasil dibuat!');
        } catch (Exception $e) {
            DB::rollBack();

            if (isset($fotoPath)) {
                Storage::disk('public')->delete($fotoPath);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function edit(Guru $guru)
    {
        $guru->load('user');
        return view('admin.guru.edit', compact('guru'));
    }

    public function update(UpdateGuruRequest $request, Guru $guru)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validated();

            // ================= START: NORMALISASI NO HP =================
            if (!empty($validated['no_hp'])) {
                // Hilangkan spasi, strip (-), atau karakter non-angka lainnya
                $nomor = preg_replace('/[^0-9]/', '', $validated['no_hp']);
                
                // Jika diawali dengan angka '0', ubah menjadi '62'
                if (strpos($nomor, '0') === 0) {
                    $nomor = '62' . substr($nomor, 1);
                }
                $validated['no_hp'] = $nomor;
            }
            // ================= END: NORMALISASI NO HP =================

            // Update Data User
            $guru->user->update([
                'name' => $validated['user'],
                'email' => $validated['email'],
                'password' => !empty($validated['password'])
                    ? Hash::make($validated['password'])
                    : $guru->user->password,
            ]);

            // Upload Foto Profil
            if ($request->hasFile('foto_profil')) {
                if ($guru->foto_profil && Storage::disk('public')->exists($guru->foto_profil)) {
                    Storage::disk('public')->delete($guru->foto_profil);
                }

                $validated['foto_profil'] = $request
                    ->file('foto_profil')
                    ->store('foto_profil', 'public');
            } else {
                unset($validated['foto_profil']);
            }

            // Field milik tabel users jangan ikut diupdate ke tabel gurus
            unset(
                $validated['user'],
                $validated['email'],
                $validated['password'],
                $validated['password_confirmation']
            );
          
            // Update Data Guru (no_hp aman terjaga dalam format 62)
            $guru->update($validated);

            DB::commit();

            return redirect()
                ->route('admin.guru.index')
                ->with('success', 'Data guru berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data guru. Error: ' . $e->getMessage());
        }
    }

    public function destroy(Guru $guru)
    {
        DB::beginTransaction();

        try {
            if ($guru->foto_profil) {
                Storage::disk('public')->delete($guru->foto_profil);
            }

            $guru->user->delete();

            DB::commit();

            return redirect()->route('admin.guru.index')
                ->with('success', 'Data Guru dan Akun Login berhasil dihapus!');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function show(Guru $guru)
    {
        $guru->load('user');
        return view('admin.guru.show', compact('guru'));
    }

    public function updatePassword(Request $request, Guru $guru)
    {
        $request->validate([
            'password' => [
                'required',
                'string',
                'min:8',
                'max:50',
                'confirmed',
            ],
        ], [
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.max' => 'Password maksimal 50 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
        ]);

        try {
            $guru->user->update([
                'password' => Hash::make($request->password),
            ]);

            return redirect()->back()->with('success', 'Password berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'password' => 'Gagal memperbarui password.',
                ]);
        }
    }
}