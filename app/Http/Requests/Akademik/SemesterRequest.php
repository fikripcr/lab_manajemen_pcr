<?php
namespace App\Http\Requests\Akademik;

use App\Http\Requests\BaseRequest;

class SemesterRequest extends BaseRequest
{
    /**
     */

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $semesterId = $this->route('semester'); // Get the semester ID from the route for update operations

        return [
            'tahun_ajaran' => 'required|string|max:20',
            'semester'     => 'required|integer|in:1,2',
            'start_date'   => 'required|date',
            'is_active'    => 'boolean',
        ];
    }

    public function attributes(): array
    {
        return [
            'tahun_ajaran' => 'Tahun Ajaran',
            'semester'     => 'Semester',
            'start_date'   => 'Tanggal Mulai',
            'end_date'     => 'Tanggal Selesai',
            'is_active'    => 'Status Aktif',
        ];
    }
}
