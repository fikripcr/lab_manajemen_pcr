<?php

namespace App\Http\Requests\Event;

use App\Http\Requests\BaseRequest;

class RapatPesertaRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'rapat_id' => 'required|exists:event_rapat,rapat_id',
            'user_id' => 'required|exists:users,id',
            'jabatan' => 'required|string|max:100',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('rapat_id')) {
            $this->merge([
                'rapat_id' => decryptIdIfEncrypted($this->rapat_id),
            ]);
        }
        if ($this->has('user_id')) {
            $this->merge([
                'user_id' => decryptIdIfEncrypted($this->user_id),
            ]);
        }
    }

    public function attributes(): array
    {
        return [
            'rapat_id' => 'Rapat',
            'user_id' => 'Peserta Rapat',
            'jabatan' => 'Jabatan',
        ];
    }
}
