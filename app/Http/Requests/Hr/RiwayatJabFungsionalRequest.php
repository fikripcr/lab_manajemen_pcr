<?php
namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;

class RiwayatJabFungsionalRequest extends FormRequest
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
