<?php

namespace App\Http\Requests\Project;

use App\Http\Requests\BaseRequest;

class ProjectTaskRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

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
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'project_id.required'         => 'Proyek wajib dipilih',
            'project_id.exists'           => 'Proyek tidak ditemukan',
            'task_title.required'         => 'Judul tugas wajib diisi',
            'task_title.max'              => 'Judul tugas maksimal 200 karakter',
            'status.required'             => 'Status wajib diisi',
            'status.in'                   => 'Status tidak valid',
            'priority.required'           => 'Prioritas wajib diisi',
            'priority.in'                 => 'Prioritas tidak valid',
            'weight.integer'              => 'Bobot harus berupa angka',
            'weight.min'                  => 'Bobot minimal 1',
            'weight.max'                  => 'Bobot maksimal 10',
            'hours_worked.integer'        => 'Jam kerja harus berupa angka',
            'hours_worked.min'            => 'Jam kerja minimal 0',
            'due_date.date'               => 'Tanggal jatuh tempo tidak valid',
        ]);
    }
}
