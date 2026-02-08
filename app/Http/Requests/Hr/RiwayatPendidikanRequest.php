<?php
namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;

class RiwayatPendidikanRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'jenjang_pendidikan' => 'required|string|max:10',
            'nama_pt'            => 'required|string|max:100',
            'bidang_ilmu'        => 'nullable|string|max:100',
            'tgl_ijazah'         => 'required|date',
            'kotaasal_pt'        => 'nullable|string|max:100',
            'kodenegara_pt'      => 'nullable|string|max:100',
        ];
    }
}
