<?php
namespace App\Http\Requests\Event;

use App\Http\Requests\BaseRequest;

class RapatEntitasRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'rapat_id'   => 'required|exists:event_rapat,rapat_id',
            'model'      => 'required|string|max:50',
            'model_id'   => 'required|integer',
            'keterangan' => 'nullable|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'rapat_id'   => 'Rapat',
            'model'      => 'Entitas',
            'model_id'   => 'ID Entitas',
            'keterangan' => 'Keterangan',
        ];
    }
}
