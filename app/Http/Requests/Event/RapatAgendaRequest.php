<?php
namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;

class RapatAgendaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'rapat_id'     => 'sometimes|exists:event_rapat,rapat_id',
            'judul_agenda' => 'required|string|max:250',
            'isi'          => 'nullable|string',
            'seq'          => 'nullable|integer',
        ];
    }
}
