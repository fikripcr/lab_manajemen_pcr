<?php
namespace App\Http\Requests\Eoffice;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDisposisiRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'seq'             => 'nullable|integer',
            'is_notify_email' => 'nullable|boolean',
            'text_ket'        => 'nullable|string',
            'action'          => 'nullable|string',
        ];
    }
}
