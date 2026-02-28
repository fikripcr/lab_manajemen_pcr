<?php

namespace App\Http\Requests\Sys;

use App\Http\Requests\BaseRequest;

class RoleRequest extends BaseRequest
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
        $roleId = $this->route('role'); // Get the role ID from the route for update operations
        return [
            'name' => $roleId ? 'required|unique:sys_roles,name,' . decryptId($roleId) : 'required|unique:sys_roles,name',
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
            'name.required' => 'Nama role wajib diisi.',
            'name.unique'   => 'Nama role sudah digunakan.',
        ]);
    }
}
