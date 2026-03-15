<?php
namespace App\Http\Requests\Event;

use App\Http\Requests\BaseRequest;

class RapatAgendaRequest extends BaseRequest
{

    public function rules(): array
    {
        return [
            'rapat_id'     => 'sometimes|exists:event_rapat,rapat_id',
            'judul_agenda' => 'required|string|max:250',
            'isi'          => 'nullable|string',
            'seq'          => 'nullable|integer',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('rapat_id')) {
            $this->merge([
                'rapat_id' => decryptIdIfEncrypted($this->rapat_id),
            ]);
        }
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
