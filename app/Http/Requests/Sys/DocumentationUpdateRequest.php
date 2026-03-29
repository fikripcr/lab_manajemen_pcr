<?php

namespace App\Http\Requests\Sys;

use App\Http\Requests\BaseRequest;

class DocumentationUpdateRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'content' => 'required|string',
        ];
    }

    protected function customAttributes(): array
    {
        return [
            'content' => 'Konten Dokumentasi',
        ];
    }
}
