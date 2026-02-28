<?php

namespace App\Http\Requests\Pemutu;

use App\Http\Requests\BaseRequest;

class PengendalianRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pengend_status'            => ['required', 'string', 'in:tetap,penyesuaian,nonaktif'],
            'pengend_analisis'          => ['required', 'string'],
            'pengend_important_matrix'  => ['nullable', 'string', 'in:important,not_important'],
            'pengend_urgent_matrix'     => ['nullable', 'string', 'in:urgent,not_urgent'],
            'pengendalian_desc'         => ['required', 'string'],
            'tgl_target'                => ['required', 'date'],
        ];
    }

    public function attributes(): array
    {
        return [
            'pengend_status'           => 'Status Pengendalian',
            'pengend_analisis'         => 'Analisis Pengendalian',
            'pengend_important_matrix' => 'Matrix Important',
            'pengend_urgent_matrix'    => 'Matrix Urgent',
            'pengendalian_desc'        => 'Deskripsi Pengendalian',
            'tgl_target'               => 'Tanggal Target',
        ];
    }
}
