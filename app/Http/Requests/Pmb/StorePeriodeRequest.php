<?php
namespace App\Http\Requests\Pmb;

use App\Http\Requests\BaseRequest;

class StorePeriodeRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nama_periode'    => 'required|string|max:255',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'is_aktif'        => 'nullable|boolean',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'is_aktif' => $this->has('is_aktif'),
        ]);
    }

    public function attributes(): array
    {
        return [
            'nama_periode'    => 'Nama Periode',
            'tanggal_mulai'   => 'Tanggal Mulai',
            'tanggal_selesai' => 'Tanggal Selesai',
            'is_aktif'        => 'Status Aktif',
        ];
    }
}
