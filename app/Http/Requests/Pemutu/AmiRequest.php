<?php

namespace App\Http\Requests\Pemutu;

use Illuminate\Foundation\Http\FormRequest;

class AmiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ami_hasil_akhir'          => ['required', 'integer', 'in:0,1,2'],
            'ami_hasil_temuan'          => ['nullable', 'string'],
            'ami_hasil_temuan_sebab'    => ['required_if:ami_hasil_akhir,0', 'nullable', 'string'],
            'ami_hasil_temuan_akibat'   => ['required_if:ami_hasil_akhir,0', 'nullable', 'string'],
            'ami_hasil_temuan_rekom'    => ['required_if:ami_hasil_akhir,0', 'nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'ami_hasil_akhir.required' => 'Status hasil AMI wajib dipilih.',
            'ami_hasil_akhir.in'       => 'Status AMI harus salah satu dari: KTS, Terpenuhi, atau Terlampaui.',
        ];
    }
}
