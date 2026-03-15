<?php

namespace App\Http\Requests\Project;

use App\Http\Requests\BaseRequest;

class ProjectMemberRequest extends BaseRequest
{

    public function rules(): array
    {
        return [
            'user_id'        => 'required|exists:users,id',
            'role'           => 'required|in:leader,member,viewer',
            'alias_position' => 'nullable|string|max:100',
            'rate_per_hour'  => 'nullable|numeric|min:0',
        ];
    }

}
