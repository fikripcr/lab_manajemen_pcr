<?php
namespace App\Http\Requests\Cbt;

use App\Http\Requests\BaseRequest;

class SubmitExamRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            // No body required for simple submit
        ];
    }
}
