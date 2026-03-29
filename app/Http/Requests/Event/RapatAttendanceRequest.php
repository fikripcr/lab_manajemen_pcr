<?php

namespace App\Http\Requests\Event;

use App\Http\Requests\BaseRequest;

class RapatAttendanceRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'attendance' => 'required|array',
            'attendance.*.status' => 'nullable|in:hadir,izin,sakit,alpa',
            'attendance.*.waktu_hadir' => 'nullable|date_format:H:i',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('attendance') && is_array($this->attendance)) {
            $decryptedAttendance = [];
            foreach ($this->attendance as $id => $data) {
                $decryptedId = decryptIdIfEncrypted($id);
                $decryptedAttendance[$decryptedId] = $data;
            }

            $this->merge([
                'attendance' => $decryptedAttendance,
            ]);
        }
    }

    public function attributes(): array
    {
        return [
            'attendance' => 'Kehadiran',
            'attendance.*.status' => 'Status Kehadiran',
            'attendance.*.waktu_hadir' => 'Waktu Hadir',
        ];
    }
}
