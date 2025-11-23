<?php

namespace App\Http\Requests\Sys;

use Illuminate\Foundation\Http\FormRequest;

class RolePermissionRequest extends FormRequest
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
        return validation_messages_id();
    }
}
