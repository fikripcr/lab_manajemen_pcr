<?php
namespace App\Http\Requests\Pemutu;

use App\Http\Requests\BaseRequest;

class RtpRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ami_rtp_isi'             => ['required', 'string'],
            'ami_rtp_tgl_pelaksanaan' => ['required', 'date'],
        ];
    }

    public function attributes(): array
    {
        return [
            'ami_rtp_isi'             => 'Rencana Tindakan Perbaikan',
            'ami_rtp_tgl_pelaksanaan' => 'Tanggal Pelaksanaan',
        ];
    }
}
