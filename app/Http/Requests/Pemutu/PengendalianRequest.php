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

    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'pengend_status.required'   => 'Status pengendalian wajib dipilih.',
            'pengend_status.in'         => 'Status tidak valid. Pilih: Tetap, Penyesuaian, atau Nonaktifkan.',
            'pengend_analisis.required' => 'Deskripsi analisis wajib diisi.',
            'pengendalian_desc.required' => 'Deskripsi Pengendalian wajib diisi.',
            'tgl_target.required'        => 'Tanggal Target wajib diisi.',
        ]);
    }
}
