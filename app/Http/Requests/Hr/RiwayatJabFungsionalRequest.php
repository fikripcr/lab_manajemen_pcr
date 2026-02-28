<?php
namespace App\Http\Requests\Hr;

use App\Http\Requests\BaseRequest;

class RiwayatJabFungsionalRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'jabfungsional_id' => 'required|exists:hr_jabatan_fungsional,jabfungsional_id',
            'tmt'              => 'required|date',
            'no_sk'            => 'nullable|string|max:100',
            'no_sk_internal'   => 'nullable|string|max:100',
        ];
    }
}
