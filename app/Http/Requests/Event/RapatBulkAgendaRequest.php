<?php

namespace App\Http\Requests\Event;

use App\Http\Requests\BaseRequest;

class RapatBulkAgendaRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'agendas'       => 'required|array',
            'agendas.*.isi' => 'nullable|string',
        ];
    }
}
