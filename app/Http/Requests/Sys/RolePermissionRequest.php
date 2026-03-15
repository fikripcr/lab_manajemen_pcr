<?php

namespace App\Http\Requests\Sys;

use App\Http\Requests\BaseRequest;

class RolePermissionRequest extends BaseRequest
{
    /**
     */

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
     */

    public function attributes(): array
    {
        return [
            'permissions' => 'Permissions',
            'sys.permissions.*' => 'Permissions',
        ];
    }
}
