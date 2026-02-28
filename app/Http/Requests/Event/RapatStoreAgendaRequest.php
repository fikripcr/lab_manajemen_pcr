<?php

namespace App\Http\Requests\Event;

use App\Http\Requests\BaseRequest;

class RapatStoreAgendaRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'judul_agenda' => 'required|string|max:255',
        ];
    }

    public function attributes(): array
    {
        return [
            'judul_agenda' => 'Judul Agenda',
        ];
    }
}
