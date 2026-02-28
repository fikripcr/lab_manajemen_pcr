<?php
namespace App\Http\Requests\Eoffice;

use App\Http\Requests\BaseRequest;

class UpdateIsianFieldRequest extends BaseRequest
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
