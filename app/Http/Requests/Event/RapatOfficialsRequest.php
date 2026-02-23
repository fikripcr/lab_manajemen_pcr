<?php
namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;

class RapatOfficialsRequest extends FormRequest
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
}
