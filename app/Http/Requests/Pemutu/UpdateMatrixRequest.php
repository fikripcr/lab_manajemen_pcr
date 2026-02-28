<?php

namespace App\Http\Requests\Pemutu;

use App\Http\Requests\BaseRequest;

class UpdateMatrixRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pengend_important_matrix' => ['nullable', 'string', 'in:important,not_important'],
            'pengend_urgent_matrix'    => ['nullable', 'string', 'in:urgent,not_urgent'],
        ];
    }
}
