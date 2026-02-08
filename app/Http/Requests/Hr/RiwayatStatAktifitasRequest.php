<?php
namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;

class RiwayatStatAktifitasRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'statusaktifitas_id' => 'required|exists:hr_status_aktifitas,statusaktifitas_id',
            'tmt'                => 'required|date',
            'no_sk'              => 'nullable|string|max:100',
            'keterangan'         => 'nullable|string',
        ];
    }
}
