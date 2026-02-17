<?php
namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;

class EventTeamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'event_id'        => 'required|exists:events,event_id',
            'memberable_type' => 'nullable|string',
            'memberable_id'   => 'nullable|integer',
            'name'            => 'nullable|string|max:255',
            'role'            => 'nullable|string|max:100',
            'is_pic'          => 'boolean',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Nama Anggota',
            'role' => 'Jabatan/Peran',
        ];
    }
}
