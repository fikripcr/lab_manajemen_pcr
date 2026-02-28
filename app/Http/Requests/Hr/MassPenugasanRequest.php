<?php
namespace App\Http\Requests\Hr;

use App\Http\Requests\BaseRequest;

class MassPenugasanRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'org_unit_id' => 'required|exists:struktur_organisasi,orgunit_id',
            'pegawai_id'  => 'required|exists:pegawai,pegawai_id',
            'no_sk'       => 'nullable|string|max:100',
        ];
    }

    public function attributes(): array
    {
        return [
            'org_unit_id' => 'Unit Organisasi',
            'pegawai_id'  => 'Pegawai',
            'tgl_mulai'   => 'Tanggal Mulai',
            'no_sk'       => 'Nomor SK',
        ];
    }
}
