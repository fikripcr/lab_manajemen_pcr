<?php
namespace App\Http\Requests\Pmb;

use Illuminate\Foundation\Http\FormRequest;

class StoreRegistrationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'periode_id' => decryptId($this->periode_id),
            'jalur_id'   => decryptId($this->jalur_id),
        ]);
    }

    public function rules()
    {
        return [
            'periode_id'       => 'required|exists:pmb_periode,id',
            'jalur_id'         => 'required|exists:pmb_jalur,id',
            'pilihan_prodi'    => 'required|array|min:1|max:2',
            'pilihan_prodi.*'  => 'required|exists:pmb_prodi,id',
            'nik'              => 'required|string|size:16',
            'no_hp'            => 'required|string|max:20',
            'tempat_lahir'     => 'required|string|max:255',
            'tanggal_lahir'    => 'required|date',
            'jenis_kelamin'    => 'required|in:L,P',
            'alamat_lengkap'   => 'required|string',
            'asal_sekolah'     => 'required|string|max:255',
            'nama_ibu_kandung' => 'required|string|max:255',
        ];
    }
}
