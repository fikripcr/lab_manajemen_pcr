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

    public function attributes(): array
    {
        return [
            'ami_hasil_akhir'          => 'Hasil Akhir AMI',
            'ami_hasil_temuan'         => 'Temuan AMI',
            'ami_hasil_temuan_sebab'   => 'Sebab Temuan AMI',
            'ami_hasil_temuan_akibat'  => 'Akibat Temuan AMI',
            'ami_hasil_temuan_rekom'   => 'Rekomendasi Temuan AMI',
            'tgl_ami'                  => 'Tanggal AMI',
            'org_unit_id'              => 'Unit Kerja',
        ];
    }
}
