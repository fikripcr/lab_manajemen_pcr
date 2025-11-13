<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MataKuliahRequest extends FormRequest
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
        $mataKuliahId = $this->route('mata_kuliah'); // Get the mata kuliah ID from the route for update operations

        return [
            'kode_mk' => $mataKuliahId ? 'required|string|max:20|unique:mata_kuliahs,kode_mk,' . $mataKuliahId : 'required|string|max:20|unique:mata_kuliahs,kode_mk',
            'nama_mk' => 'required|string|max:255',
            'sks' => 'required|integer|min:1|max:6',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'kode_mk.required' => 'Kode mata kuliah wajib diisi.',
            'kode_mk.string' => 'Kode mata kuliah harus berupa teks.',
            'kode_mk.max' => 'Kode mata kuliah maksimal :max karakter.',
            'kode_mk.unique' => 'Kode mata kuliah sudah digunakan.',
            'nama_mk.required' => 'Nama mata kuliah wajib diisi.',
            'nama_mk.string' => 'Nama mata kuliah harus berupa teks.',
            'nama_mk.max' => 'Nama mata kuliah maksimal :max karakter.',
            'sks.required' => 'SKS wajib diisi.',
            'sks.integer' => 'SKS harus berupa angka.',
            'sks.min' => 'SKS minimal :min.',
            'sks.max' => 'SKS maksimal :max.',
        ];
    }
}