<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
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
        $userId = $this->route('user'); // Get the user ID from the route parameter
        
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 
                'email', 
                Rule::unique('users')->ignore($userId)
            ],
            'role' => ['required', 'exists:roles,name'],
            'npm' => [
                'nullable', 
                'string', 
                Rule::unique('users')->ignore($userId, 'npm')
            ],
            'nip' => [
                'nullable', 
                'string', 
                Rule::unique('users')->ignore($userId, 'nip')
            ],
            'password' => [
                $this->isMethod('post') ? 'required' : 'nullable', // required for create (POST), optional for update (PUT/PATCH)
                'string', 
                'min:8', 
                'confirmed'
            ],
        ];
    }
}