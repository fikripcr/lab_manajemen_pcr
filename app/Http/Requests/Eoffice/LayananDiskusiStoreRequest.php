<?php

namespace App\Http\Requests\Eoffice;

use Illuminate\Foundation\Http\FormRequest;

class LayananDiskusiStoreRequest extends FormRequest
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
            'layanan_id' => 'required',
            'pesan' => 'required|string',
            'status_pengirim' => 'nullable|string',
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
            'layanan_id.required' => 'Layanan harus dipilih.',
            'pesan.required' => 'Pesan harus diisi.',
            'pesan.string' => 'Pesan harus berupa string.',
            'status_pengirim.string' => 'Status pengirim harus berupa string.',
        ];
    }
}
