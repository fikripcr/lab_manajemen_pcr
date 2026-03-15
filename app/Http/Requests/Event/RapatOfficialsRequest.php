<?php
namespace App\Http\Requests\Event;

use App\Http\Requests\BaseRequest;

class RapatOfficialsRequest extends BaseRequest
{

    public function rules(): array
    {
        return [
            'ketua_user_id'   => 'required|exists:users,id',
            'notulen_user_id' => 'required|exists:users,id',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'ketua_user_id'   => decryptIdIfEncrypted($this->ketua_user_id),
            'notulen_user_id' => decryptIdIfEncrypted($this->notulen_user_id),
        ]);
    }

    public function attributes(): array
    {
        return [
            'ketua_user_id'   => 'Ketua Rapat',
            'notulen_user_id' => 'Notulen Rapat',
        ];
    }
}
