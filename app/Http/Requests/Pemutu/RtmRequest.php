<?php
namespace App\Http\Requests\Pemutu;

use App\Http\Requests\BaseRequest;

class RtmRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'ketua_user_id'   => decryptIdIfEncrypted($this->ketua_user_id),
            'notulen_user_id' => decryptIdIfEncrypted($this->notulen_user_id),
        ]);
    }

    public function rules(): array
    {
        return [
            'tgl_rapat'       => ['required', 'date'],
            'waktu_mulai'     => ['required'],
            'waktu_selesai'   => ['required'],
            'tempat_rapat'    => ['required', 'string', 'max:200'],
            'ketua_user_id'   => ['nullable', 'exists:users,id'],
            'notulen_user_id' => ['nullable', 'exists:users,id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'tgl_rapat'       => 'Tanggal Rapat',
            'waktu_mulai'     => 'Waktu Mulai',
            'waktu_selesai'   => 'Waktu Selesai',
            'tempat_rapat'    => 'Tempat Rapat',
            'ketua_user_id'   => 'Ketua Rapat',
            'notulen_user_id' => 'Notulen',
        ];
    }
}
