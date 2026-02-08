<?php
namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;

class RiwayatInpassingRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'gol_inpassing_id' => 'required|exists:hr_golongan_inpassing,gol_inpassing_id',
            'tmt'              => 'required|date',
            'no_sk'            => 'required|string|max:50',
            'tgl_sk'           => 'required|date',
            'file_sk'          => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'angka_kredit'     => 'nullable|numeric',
            'masa_kerja_tahun' => 'nullable|integer',
            'masa_kerja_bulan' => 'nullable|integer',
            'gaji_pokok'       => 'nullable|numeric',
            'keterangan'       => 'nullable|string',
        ];
    }
}
