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
            'anggota_ids.*'    => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'auditee_id'       => 'Auditee',
            'ketua_auditor_id' => 'Ketua Auditor',
            'auditor_ids'      => 'Auditor',
            'auditor_ids.*'    => 'Auditor',
            'anggota_ids'      => 'Anggota',
            'anggota_ids.*'    => 'Anggota',
        ];
    }
}
