<?php

namespace App\Http\Requests\Pemutu;

use App\Http\Requests\BaseRequest;

class AmiRequest extends BaseRequest
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
        return array_merge(parent::messages(), [
            'tgl_ami.required'   => 'Tanggal AMI harus diisi.',
            'org_unit_id.required' => 'Unit Kerja harus dipilih.',
        ]);
    }
}
