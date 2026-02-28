<?php

namespace App\Http\Requests\Lab;

use App\Http\Requests\BaseRequest;

class UserImportRequest extends BaseRequest
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
            'file' => 'required|mimes:xlsx,xls,csv|max:10240', // Max 10MB
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
            'file.required' => 'File harus diunggah.',
            'file.mimes'    => 'Format file harus .xlsx atau .xls.',
            'file.max'      => 'File maksimal 10MB.',
        ]);
    }
}
