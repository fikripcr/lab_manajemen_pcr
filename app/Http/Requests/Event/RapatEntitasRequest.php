<?php
namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;

class RapatEntitasRequest extends FormRequest
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
}
