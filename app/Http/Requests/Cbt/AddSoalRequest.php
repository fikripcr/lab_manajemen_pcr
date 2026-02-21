<?php
namespace App\Http\Requests\Cbt;

use Illuminate\Foundation\Http\FormRequest;

class AddSoalRequest extends FormRequest
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
}
