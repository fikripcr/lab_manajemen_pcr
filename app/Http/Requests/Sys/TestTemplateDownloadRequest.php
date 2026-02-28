<?php

namespace App\Http\Requests\Sys;

use App\Http\Requests\BaseRequest;

class TestTemplateDownloadRequest extends BaseRequest
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
            'template' => 'required|file|mimes:doc,docx|max:10240', // Max 10MB
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
            'template.required' => 'Template harus diupload.',
            'template.file' => 'Template harus berupa file.',
            'template.mimes' => 'Template harus berformat DOC atau DOCX.',
            'template.max' => 'Template maksimal 10MB.',
        ]);
    }
}
