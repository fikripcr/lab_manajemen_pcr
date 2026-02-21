<?php
namespace App\Http\Requests\Survei;

use Illuminate\Foundation\Http\FormRequest;

class SurveiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'judul'           => 'required|string|max:255',
            'deskripsi'       => 'nullable|string',
            'target_role'     => 'required|in:Mahasiswa,Dosen,Tendik,Alumni,Umum',
            'tanggal_mulai'   => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'is_aktif'        => 'boolean',
            'wajib_login'     => 'boolean',
            'bisa_isi_ulang'  => 'boolean',
            'mode'            => 'required|in:Linear,Bercabang',
        ];
    }

    public function messages(): array
    {
        return [
            'judul.required'                 => 'Judul survei wajib diisi.',
            'target_role.required'           => 'Target role wajib dipilih.',
            'target_role.in'                 => 'Target role tidak valid.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',
            'mode.required'                  => 'Mode survei wajib dipilih.',
            'mode.in'                        => 'Mode survei tidak valid.',
        ];
    }
}
