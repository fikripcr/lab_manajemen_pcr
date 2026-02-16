<?php
namespace App\Http\Requests\Cbt;

use Illuminate\Foundation\Http\FormRequest;

class StoreJadwalRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        if ($this->has('paket_id')) {
            $this->merge([
                'paket_id' => decryptId($this->paket_id),
            ]);
        }
    }

    public function rules()
    {
        return [
            'paket_id'      => 'required|exists:cbt_paket_ujian,id',
            'nama_kegiatan' => 'required|string|max:255',
            'waktu_mulai'   => 'required|date',
            'waktu_selesai' => 'required|date|after:waktu_mulai',
        ];
    }
}
