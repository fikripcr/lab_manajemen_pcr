<?php
namespace App\Http\Requests\Lab;

use App\Http\Requests\BaseRequest;

class PeriodSoftRequestRequest extends BaseRequest
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
            'is_active'    => 'boolean',
        ];
    }

    public function attributes(): array
    {
        return [
            'semester_id'  => 'Semester',
            'nama_periode' => 'Nama Periode',
            'start_date'   => 'Tanggal Mulai',
            'end_date'     => 'Tanggal Selesai',
            'is_active'    => 'Status Aktif',
        ];
    }
}
