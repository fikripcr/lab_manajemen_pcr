<?php
namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;

class KeluargaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nama'          => 'required|string|max:100',
            'hubungan'      => 'required|string|max:50',
            'jenis_kelamin' => 'required|in:L,P',
            'tgl_lahir'     => 'nullable|date',
            'alamat'        => 'nullable|string',
            'telp'          => 'nullable|string|max:20',
        ];
    }
}
