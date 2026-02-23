<?php
namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;

class RapatBulkPesertaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_ids'   => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'jabatan'    => 'nullable|string|max:255',
        ];
    }
}
