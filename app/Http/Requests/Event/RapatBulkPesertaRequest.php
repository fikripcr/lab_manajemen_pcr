<?php
namespace App\Http\Requests\Event;

use App\Http\Requests\BaseRequest;

class RapatBulkPesertaRequest extends BaseRequest
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
