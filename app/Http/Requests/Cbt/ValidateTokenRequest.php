<?php
namespace App\Http\Requests\Cbt;

use Illuminate\Foundation\Http\FormRequest;

class ValidateTokenRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'token_ujian' => 'required|string|size:6',
        ];
    }
}
