<?php
namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;

class RapatAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'attendance'               => 'required|array',
            'attendance.*.status'      => 'nullable|in:hadir,izin,sakit,alpa',
            'attendance.*.waktu_hadir' => 'nullable|date_format:H:i',
        ];
    }
}
