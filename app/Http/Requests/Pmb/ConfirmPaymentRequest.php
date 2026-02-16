<?php
namespace App\Http\Requests\Pmb;

use Illuminate\Foundation\Http\FormRequest;

class ConfirmPaymentRequest extends FormRequest
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
}
