<?php

namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;

class JenisIndisiplinerUpdateRequest extends FormRequest
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
        $jenisIndisipliner = $this->route('jenis_indisipliner');
        return [
            'jenis_indisipliner' => 'required|string|max:100|unique:hr_jenis_indisipliner,jenis_indisipliner,' . $jenisIndisipliner->jenisindisipliner_id . ',jenisindisipliner_id',
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
            'jenis_indisipliner.required' => 'Jenis indisipliner harus diisi.',
            'jenis_indisipliner.string' => 'Jenis indisipliner harus berupa string.',
            'jenis_indisipliner.max' => 'Jenis indisipliner maksimal 100 karakter.',
            'jenis_indisipliner.unique' => 'Jenis indisipliner sudah ada.',
        ];
    }
}
