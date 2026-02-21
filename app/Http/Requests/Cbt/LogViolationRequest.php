<?php
namespace App\Http\Requests\Cbt;

use Illuminate\Foundation\Http\FormRequest;

class LogViolationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'type'       => 'required|string',
            'keterangan' => 'nullable|string',
        ];
    }
}
