<?php

namespace App\Http\Requests\Pmb;

use App\Http\Requests\BaseRequest;

class UpdateStatusRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => 'required|string',
            'keterangan' => 'nullable|string|max:1000',
        ];
    }

    public function attributes(): array
    {
        return [
            'status' => 'Status',
            'keterangan' => 'Keterangan',
        ];
    }
}
