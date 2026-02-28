<?php
namespace App\Http\Requests\Eoffice;

use App\Http\Requests\BaseRequest;

class UpdateIsianInfoRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'info_tambahan' => 'nullable|string',
        ];
    }
}
