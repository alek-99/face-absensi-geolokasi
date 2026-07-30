<?php

namespace App\Http\Controllers;

use App\Models\DeviceGuru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PushSubscriptionController extends Controller
{
    /**
     * Simpan subscription Web Push dari client ke tabel device_gurus.
     */
    public function save(Request $request)
    {
        $user = Auth::user();

        // Cek apakah user login dan memiliki relasi guru
        if (!$user || !method_exists($user, 'guru') || !$user->guru) {
            return response()->json([
                'message' => 'Unauthorized: Akun ini tidak terikat dengan data Guru.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'subscription'   => ['required', 'array'],
            'device_id'      => ['nullable', 'string'],
            'nama_perangkat' => ['nullable', 'string'],
            'fcm_token'      => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid data', 
                'errors'  => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();
        $deviceId = $data['device_id'] ?? (string) Str::uuid();

        // Simpan atau update data perangkat
        $device = DeviceGuru::updateOrCreate(
            [
                'guru_id'   => $user->guru->id,
                'device_id' => $deviceId,
            ],
            [
                'nama_perangkat'    => $data['nama_perangkat'] ?? null,
                'fcm_token'         => $data['fcm_token'] ?? null,
                'push_subscription' => $data['subscription'],
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Subscription berhasil disimpan',
            'data'    => $device
        ], 200);
    }
}