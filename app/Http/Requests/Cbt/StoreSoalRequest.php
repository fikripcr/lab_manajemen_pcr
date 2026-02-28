<?php
namespace App\Http\Requests\Cbt;

use App\Http\Requests\BaseRequest;

class StoreSoalRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $mu = $this->route('mata_uji');
        if ($mu instanceof \App\Models\Cbt\MataUji) {
            $this->merge(['mata_uji_id' => $mu->mata_uji_id]);
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
            'kunci_jawaban'     => 'required_unless:tipe_soal,Esai',
        ];
    }
}
