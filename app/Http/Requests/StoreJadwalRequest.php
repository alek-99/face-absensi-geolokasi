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
            'guru_id' => ['required', 'exists:gurus,id'],
            'kelas' => ['required', 'string', 'max:255'],
            'mata_pelajaran' => ['required', 'string', 'max:255'],
            'hari' => ['required', 'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu'],
            'jam_masuk' => [
                'required',
                'date_format:H:i',
            ],

            'jam_pulang' => [
                'required',
                'date_format:H:i',
                'after:jam_masuk',
            ],
            'ruangan' => ['nullable', 'string', 'max:255'],
            'is_active' => ['boolean'],
        ];
    }
    public function messages(): array
    {
        return [
            'guru_id.required' => 'Guru harus dipilih.',
            'guru_id.exists' => 'Guru yang dipilih tidak valid.',
            'kelas.required' => 'Kelas harus diisi.',
            'mata_pelajaran.required' => 'Mata pelajaran harus diisi.',
            'hari.required' => 'Hari harus dipilih.',
            'hari.in' => 'Hari yang dipilih tidak valid.',
            'jam_masuk.required' => 'Jam masuk harus diisi.',
            'jam_masuk.date_format' => 'Format jam masuk tidak valid. Gunakan format HH:MM.',
            'jam_pulang.required' => 'Jam pulang harus diisi.',
            'jam_pulang.date_format' => 'Format jam pulang tidak valid. Gunakan format HH:MM.',
            'jam_pulang.after' => 'Jam pulang harus lebih besar dari jam masuk.',
            'ruangan.string' => 'Ruangan harus berupa teks.',
            'is_active.boolean' => 'Status aktif harus berupa nilai boolean.',
        ];
    }
}
