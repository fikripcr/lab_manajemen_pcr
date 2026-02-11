<?php

namespace App\Http\Requests\Eoffice;

use Illuminate\Foundation\Http\FormRequest;

class JenisLayananDisposisiRequest extends FormRequest
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
            'model' => 'required|string|max:255',
            'value' => 'required|string|max:255',
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
            'model.required' => 'Model disposisi harus diisi.',
            'model.string' => 'Model disposisi harus berupa string.',
            'model.max' => 'Model disposisi maksimal 255 karakter.',
            'value.required' => 'Nilai disposisi harus diisi.',
            'value.string' => 'Nilai disposisi harus berupa string.',
            'value.max' => 'Nilai disposisi maksimal 255 karakter.',
        ];
    }
}
