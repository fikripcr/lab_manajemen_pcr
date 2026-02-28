<?php
namespace App\Http\Requests\Pmb;

use App\Http\Requests\BaseRequest;

class StoreSesiRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        if ($this->has('periode_id')) {
            $this->merge([
                'periode_id' => decryptId($this->periode_id),
            ]);
        }
    }

    public function rules()
    {
        return [
            'periode_id'    => 'required|exists:pmb_periode,id',
            'nama_sesi'     => 'required|string|max:255',
            'waktu_mulai'   => 'required|date',
            'waktu_selesai' => 'required|date|after:waktu_mulai',
            'lokasi'        => 'nullable|string|max:255',
            'kuota'         => 'required|integer|min:1',
        ];
    }

    public function attributes(): array
    {
        return [
            'periode_id'    => 'Periode',
            'nama_sesi'     => 'Nama Sesi',
            'waktu_mulai'   => 'Waktu Mulai',
            'waktu_selesai' => 'Waktu Selesai',
            'lokasi'        => 'Lokasi Sesi',
            'kuota'         => 'Kuota Sesi',
        ];
    }
}
