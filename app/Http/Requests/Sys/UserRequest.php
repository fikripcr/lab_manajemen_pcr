<?php

namespace App\Http\Requests\Sys;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class UserRequest extends BaseRequest
{
    /**
     * @return bool
     */

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Determine if this is an update operation.
        // POST = create (password required), PUT/PATCH = update (password optional).
        $isUpdate = in_array($this->method(), ['PUT', 'PATCH']);

        // Try to resolve user ID for unique email validation.
        $userId = null;
        if ($isUpdate) {
            $userId = decryptIdIfEncrypted($this->route('user'));
        }

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                $userId ? Rule::unique('users')->ignore($userId) : '',
            ],
            'role' => ['required', 'array'],
            'role.*' => ['exists:sys_roles,name'],
            'password' => [
                $isUpdate ? 'nullable' : 'required',
                'string',
                'min:8',
                'confirmed',
            ],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'expired_at' => ['nullable', 'date'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Nama',
            'email' => 'Email',
            'role' => 'Role',
            'role.*' => 'Role',
            'password' => 'Password',
            'avatar' => 'Avatar',
            'expired_at' => 'Tanggal Kedaluwarsa',
        ];
    }
}
