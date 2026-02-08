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
            'nama'      => 'required|string|max:255',
            'is_active' => 'boolean',
        ];
    }
}
