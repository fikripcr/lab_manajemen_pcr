<?php

namespace App\Http\Requests\Sys;

use App\Http\Requests\BaseRequest;

class NotificationSendRequest extends BaseRequest
{
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
     * @return array<string, string>
     */
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
