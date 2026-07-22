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
     * Body: { subscription: {...}, device_id: 'uuid', nama_perangkat: '...' }
     */
    public function save(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->guru) {
            return response()->json(['message' => 'Unauthorized or not a guru'], 403);
        }

        $validator = Validator::make($request->all(), [
            'subscription' => ['required', 'array'],
            'device_id' => ['nullable', 'string'],
            'nama_perangkat' => ['nullable', 'string'],
            'fcm_token' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Invalid data', 'errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        $deviceId = $data['device_id'] ?? null;

        // Cari device berdasarkan device_id atau buat baru
        $deviceQuery = DeviceGuru::where('guru_id', $user->guru->id);
        if ($deviceId) {
            $deviceQuery = $deviceQuery->where('device_id', $deviceId);
        }

        $device = $deviceQuery->first();

        if (!$device) {
            $device = DeviceGuru::create([
                'guru_id' => $user->guru->id,
                'device_id' => $deviceId ?? (string) Str::uuid(),
                'nama_perangkat' => $data['nama_perangkat'] ?? null,
                'fcm_token' => $data['fcm_token'] ?? null,
                'push_subscription' => $data['subscription'],
            ]);
        } else {
            $device->update([
                'nama_perangkat' => $data['nama_perangkat'] ?? $device->nama_perangkat,
                'fcm_token' => $data['fcm_token'] ?? $device->fcm_token,
                'push_subscription' => $data['subscription'],
            ]);
        }

        return response()->json(['message' => 'Subscription saved']);
    }
}
