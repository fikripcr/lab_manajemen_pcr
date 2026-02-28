<?php
namespace App\Http\Requests\Event;

use App\Http\Requests\BaseRequest;

class RapatAgendaRequest extends BaseRequest
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

    public function attributes(): array
    {
        return [
            'rapat_id'     => 'Rapat',
            'judul_agenda' => 'Judul Agenda',
            'isi'          => 'Isi Agenda',
            'seq'          => 'Urutan',
        ];
    }
}
