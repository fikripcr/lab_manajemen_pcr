<?php

namespace App\Http\Requests\Eoffice;

use App\Http\Requests\BaseRequest;

class FeedbackStoreRequest extends BaseRequest
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
            'rating' => 'required|numeric|min:1|max:5',
            'feedback' => 'required|string',
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
            'rating.required'  => 'Rating harus diisi.',
            'rating.integer'   => 'Rating tidak valid.',
            'rating.min'       => 'Rating minimal 1.',
            'rating.max'       => 'Rating maksimal 5.',
            'catatan.required' => 'Catatan harus diisi.',
            'catatan.min'      => 'Catatan minimal 5 karakter.',
        ]);
    }
}
