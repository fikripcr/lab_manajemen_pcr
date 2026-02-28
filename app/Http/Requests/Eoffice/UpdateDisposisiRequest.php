<?php
namespace App\Http\Requests\Eoffice;

use App\Http\Requests\BaseRequest;

class UpdateDisposisiRequest extends BaseRequest
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
