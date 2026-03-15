<?php

namespace App\Http\Requests\Project;

use App\Http\Requests\BaseRequest;

class MoveTaskRequest extends BaseRequest
{
    /**
     */

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'status' => 'required|in:todo,in_progress,done',
        ];
    }

    /**
     */
}
