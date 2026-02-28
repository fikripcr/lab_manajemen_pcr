<?php

namespace App\Http\Requests\Hr;

use App\Http\Requests\BaseRequest;

class PerizinanApproveRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status'        => 'required|in:Approved,Rejected,Pending',
            'pejabat'       => 'required|string|max:255',
            'keterangan'    => 'nullable|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'status'        => 'Status',
            'pejabat'       => 'Pejabat',
            'jenis_jabatan' => 'Jenis Jabatan',
            'keterangan'    => 'Keterangan',
        ];
    }
}
