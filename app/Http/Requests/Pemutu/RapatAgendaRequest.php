<?php
namespace App\Http\Requests\Pemutu;

use Illuminate\Foundation\Http\FormRequest;

class RapatAgendaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'rapat_id'     => 'required|exists:rapats,rapat_id', // Note: Using table name from migration/model
            'judul_agenda' => 'required|string|max:250',
            'isi'          => 'required|string',
            'seq'          => 'required|integer',
        ];
    }
}
