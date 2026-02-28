<?php

namespace App\Http\Requests\Sys;

use App\Http\Requests\BaseRequest;

class NotificationSendRequest extends BaseRequest
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
            'user_id' => 'nullable',
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
        return array_merge(parent::messages(), [
            'type.in' => 'Tipe harus database atau email.',
            'user_id.exists' => 'User tidak ditemukan.',
            'level.in' => 'Level harus info, success, warning, atau error.',
        ]);
    }

    public function attributes(): array
    {
        return [
            'type' => 'Tipe Notifikasi',
            'user_id' => 'Penerima',
            'title' => 'Judul Notifikasi',
            'message' => 'Pesan Notifikasi',
            'level' => 'Level',
            'icon' => 'Icon',
        ];
    }
}
