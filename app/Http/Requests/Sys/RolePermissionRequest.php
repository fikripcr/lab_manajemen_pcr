<?php

namespace App\Http\Requests\Sys;

use App\Http\Requests\BaseRequest;

class RolePermissionRequest extends BaseRequest
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
     */
    public function rules(): array
    {
        return [
            'permissions' => 'nullable|array',
            'sys.permissions.*' => 'exists:sys_permissions,name',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'permissions.required' => 'Setidaknya satu permission harus dipilih.',
        ]);
    }

    public function attributes(): array
    {
        return [
            'permissions' => 'Permissions',
            'sys.permissions.*' => 'Permissions',
        ];
    }
}
