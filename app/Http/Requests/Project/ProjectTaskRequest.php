<?php

namespace App\Http\Requests\Project;

use App\Http\Requests\BaseRequest;

class ProjectTaskRequest extends BaseRequest
{
    /**
     */

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'project_id'        => 'required|exists:pr_projects,project_id',
            'task_title'        => 'required|string|max:200',
            'task_desc'         => 'nullable|string',
            'project_phase_id'  => 'nullable|exists:pr_project_phases,project_phase_id',
            'project_sprint_id' => 'nullable|exists:pr_project_sprints,project_sprint_id',
            'assignee_id'       => 'nullable|exists:users,id',
            'parent_id'         => 'nullable|exists:pr_project_tasks,project_task_id',
            'status'            => 'required|in:todo,in_progress,done',
            'weight'            => 'integer|min:1|max:10',
            'hours_worked'      => 'integer|min:0',
            'priority'          => 'required|in:low,medium,high,urgent',
            'due_date'          => 'nullable|date',
            'seq'               => 'integer|min:0',
        ];

        return $rules;
    }

    /**
     */
}
