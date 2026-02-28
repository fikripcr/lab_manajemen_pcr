<?php

namespace App\Http\Requests\Sys;

use App\Http\Requests\BaseRequest;

class TestQrCodeGenerateRequest extends BaseRequest
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
            'text' => 'required|string|max:500',
            'size' => 'required|integer|min:100|max:500',
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
            'text.required' => 'Teks QR Code harus diisi.',
            'text.string' => 'Teks harus berupa string.',
            'text.max' => 'Teks maksimal 500 karakter.',
            'size.required' => 'Ukuran QR Code harus diisi.',
            'size.integer' => 'Ukuran harus berupa angka.',
            'size.min' => 'Ukuran minimal 100px.',
            'size.max' => 'Ukuran maksimal 500px.',
        ]);
    }
}
