<?php
namespace App\Http\Requests\Pemutu;

use App\Http\Requests\BaseRequest;

class StoreTimMutuRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'auditee_id'       => ['nullable', 'string'],
            'ketua_auditor_id' => ['nullable', 'string'],
            'auditor_ids'      => ['nullable', 'array'],
            'auditor_ids.*'    => ['nullable', 'string'],
            'anggota_ids'      => ['nullable', 'array'],
            'anggota_ids.*'    => ['nullable', 'string'],
        ];
    }
}
