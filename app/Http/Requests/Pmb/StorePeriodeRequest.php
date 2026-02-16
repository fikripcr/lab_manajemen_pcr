<?php
namespace App\Http\Requests\Pmb;

use Illuminate\Foundation\Http\FormRequest;

class StorePeriodeRequest extends FormRequest
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
}
