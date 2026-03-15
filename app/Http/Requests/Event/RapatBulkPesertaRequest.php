<?php
namespace App\Http\Requests\Event;

use App\Http\Requests\BaseRequest;

class RapatBulkPesertaRequest extends BaseRequest
{

    public function rules(): array
    {
        return [
            'user_ids'   => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'jabatan'    => 'nullable|string|max:255',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('user_ids') && is_array($this->user_ids)) {
            $decryptedIds = array_map(function ($id) {
                return decryptIdIfEncrypted($id);
            }, $this->user_ids);

            $this->merge([
                'user_ids' => $decryptedIds,
            ]);
        }
    }

    public function attributes(): array
    {
        return [
            'user_ids'   => 'Peserta Rapat',
            'user_ids.*' => 'Peserta Rapat',
            'jabatan'    => 'Jabatan',
        ];
    }
}
