<?php

namespace App\Http\Requests\Pemutu;

use Illuminate\Foundation\Http\FormRequest;

class PengendalianRequest extends FormRequest
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
        ];
    }

    public function messages(): array
    {
        return [
            'pengend_status.required'   => 'Status pengendalian wajib dipilih.',
            'pengend_status.in'         => 'Status tidak valid. Pilih: Tetap, Penyesuaian, atau Nonaktifkan.',
            'pengend_analisis.required' => 'Deskripsi analisis wajib diisi.',
        ];
    }
}
