<?php

namespace App\Http\Requests\Sys;

use Illuminate\Foundation\Http\FormRequest;

class TestDocxTemplateRequest extends FormRequest
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
        return [
            'template.required' => 'File template harus diupload.',
            'template.file' => 'Harus berupa file.',
            'template.mimes' => 'File harus berformat DOC atau DOCX.',
            'template.max' => 'File maksimal 10MB.',
        ];
    }
}
