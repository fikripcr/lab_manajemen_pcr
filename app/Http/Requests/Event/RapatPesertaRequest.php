<?php
namespace App\Http\Requests\Event;

use App\Http\Requests\BaseRequest;

class RapatPesertaRequest extends BaseRequest
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
