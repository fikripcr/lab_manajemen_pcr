<?php

namespace App\Http\Requests\Pemutu;

use App\Http\Requests\BaseRequest;

class OrgUnitAuditeeRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

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

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'auditee_user_id.exists' => 'User auditee tidak ditemukan.',
            'org_unit_id.required'  => 'Unit Kerja harus dipilih.',
            'org_unit_id.exists'    => 'Unit Kerja tidak ditemukan.',
        ]);
    }
}
