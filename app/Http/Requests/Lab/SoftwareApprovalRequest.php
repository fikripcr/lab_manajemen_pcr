<?php

namespace App\Http\Requests\Lab;

use App\Http\Requests\BaseRequest;

class SoftwareApprovalRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status'     => 'required|in:approved,rejected,tangguhkan',
            'keterangan' => 'nullable|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'status'     => 'Status',
            'pejabat'    => 'Pejabat',
            'keterangan' => 'Keterangan',
        ];
    }
}
