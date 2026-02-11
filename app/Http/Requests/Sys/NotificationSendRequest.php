<?php

namespace App\Http\Requests\Sys;

use Illuminate\Foundation\Http\FormRequest;

class NotificationSendRequest extends FormRequest
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
            'type' => 'required|in:database,email',
            'user_id' => 'nullable|exists:users,id',
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'level' => 'nullable|in:info,success,warning,error',
            'icon' => 'nullable|string|max:50',
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
            'type.required' => 'Tipe notifikasi harus diisi.',
            'type.in' => 'Tipe harus database atau email.',
            'user_id.exists' => 'User tidak ditemukan.',
            'title.required' => 'Judul notifikasi harus diisi.',
            'title.max' => 'Judul maksimal 255 karakter.',
            'message.required' => 'Pesan notifikasi harus diisi.',
            'message.max' => 'Pesan maksimal 1000 karakter.',
            'level.in' => 'Level harus info, success, warning, atau error.',
            'icon.max' => 'Icon maksimal 50 karakter.',
        ];
    }
}
