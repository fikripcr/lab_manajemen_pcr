<?php

namespace App\Http\Requests\Sys;

use App\Http\Requests\BaseRequest;

class DocumentationUpdateRequest extends BaseRequest
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
            'content' => 'required|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'content' => 'Konten',
        ];
    }
}
