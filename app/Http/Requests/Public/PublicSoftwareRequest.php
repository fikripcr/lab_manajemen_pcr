<?php

namespace App\Http\Requests\Public;

use App\Http\Requests\BaseRequest;

class PublicSoftwareRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_software'     => 'required|string|max:255',
            'alasan'            => 'required|string',
            'mata_kuliah_ids'   => 'array',
            'mata_kuliah_ids.*' => 'exists:lab_mata_kuliahs,mata_kuliah_id',
        ];
    }
}
