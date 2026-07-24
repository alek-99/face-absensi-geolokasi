<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreJadwalRequest extends FormRequest
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
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'guru_id'    => ['required', 'exists:gurus,id'],
            'kelas_id'   => ['required', 'exists:kelas,id'],
            'mapel_id'   => ['required', 'exists:mapels,id'],
            'hari'       => ['required', 'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu'],
            'jam_masuk'  => ['required'],
            'jam_pulang' => ['required', 'after:jam_masuk'],
            'is_active'  => ['nullable', 'boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'guru_id.required'    => 'Guru pengajar wajib dipilih.',
            'guru_id.exists'      => 'Guru yang dipilih tidak valid.',
            'kelas_id.required'   => 'Kelas wajib dipilih.',
            'kelas_id.exists'     => 'Kelas yang dipilih tidak valid.',
            'mapel_id.required'   => 'Mata pelajaran wajib dipilih.',
            'mapel_id.exists'     => 'Mata pelajaran yang dipilih tidak valid.',
            'hari.required'       => 'Hari wajib dipilih.',
            'hari.in'             => 'Hari yang dipilih tidak valid.',
            'jam_masuk.required'  => 'Jam masuk wajib diisi.',
            'jam_pulang.required' => 'Jam pulang wajib diisi.',
            'jam_pulang.after'    => 'Jam pulang harus lebih akhir dari jam masuk.',
            'is_active.boolean'   => 'Status aktif harus berupa boolean.',
        ];
    }
}