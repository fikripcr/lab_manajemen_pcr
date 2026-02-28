<?php

namespace App\Http\Requests\Project;

use App\Http\Requests\BaseRequest;

class ProjectRequest extends BaseRequest
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
            'project_name' => 'required|string|max:200',
            'project_desc' => 'nullable|string',
            'is_agile'     => 'boolean',
            'start_date'   => 'required|date',
            'end_date'     => 'required|date|after_or_equal:start_date',
            'status'       => 'required|in:planning,active,completed,on_hold',
        ];

        // Add project_id for update
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['project_id'] = 'required|exists:pr_projects,project_id';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'project_name.required'      => 'Nama proyek wajib diisi',
            'project_name.max'           => 'Nama proyek maksimal 200 karakter',
            'start_date.required'        => 'Tanggal mulai wajib diisi',
            'start_date.date'            => 'Tanggal mulai tidak valid',
            'end_date.required'          => 'Tanggal akhir wajib diisi',
            'end_date.date'              => 'Tanggal akhir tidak valid',
            'end_date.after_or_equal'    => 'Tanggal akhir harus setelah atau sama dengan tanggal mulai',
            'status.required'            => 'Status wajib diisi',
            'status.in'                  => 'Status tidak valid',
        ]);
    }
}
