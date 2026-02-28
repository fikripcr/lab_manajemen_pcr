<?php
namespace App\Http\Requests\Pemutu;

use App\Http\Requests\BaseRequest;

class StandarAssignmentRequest extends BaseRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'assignments'            => 'required|array',
            'assignments.*.target'   => 'nullable|string|max:255',
        ];
    }

    public function attributes(): array
    {
        return [
            'assignments'            => 'Penugasan Standar',
            'assignments.*.selected' => 'Status Pilihan',
            'assignments.*.target'   => 'Target Penugasan',
        ];
    }
}
