<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGuruRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Sesuaikan jika ada middleware/permission khusus
    }

    public function rules(): array
    {
        return [
            // Validasi Akun (User)
            'name' => 'required|string|alpha_num|min:4|max:255|unique:users,name',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            
            // Validasi Data Guru
            'nip' => 'required|string|numeric|unique:gurus,nip',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date|before:today',
            'alamat' => 'required|string|max:500',
            'no_hp' => 'required|string|numeric|digits_between:10,15',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Maks 2MB
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'Username ini sudah digunakan oleh akun lain.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'nip.unique' => 'NIP sudah terdaftar di sistem.',
            'foto_profil.image' => 'File harus berupa gambar.',
            'foto_profil.max' => 'Ukuran foto maksimal adalah 2MB.',
        ];
    }
}