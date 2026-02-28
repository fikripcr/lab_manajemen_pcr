<?php

namespace App\Http\Requests\Sys;

use App\Http\Requests\BaseRequest;

class AppConfigurationRequest extends BaseRequest
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
    public function attributes(): array
    {
        return [
            'app_name' => 'Nama Aplikasi',
            'app_debug' => 'Mode Debug',
            'app_url' => 'URL Aplikasi',
            'mail_mailer' => 'Mail Mailer',
            'mail_host' => 'Mail Host',
            'mail_port' => 'Mail Port',
            'mail_username' => 'Mail Username',
            'mail_password' => 'Mail Password',
            'mail_encryption' => 'Mail Encryption',
            'mail_from_address' => 'Email Pengirim',
            'mail_from_name' => 'Nama Pengirim',
            'google_client_id' => 'Google Client ID',
            'google_client_secret' => 'Google Client Secret',
            'google_redirect_uri' => 'Google Redirect URI',
            'mysqldump_path' => 'Path Mysqldump',
        ];
    }
}