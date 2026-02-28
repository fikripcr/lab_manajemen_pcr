<?php
namespace App\Http\Requests\Pemutu;

use App\Http\Requests\BaseRequest;

class PeriodeSpmiRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'periode'            => 'required|integer',
            'jenis_periode'      => 'required|string|max:20',
            'penetapan_awal'     => 'nullable|date',
            'penetapan_akhir'    => 'nullable|date',
            'ed_awal'            => 'nullable|date',
            'ed_akhir'           => 'nullable|date',
            'ami_awal'           => 'nullable|date',
            'ami_akhir'          => 'nullable|date',
            'pengendalian_awal'  => 'nullable|date',
            'pengendalian_akhir' => 'nullable|date',
            'peningkatan_awal'   => 'nullable|date',
            'peningkatan_akhir'  => 'nullable|date',
        ];
    }

    public function attributes(): array
    {
        return [
            'periode'            => 'Periode',
            'jenis_periode'      => 'Jenis Periode',
            'penetapan_awal'     => 'Penetapan Awal',
            'penetapan_akhir'    => 'Penetapan Akhir',
            'ed_awal'            => 'Evaluasi Diri Awal',
            'ed_akhir'           => 'Evaluasi Diri Akhir',
            'ami_awal'           => 'AMI Awal',
            'ami_akhir'          => 'AMI Akhir',
            'pengendalian_awal'  => 'Pengendalian Awal',
            'pengendalian_akhir' => 'Pengendalian Akhir',
            'peningkatan_awal'   => 'Peningkatan Awal',
            'peningkatan_akhir'  => 'Peningkatan Akhir',
        ];
    }
}
