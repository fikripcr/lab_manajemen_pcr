<?php
namespace App\Http\Requests\Hr;

use App\Http\Requests\BaseRequest;

class RiwayatStatAktifitasRequest extends BaseRequest
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
            'keterangan'         => 'nullable|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'statusaktifitas_id' => 'Status Aktifitas',
            'tmt'                => 'TMT',
            'no_sk'              => 'Nomor SK',
            'keterangan'         => 'Keterangan',
        ];
    }
}
