<?php
namespace App\Http\Requests\Hr;

use App\Http\Requests\BaseRequest;

class EndPenugasanRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'tgl_selesai' => 'required|date',
        ];
    }
}
