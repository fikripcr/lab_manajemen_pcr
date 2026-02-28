<?php

namespace App\Http\Requests\Eoffice;

use App\Http\Requests\BaseRequest;

class JenisLayananDisposisiRequest extends BaseRequest
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
        return array_merge(parent::messages(), [
            'model.required' => 'Model disposisi harus diisi.',
            'model.string' => 'Model disposisi harus berupa string.',
            'model.max' => 'Model disposisi maksimal 255 karakter.',
            'value.required' => 'Nilai disposisi harus diisi.',
            'value.string' => 'Nilai disposisi harus berupa string.',
            'value.max' => 'Nilai disposisi maksimal 255 karakter.',
            'pic_id.required'  => 'Penerima disposisi harus dipilih.',
            'pesan.required'   => 'Pesan disposisi harus diisi.',
            'pesan.min'        => 'Pesan disposisi minimal 5 karakter.',
        ]);
    }
}
