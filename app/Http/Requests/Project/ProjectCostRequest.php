<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class ProjectCostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'project_task_id' => 'nullable|exists:pr_project_tasks,project_task_id',
            'cost_desc'       => 'required|string|max:255',
            'cost_type'       => 'required|in:in_cash,out_cash',
            'amount'          => 'required|numeric|min:0',
            'cost_date'       => 'required|date',
            'approval_status' => 'nullable|in:pending,approved,rejected',
        ];
    }

    public function messages(): array
    {
        return [
            'cost_desc.required' => 'Description is required.',
            'cost_type.required' => 'Cost type is required.',
            'amount.required'    => 'Amount is required.',
            'cost_date.required' => 'Date is required.',
        ];
    }
}
