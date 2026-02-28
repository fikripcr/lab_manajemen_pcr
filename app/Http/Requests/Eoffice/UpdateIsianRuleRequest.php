<?php
namespace App\Http\Requests\Eoffice;

use App\Http\Requests\BaseRequest;

class UpdateIsianRuleRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'rule' => 'nullable|string',
        ];
    }
}
