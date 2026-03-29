<?php

namespace App\Http\Requests\Hr;

use App\Http\Requests\BaseRequest;

class PresensiLocationRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'longitude' => 'required|numeric',
        ];
    }

    public function attributes(): array
    {
        return [
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
        ];
    }
}
