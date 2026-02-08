<?php
namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;

class StatusPegawaiRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'status'    => 'required|string|max:255',
            'is_active' => 'boolean',
        ];
    }
}
