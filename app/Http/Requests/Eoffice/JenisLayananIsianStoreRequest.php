<?php

namespace App\Http\Requests\Eoffice;

use App\Http\Requests\BaseRequest;

class JenisLayananIsianStoreRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'kategoriisian_id' => 'required|exists:eoffice_kategori_isian,kategoriisian_id',
            'seq' => 'required|integer',
            'is_required' => 'nullable|boolean',
        ];
    }

    /**
     * @return array<string, string>
     */
}
