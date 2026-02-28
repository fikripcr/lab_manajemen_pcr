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
            'pejabat'    => 'required|string|max:191',
            'keterangan' => 'nullable|string',
        ];
    }
}
