<?php

namespace App\Http\Requests\Sys;

use App\Http\Requests\BaseRequest;

class TestDocxTemplateRequest extends BaseRequest
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
            'template' => 'required|file|mimes:doc,docx|max:10240', // Max 10MB
        ];
    }

    /**
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'template' => 'Template DOCX',
        ];
    }
}
