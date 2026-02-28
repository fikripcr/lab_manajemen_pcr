<?php

namespace App\Http\Requests\Eoffice;

use App\Http\Requests\BaseRequest;

class JenisLayananPeriodeStoreRequest extends BaseRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
            'tahun_ajaran' => 'nullable|string',
            'semester' => 'nullable|string',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'tgl_mulai.required'   => 'Tanggal mulai harus diisi.',
            'tgl_mulai.date' => 'Tanggal mulai harus berupa tanggal.',
            'tgl_selesai.required' => 'Tanggal selesai harus diisi.',
            'tgl_selesai.date' => 'Tanggal selesai harus berupa tanggal.',
            'tgl_selesai.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',
            'tahun_ajaran.string' => 'Tahun ajaran harus berupa string.',
            'semester.string' => 'Semester harus berupa string.',
        ]);
    }
}
