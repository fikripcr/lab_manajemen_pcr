<?php

namespace App\Http\Requests\Hr;

use App\Http\Requests\BaseRequest;

class JenisIzinStoreRequest extends BaseRequest
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
            'nama' => 'required|string|max:50',
            'kategori' => 'nullable|string|max:10',
            'max_hari' => 'nullable|integer|min:1|max:365',
            'is_active' => 'nullable|boolean',
            'keterangan' => 'nullable|string|max:500',
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
            'nama.required' => 'Nama jenis izin harus diisi.',
            'nama.string' => 'Nama harus berupa string.',
            'nama.max' => 'Nama maksimal 50 karakter.',
            'kategori.string' => 'Kategori harus berupa string.',
            'kategori.max' => 'Kategori maksimal 10 karakter.',
            'max_hari.integer' => 'Maksimal hari harus berupa angka.',
            'max_hari.min' => 'Maksimal hari minimal 1.',
            'max_hari.max' => 'Maksimal hari maksimal 365.',
            'is_active.boolean' => 'Status aktif harus true atau false.',
            'keterangan.string' => 'Keterangan harus berupa string.',
            'keterangan.max' => 'Keterangan maksimal 500 karakter.',
        ]);
    }
}
