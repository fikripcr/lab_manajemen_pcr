<?php
namespace App\Http\Requests\Hr;

use App\Http\Requests\BaseRequest;

class RiwayatPenugasanRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'org_unit_id' => 'required|exists:struktur_organisasi,orgunit_id',
            'tgl_mulai'   => 'required|date',
            'tgl_selesai' => 'nullable|date|after_or_equal:tgl_mulai',
            'no_sk'       => 'nullable|string|max:100',
            'tgl_sk'      => 'nullable|date',
            'keterangan'  => 'nullable|string',
        ];
    }
}
