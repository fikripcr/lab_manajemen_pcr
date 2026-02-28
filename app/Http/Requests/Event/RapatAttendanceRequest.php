<?php
namespace App\Http\Requests\Event;

use App\Http\Requests\BaseRequest;

class RapatAttendanceRequest extends BaseRequest
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

    public function attributes(): array
    {
        return [
            'attendance'               => 'Kehadiran',
            'attendance.*.status'      => 'Status Kehadiran',
            'attendance.*.waktu_hadir' => 'Waktu Hadir',
        ];
    }
}
