<?php

namespace App\Http\Requests\Eoffice;

use App\Http\Requests\BaseRequest;

class LayananDiskusiStoreRequest extends BaseRequest
{
    /**
     */

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
     *
     * @return array<string, string>
     */
}
