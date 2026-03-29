<?php

namespace App\Http\Requests\Project;

use App\Http\Requests\BaseRequest;

class ProjectRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'project_name' => 'required|string|max:200',
            'project_desc' => 'nullable|string',
            'is_agile' => 'boolean',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:planning,active,completed,on_hold',
        ];

        // Add project_id for update
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['project_id'] = 'required|exists:pr_projects,project_id';
        }

        return $rules;
    }

}
