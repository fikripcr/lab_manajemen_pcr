<?php

namespace App\Http\Requests\Lab;

use App\Http\Requests\BaseRequest;

class LabTeamRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required', // ID is encrypted string
            'tanggal_mulai' => 'nullable|date',
        ];
    }

    public function attributes(): array
    {
        return [
            'user_id' => 'User',
            'jabatan' => 'Jabatan',
            'tanggal_mulai' => 'Tanggal Mulai',
        ];
    }
}
