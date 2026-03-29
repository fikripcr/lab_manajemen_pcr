<?php

namespace App\Http\Requests\Project;

use App\Http\Requests\BaseRequest;

class ProjectCostRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'project_task_id' => 'nullable|exists:pr_project_tasks,project_task_id',
            'cost_desc' => 'required|string|max:255',
            'cost_type' => 'required|in:in_cash,out_cash',
            'amount' => 'required|numeric|min:0',
            'cost_date' => 'required|date',
            'approval_status' => 'nullable|in:pending,approved,rejected',
        ];
    }
}
