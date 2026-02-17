<?php
namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;

class RapatPesertaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'rapat_id' => 'required|exists:event_rapat,rapat_id',
            'user_id'  => 'required|exists:users,id',
            'jabatan'  => 'required|string|max:100',
        ];
    }
}
