<?php

namespace App\Http\Requests\Hr;

use App\Http\Requests\BaseRequest;

class PresensiRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
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
