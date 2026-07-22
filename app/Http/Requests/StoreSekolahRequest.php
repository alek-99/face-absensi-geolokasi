<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSekolahRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_sekolah' => 'required|string|max:255',
            'alamat' => 'required|string',

            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',

            'radius' => 'required|integer|min:1',

            'foto_sekolah' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'logo_sekolah' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            'no_telp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',

            'jam_masuk' => 'required|date_format:H:i',
            'jam_keluar' => 'required|date_format:H:i',
        ];
    }
}