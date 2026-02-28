<?php

namespace App\Http\Requests\Eoffice;

use App\Http\Requests\BaseRequest;

class LayananDiskusiStoreRequest extends BaseRequest
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
     * Prepare the request for validation, decrypting encrypted IDs.
     */
    protected function prepareForValidation(): void
    {
        if ($this->filled('layanan_id')) {
            $this->merge([
                'layanan_id' => decryptIdIfEncrypted($this->layanan_id),
            ]);
        }
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'layanan_id.required' => 'Layanan harus dipilih.',
            'pesan.required' => 'Pesan harus diisi.',
            'pesan.string' => 'Pesan harus berupa string.',
            'status_pengirim.string' => 'Status pengirim harus berupa string.',
        ]);
    }
}
