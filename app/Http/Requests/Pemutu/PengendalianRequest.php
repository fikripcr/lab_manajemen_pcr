<?php
namespace App\Http\Requests\Pemutu;

use App\Http\Requests\BaseRequest;

class PengendalianRequest extends BaseRequest
{

    public function rules(): array
    {
        return [
            'pengend_status'           => ['required', 'string', 'in:tetap,penyesuaian,ditingkatkan,nonaktif'],
            'pengend_analisis'         => ['required', 'string'],
            'pengend_important_matrix' => ['nullable', 'string', 'in:important,not_important'],
            'pengend_urgent_matrix'    => ['nullable', 'string', 'in:urgent,not_urgent'],
            
            // Superior review columns (optional, processed in service)
            'pengend_status_atsn'           => ['nullable', 'string', 'in:tetap,penyesuaian,ditingkatkan,nonaktif'],
            'pengend_analisis_atsn'         => ['nullable', 'string'],
            'pengend_important_matrix_atsn' => ['nullable', 'string', 'in:important,not_important'],
            'pengend_urgent_matrix_atsn'    => ['nullable', 'string', 'in:urgent,not_urgent'],
        ];
    }

    public function attributes(): array
    {
        return [
            'pengend_status'           => 'Status Pengendalian',
            'pengend_analisis'         => 'Analisis Pengendalian',
            'pengend_important_matrix' => 'Matrix Important',
            'pengend_urgent_matrix'    => 'Matrix Urgent',
        ];
    }
}
