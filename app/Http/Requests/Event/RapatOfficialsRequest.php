<?php
namespace App\Http\Requests\Event;

use App\Http\Requests\BaseRequest;

class RapatOfficialsRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ketua_user_id'   => 'required|exists:users,id',
            'notulen_user_id' => 'required|exists:users,id',
        ];
    }

    public function attributes(): array
    {
        return [
            'ketua_user_id'   => 'Ketua Rapat',
            'notulen_user_id' => 'Notulen Rapat',
        ];
    }
}
