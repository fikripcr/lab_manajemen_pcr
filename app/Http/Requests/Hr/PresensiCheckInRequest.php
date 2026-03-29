<?php

namespace App\Http\Requests\Hr;

use App\Http\Requests\BaseRequest;

class PresensiCheckInRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'address' => 'nullable|string|max:500',
        ];
    }

    public function attributes(): array
    {
        return [
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'address' => 'Alamat',
        ];
    }
}
