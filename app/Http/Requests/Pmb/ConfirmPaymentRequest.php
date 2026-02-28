<?php
namespace App\Http\Requests\Pmb;

use App\Http\Requests\BaseRequest;

class ConfirmPaymentRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'pendaftaran_id' => decryptId($this->pendaftaran_id),
        ]);
    }

    public function rules()
    {
        return [
            'pendaftaran_id' => 'required|exists:pmb_pendaftaran,id',
            'bukti_bayar'    => 'required|file|image|max:2048',
            'bank_asal'      => 'required|string',
            'nama_pengirim'  => 'required|string',
            'tanggal_bayar'  => 'required|date',
        ];
    }

    public function attributes(): array
    {
        return [
            'pendaftaran_id' => 'Pendaftaran',
            'bukti_bayar'    => 'Bukti Pembayaran',
            'bank_asal'      => 'Bank Asal',
            'nama_pengirim'  => 'Nama Pengirim',
            'tanggal_bayar'  => 'Tanggal Pembayaran',
        ];
    }
}
