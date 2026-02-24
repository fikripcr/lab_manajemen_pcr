<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class ProjectMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id'        => 'required|exists:users,id',
            'role'           => 'required|in:leader,member,viewer',
            'alias_position' => 'nullable|string|max:100',
            'rate_per_hour'  => 'nullable|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'User must be selected.',
            'user_id.exists'   => 'User not found.',
            'role.required'    => 'Role is required.',
            'role.in'          => 'Invalid role selected.',
        ];
    }
}
