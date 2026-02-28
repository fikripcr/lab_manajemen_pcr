<?php
namespace App\Http\Requests\Cbt;

use App\Http\Requests\BaseRequest;

class ValidateTokenRequest extends BaseRequest
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

    public function attributes(): array
    {
        return [
            'token_ujian' => 'Token Ujian',
        ];
    }
}
