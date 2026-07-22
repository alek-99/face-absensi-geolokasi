<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGuruRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $guru = $this->route('guru');

        return [
            // ==========================
            // Data Akun
            // ==========================
            'user' => [
                'required',
                'string',
                'alpha_num',
                'min:4',
                'max:255',
                Rule::unique('users', 'name')->ignore($guru->user_id),
            ],

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($guru->user_id),
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],

            // ==========================
            // Data Guru
            // ==========================
            'nip' => [
                'required',
                'numeric',
                Rule::unique('gurus', 'nip')->ignore($guru->id),
            ],

            'nama' => [
                'required',
                'string',
                'max:255',
            ],

            'jenis_kelamin' => [
                'required',
                'in:Laki-laki,Perempuan',
            ],

            'tempat_lahir' => [
                'required',
                'string',
                'max:255',
            ],

            'tanggal_lahir' => [
                'required',
                'date',
                'before:today',
            ],

            'alamat' => [
                'required',
                'string',
                'max:500',
            ],

            'no_hp' => [
                'required',
                'numeric',
                'digits_between:10,15',
            ],

            'foto_profil' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg',
                'max:2048',
            ],
        ];
    }

    /**
     * Pesan error validasi.
     */
    public function messages(): array
    {
        return [
            // User
            'user.required' => 'Username wajib diisi.',
            'user.alpha_num' => 'Username hanya boleh berisi huruf dan angka.',
            'user.min' => 'Username minimal 4 karakter.',
            'user.max' => 'Username maksimal 255 karakter.',
            'user.unique' => 'Username sudah digunakan.',

            // Email
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 255 karakter.',
            'email.unique' => 'Email sudah terdaftar.',

            // Password
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',

            // NIP
            'nip.required' => 'NIP wajib diisi.',
            'nip.numeric' => 'NIP hanya boleh berupa angka.',
            'nip.unique' => 'NIP sudah terdaftar.',

            // Nama
            'nama.required' => 'Nama guru wajib diisi.',
            'nama.max' => 'Nama maksimal 255 karakter.',

            // Jenis Kelamin
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'jenis_kelamin.in' => 'Jenis kelamin tidak valid.',

            // Tempat Lahir
            'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
            'tempat_lahir.max' => 'Tempat lahir maksimal 255 karakter.',

            // Tanggal Lahir
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid.',
            'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini.',

            // Alamat
            'alamat.required' => 'Alamat wajib diisi.',
            'alamat.max' => 'Alamat maksimal 500 karakter.',

            // No HP
            'no_hp.required' => 'Nomor HP wajib diisi.',
            'no_hp.numeric' => 'Nomor HP hanya boleh berupa angka.',
            'no_hp.digits_between' => 'Nomor HP harus terdiri dari 10 sampai 15 digit.',

            // Foto Profil
            'foto_profil.image' => 'File harus berupa gambar.',
            'foto_profil.mimes' => 'Foto profil harus berformat JPG, JPEG, atau PNG.',
            'foto_profil.max' => 'Ukuran foto profil maksimal 2 MB.',
        ];
    }

    /**
     * Nama atribut agar pesan error lebih mudah dibaca.
     */
    public function attributes(): array
    {
        return [
            'user' => 'Username',
            'email' => 'Email',
            'password' => 'Password',
            'nip' => 'NIP',
            'nama' => 'Nama Guru',
            'jenis_kelamin' => 'Jenis Kelamin',
            'tempat_lahir' => 'Tempat Lahir',
            'tanggal_lahir' => 'Tanggal Lahir',
            'alamat' => 'Alamat',
            'no_hp' => 'Nomor HP',
            'foto_profil' => 'Foto Profil',
        ];
    }
}