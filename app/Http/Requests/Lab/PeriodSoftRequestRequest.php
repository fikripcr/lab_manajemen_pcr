<?php
namespace App\Http\Requests\Lab;

use Illuminate\Foundation\Http\FormRequest;

class PeriodSoftRequestRequest extends FormRequest
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
            'semester_id'  => 'required|exists:lab_semesters,semester_id',
            'nama_periode' => 'required|string|max:191',
            'start_date'   => 'required|date',
            'end_date'     => 'required|date|after_or_equal:start_date',
            'is_active'    => 'boolean',
        ];
    }
}
