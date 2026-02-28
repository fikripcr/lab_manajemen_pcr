<?php

namespace App\Http\Requests\Project;

use App\Http\Requests\BaseRequest;

class MoveTaskRequest extends BaseRequest
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
        return [
            'status' => 'required|in:todo,in_progress,done',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'status.required' => 'Status tujuan wajib diisi.',
            'status.in'       => 'Status tidak valid. Harus salah satu dari: todo, in_progress, review, done',
            'order.required' => 'Urutan baru wajib diisi.',
        ]);
    }
}
