<?php
namespace App\Http\Requests\Cbt;

use App\Http\Requests\BaseRequest;

class LogViolationRequest extends BaseRequest
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
