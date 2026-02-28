<?php

namespace App\Http\Requests\Project;

use App\Http\Requests\BaseRequest;

class ProjectMemberRequest extends BaseRequest
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
        return array_merge(parent::messages(), [
            'user_id.required' => 'Anggota tim harus dipilih.',
            'user_id.exists' => 'User tidak ditemukan.',
            'role.required' => 'Peran anggota wajib diisi.',
            'role.in'          => 'Invalid role selected.',
        ]);
    }
}
