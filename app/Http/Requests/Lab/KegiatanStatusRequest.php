<?php

namespace App\Http\Requests\Lab;

use App\Http\Requests\BaseRequest;

class KegiatanStatusRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'catatan' => 'nullable|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'status' => 'Status',
            'catatan' => 'Catatan',
        ];
    }
}
