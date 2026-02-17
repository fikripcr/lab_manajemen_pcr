<?php
namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;

class MassPenugasanRequest extends FormRequest
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
            'tgl_mulai'   => 'required|date',
            'no_sk'       => 'nullable|string|max:100',
        ];
    }
}
