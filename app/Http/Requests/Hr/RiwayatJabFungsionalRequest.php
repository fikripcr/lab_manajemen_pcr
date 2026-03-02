<?php
namespace App\Http\Requests\Hr;

use App\Http\Requests\BaseRequest;

class RiwayatJabFungsionalRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'jabfungsional_id' => 'required|exists:hr_jabatan_fungsional,jabfungsional_id',
            'tmt'              => 'required|date',
            'no_sk_internal'   => 'nullable|string|max:100',
        ];
    }

    public function attributes(): array
    {
        return [
            'jabfungsional_id' => 'Jabatan Fungsional',
            'tmt'              => 'TMT',
            'no_sk'            => 'Nomor SK',
            'no_sk_internal'   => 'Nomor SK Internal',
        ];
    }
}
