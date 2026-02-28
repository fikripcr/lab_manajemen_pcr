<?php
namespace App\Http\Requests\Cbt;

use App\Http\Requests\BaseRequest;

class AddSoalRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'soal_ids'   => 'required|array',
            'soal_ids.*' => 'string', // hashids
        ];
    }

    public function attributes(): array
    {
        return [
            'soal_ids'   => 'Soal',
            'soal_ids.*' => 'ID Soal',
        ];
    }
}
