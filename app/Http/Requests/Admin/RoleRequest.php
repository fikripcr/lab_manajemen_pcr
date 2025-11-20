<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
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
        $roleId = $this->route('role')?->id; // Get the role ID from the route for update operations
        return [
            'name' => $roleId ? 'required|unique:sys_roles,name,' . $roleId : 'required|unique:sys_roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:sys_permissions,name',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return validation_messages_id();
    }
}
