<?php
namespace App\Http\Requests\Cbt;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSoalRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        if ($this->has('mata_uji_id')) {
            $this->merge([
                'mata_uji_id' => decryptId($this->mata_uji_id),
            ]);
        }
    }

    public function rules()
    {
        return [
            'mata_uji_id'       => 'required|exists:cbt_mata_uji,id',
            'tipe_soal'         => 'required|in:Pilihan_Ganda,Esai,Benar_Salah',
            'konten_pertanyaan' => 'required|string',
            'tingkat_kesulitan' => 'required|in:Mudah,Sedang,Sulit',
            'opsi'              => 'required_if:tipe_soal,Pilihan_Ganda|array',
            'kunci_jawaban'     => 'required_if:tipe_soal,Pilihan_Ganda',
        ];
    }
}
