<?php

namespace App\Http\Requests\Sys;

use Illuminate\Foundation\Http\FormRequest;

class AppConfigurationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Adjust authorization logic as needed
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $configSection = $this->input('config_section', 'app');

        switch($configSection) {
            case 'app':
                return [
                    'app_name' => 'nullable|string|max:255',
                    'app_debug' => 'nullable|boolean',
                    'app_url' => 'nullable|url',
                ];
            case 'mail':
                return [
                    'mail_mailer' => 'nullable|string',
                    'mail_host' => 'nullable|string',
                    'mail_port' => 'nullable|integer',
                    'mail_username' => 'nullable|string',
                    'mail_password' => 'nullable|string',
                    'mail_encryption' => 'nullable|string',
                    'mail_from_address' => 'nullable|email',
                    'mail_from_name' => 'nullable|string',
                ];
            case 'google':
                return [
                    'google_client_id' => 'nullable|string',
                    'google_client_secret' => 'nullable|string',
                    'google_redirect_uri' => 'nullable|url',
                ];
            case 'backup':
                return [
                    'mysqldump_path' => 'nullable|string',
                ];
            default:
                return [];
        }
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'app_name.string' => 'The application name must be a text string.',
            'app_name.max' => 'The application name must not exceed 255 characters.',
            'app_debug.boolean' => 'The debug mode must be enabled or disabled.',
            'app_url.url' => 'The application URL must be a valid URL format.',
            'mail_port.integer' => 'The mail port must be a number.',
            'mail_from_address.email' => 'The mail from address must be a valid email address.',
            'google_redirect_uri.url' => 'The Google redirect URI must be a valid URL format.',
            'mysqldump_path.string' => 'The mysqldump path must be a text string.',
        ];
    }
}