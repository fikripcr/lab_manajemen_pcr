<?php
namespace App\Http\Requests\Pmb;

use Illuminate\Foundation\Http\FormRequest;

class StoreProdiRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $prodiId = $this->prodi ? $this->prodi->id : null;

        return [
            'kode_prodi' => 'required|string|max:50|unique:pmb_prodi,kode_prodi,' . $prodiId,
            'nama_prodi' => 'required|string|max:255',
            'fakultas'   => 'nullable|string|max:255',
            'kuota_umum' => 'required|integer|min:0',
        ];
    }
}
