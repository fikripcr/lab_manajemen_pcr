<?php

namespace App\Http\Requests\Lab;

use App\Http\Requests\BaseRequest;

class JadwalImportRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => 'required|mimes:xlsx,xls,csv',
        ];
    }
}
