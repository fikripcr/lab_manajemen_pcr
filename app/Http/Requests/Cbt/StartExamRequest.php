<?php
namespace App\Http\Requests\Cbt;

use App\Http\Requests\BaseRequest;

class StartExamRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            // Meta-validation if needed, e.g. checking if exam window is still open
        ];
    }
}
