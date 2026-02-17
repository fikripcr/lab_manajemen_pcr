<?php
namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;

class RiwayatJabStrukturalRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'org_unit_id' => 'required|exists:struktur_organisasi,orgunit_id',
            'tgl_awal'    => 'required|date',
            'tgl_akhir'   => 'nullable|date|after_or_equal:tgl_awal',
            'no_sk'       => 'nullable|string|max:100',
            'keterangan'  => 'nullable|string',
        ];
    }
}
