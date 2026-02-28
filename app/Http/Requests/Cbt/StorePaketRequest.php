<?php
namespace App\Http\Requests\Cbt;

use App\Http\Requests\BaseRequest;

class StorePaketRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'is_acak_soal' => $this->has('is_acak_soal'),
            'is_acak_opsi' => $this->has('is_acak_opsi'),
        ]);
    }

    public function rules()
    {
        return [
            'nama_paket'         => 'required|string|max:255',
            'tipe_paket'         => 'required|in:PMB,Akademik',
            'total_durasi_menit' => 'required|integer|min:1',
            'kk_nilai_minimal'   => 'nullable|integer|min:0',
            'is_acak_soal'       => 'boolean',
            'is_acak_opsi'       => 'boolean',
        ];
    }
}
