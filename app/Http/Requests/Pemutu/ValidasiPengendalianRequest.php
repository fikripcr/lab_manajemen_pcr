<?php

namespace App\Http\Requests\Pemutu;

use App\Http\Requests\BaseRequest;

class ValidasiPengendalianRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'pengend_status_atsn' => ['required', 'string', 'in:tetap,penyesuaian,ditingkatkan,nonaktif'],
            'pengend_analisis_atsn' => ['nullable', 'string'],
            'pengend_important_matrix_atsn' => ['required', 'string', 'in:important,not_important'],
            'pengend_urgent_matrix_atsn' => ['required', 'string', 'in:urgent,not_urgent'],
        ];
    }

    public function attributes(): array
    {
        return [
            'pengend_status_atsn' => 'Status Validasi',
            'pengend_analisis_atsn' => 'Catatan Atasan',
            'pengend_important_matrix_atsn' => 'Matrix Important (Final)',
            'pengend_urgent_matrix_atsn' => 'Matrix Urgent (Final)',
        ];
    }
}
