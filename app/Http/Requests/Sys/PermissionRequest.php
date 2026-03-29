<?php

namespace App\Http\Requests\Sys;

use App\Http\Requests\BaseRequest;

class PermissionRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $permissionId = $this->route('id'); // Get the permission ID from the route for update operations

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            // For update requests, get the decrypted ID
            $realId = decryptId($permissionId);
            $permissionId = $realId;
        }

        return [
            'name' => $permissionId ? 'required|unique:sys_permissions,name,'.$permissionId : 'required|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Nama Permission',
        ];
    }
}
