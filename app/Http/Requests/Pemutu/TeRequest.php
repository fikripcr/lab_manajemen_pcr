<?php
namespace App\Http\Requests\Pemutu;

use App\Http\Requests\BaseRequest;

class TeRequest extends BaseRequest
{

    public function rules(): array
    {
        return [
            'ami_te_isi' => ['required', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'ami_te_isi' => 'Tinjauan Efektivitas',
        ];
    }
}
