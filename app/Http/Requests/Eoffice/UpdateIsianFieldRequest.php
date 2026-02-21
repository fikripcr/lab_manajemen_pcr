<?php
namespace App\Http\Requests\Eoffice;

use Illuminate\Foundation\Http\FormRequest;

class UpdateIsianFieldRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'is_required'         => 'boolean',
            'is_show_on_validasi' => 'boolean',
            'fill_by'             => 'nullable|string',
        ];
    }
}
