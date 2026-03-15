<?php
namespace App\Http\Requests\Pemutu;

use App\Http\Requests\BaseRequest;

class PtpRequest extends BaseRequest
{

    public function rules(): array
    {
        return [
            'ed_ptp_isi' => ['required', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'ed_ptp_isi' => 'Pelaksanaan Tindakan Perbaikan',
        ];
    }
}
