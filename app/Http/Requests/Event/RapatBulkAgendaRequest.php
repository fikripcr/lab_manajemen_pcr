<?php
namespace App\Http\Requests\Event;

use App\Http\Requests\BaseRequest;

class RapatBulkAgendaRequest extends BaseRequest
{

    public function rules(): array
    {
        return [
            'agendas'       => 'required|array',
            'agendas.*.isi' => 'nullable|string',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('agendas') && is_array($this->agendas)) {
            $decryptedAgendas = [];
            foreach ($this->agendas as $id => $data) {
                $decryptedId                    = decryptIdIfEncrypted($id);
                $decryptedAgendas[$decryptedId] = $data;
            }

            $this->merge([
                'agendas' => $decryptedAgendas,
            ]);
        }
    }

    public function attributes(): array
    {
        return [
            'agendas'       => 'Agenda Rapat',
            'agendas.*.isi' => 'Isi Agenda',
        ];
    }
}
