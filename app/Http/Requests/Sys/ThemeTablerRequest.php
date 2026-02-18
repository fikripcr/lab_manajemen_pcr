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
            'mode'                    => 'required|in:sys,auth,tabler',
            'theme'                   => 'nullable|in:light,dark',
            'theme-primary'           => 'nullable|string',
            'theme-font'              => 'nullable|string',
            'theme-base'              => 'nullable|string',
            'theme-radius'            => 'nullable|string',
            'theme-card-style'        => 'nullable|string',
            'theme-header-sticky'     => 'nullable|string',
            'theme-bg'                => 'nullable|string',
            'theme-sidebar-bg'        => 'nullable|string',
            'theme-header-top-bg'     => 'nullable|string',
            'theme-header-overlap-bg' => 'nullable|string',
            'theme-boxed-bg'          => 'nullable|string',
            'layout'                  => 'nullable|string',
            'container-width'         => 'nullable|string',
            'auth-layout'             => 'nullable|string',
            'auth-form-position'      => 'nullable|string',
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
            'mode.in'       => 'Mode harus sys, auth, atau tabler.',
            'theme.in'      => 'Theme harus light atau dark.',
        ];
    }
}
