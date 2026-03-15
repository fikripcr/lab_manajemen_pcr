<?php
namespace App\Http\Requests\Pemutu;

use App\Http\Requests\BaseRequest;

class RapatEntitasRequest extends BaseRequest
{
    /**
     */

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'rapat_id'   => 'required|exists:rapats,rapat_id',
            'model'      => 'required|string|max:50',
            'keterangan' => 'nullable|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'rapat_id'   => 'Rapat',
            'model'      => 'Model Entitas',
            'model_id'   => 'ID Entitas',
            'keterangan' => 'Keterangan',
        ];
    }
}
