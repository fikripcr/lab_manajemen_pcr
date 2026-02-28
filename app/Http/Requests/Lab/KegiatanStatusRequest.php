<?php

namespace App\Http\Requests\Lab;

use App\Http\Requests\BaseRequest;

class KegiatanStatusRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status'  => 'required|in:approved,rejected,tangguhkan',
            'catatan' => 'nullable|string',
        ];
    }
}
