<?php

namespace App\Http\Requests\Pemutu;

use App\Http\Requests\BaseRequest;

class OrgUnitAuditeeRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'auditee_user_id' => 'nullable|exists:users,id',
        ];
    }

    public function attributes(): array
    {
        return [
            'auditee_user_id' => 'User Auditee',
            'org_unit_id' => 'Unit Kerja',
        ];
    }
}
