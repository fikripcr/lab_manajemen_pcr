<?php
namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;

class JabatanFungsionalRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'kode_jabatan'  => 'required|string|max:10',
            'jabfungsional' => 'required|string|max:50',
            'tunjangan'     => 'nullable|numeric',
            'is_active'     => 'boolean',
        ];
    }
}
