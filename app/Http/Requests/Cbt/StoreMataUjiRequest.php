<?php
namespace App\Http\Requests\Cbt;

use App\Http\Requests\BaseRequest;

class StoreMataUjiRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nama_mata_uji' => 'required|string|max:255',
            'tipe'          => 'required|in:PMB,Akademik',
            'deskripsi'     => 'nullable|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'nama_mata_uji' => 'Nama Mata Uji',
            'tipe'          => 'Tipe Mata Uji',
            'deskripsi'     => 'Deskripsi Mata Uji',
        ];
    }
}
