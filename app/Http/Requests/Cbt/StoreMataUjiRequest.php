<?php
namespace App\Http\Requests\Cbt;

use Illuminate\Foundation\Http\FormRequest;

class StoreMataUjiRequest extends FormRequest
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
}
