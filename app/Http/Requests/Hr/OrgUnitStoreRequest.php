<?php

namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;

class OrgUnitStoreRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'parent_id' => 'nullable|exists:hr_org_unit,org_unit_id',
            'level' => 'nullable|integer|min:1',
            'sort_order' => 'nullable|integer|min:1',
            'is_active' => 'nullable|boolean',
            'description' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama organisasi harus diisi.',
            'name.string' => 'Nama harus berupa string.',
            'name.max' => 'Nama maksimal 255 karakter.',
            'type.required' => 'Tipe harus diisi.',
            'type.string' => 'Tipe harus berupa string.',
            'parent_id.exists' => 'Parent organisasi tidak ditemukan.',
            'level.integer' => 'Level harus berupa angka.',
            'level.min' => 'Level minimal 1.',
            'sort_order.integer' => 'Urutan harus berupa angka.',
            'sort_order.min' => 'Urutan minimal 1.',
            'is_active.boolean' => 'Status aktif harus true atau false.',
            'description.string' => 'Deskripsi harus berupa string.',
            'description.max' => 'Deskripsi maksimal 1000 karakter.',
        ];
    }
}
