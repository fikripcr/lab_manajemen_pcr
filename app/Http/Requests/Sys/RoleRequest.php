<?php
namespace App\Http\Requests\Sys;

use App\Http\Requests\BaseRequest;

class RoleRequest extends BaseRequest
{
    /**
     */

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $roleId = $this->route('role'); // Get the role ID from the route for update operations
        return [
            'name'              => $roleId ? 'required|unique:sys_roles,name,' . decryptId($roleId) : 'required|string',
            'permissions'       => 'nullable|array',
            'sys.permissions.*' => 'exists:sys_permissions,name',
        ];
    }

    /**
     */
    public function attributes(): array
    {
        return [
            'name'              => 'Nama Role',
            'permissions'       => 'Permissions',
            'sys.permissions.*' => 'Permissions',
        ];
    }
}
