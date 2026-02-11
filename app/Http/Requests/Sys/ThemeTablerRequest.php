<?php

namespace App\Http\Requests\Sys;

use Illuminate\Foundation\Http\FormRequest;

class ThemeTablerRequest extends FormRequest
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
            'mode' => 'required|in:sys,auth',
            'theme' => 'nullable|in:light,dark',
            'theme-primary' => 'nullable|string',
            'theme-header' => 'nullable|string',
            'theme-sidebar' => 'nullable|string',
            'theme-body' => 'nullable|string',
            'theme-font' => 'nullable|string',
            'theme-font-size' => 'nullable|string',
            'theme-radius' => 'nullable|string',
            'theme-contrast' => 'nullable|string',
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
            'mode.required' => 'Mode harus diisi.',
            'mode.in' => 'Mode harus sys atau auth.',
            'theme.in' => 'Theme harus light atau dark.',
        ];
    }
}
