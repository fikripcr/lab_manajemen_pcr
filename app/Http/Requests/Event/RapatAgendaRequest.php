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
            'rapat_id'     => 'required|exists:event_rapat,rapat_id',
            'judul_agenda' => 'required|string|max:250',
            'isi'          => 'required|string',
            'seq'          => 'required|integer',
        ];
    }
}
