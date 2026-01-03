<?php
namespace App\Http\Requests\Sys;

use Illuminate\Foundation\Http\FormRequest;

class PermissionRequest extends FormRequest
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
        $permissionId = $this->route('id'); // Get the permission ID from the route for update operations

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            // For update requests, get the decrypted ID
            $realId       = decryptId($permissionId);
            $permissionId = $realId;
        }

        return [
            'name' => $permissionId ? 'required|unique:sys_permissions,name,' . $permissionId : 'required|unique:sys_permissions,name',
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
