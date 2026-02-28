<?php

namespace App\Http\Requests\Hr;

use App\Http\Requests\BaseRequest;

class PresensiCheckOutRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

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

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'lat_out.required' => 'Lokasi latitud tidak terdeteksi.',
            'lng_out.required' => 'Lokasi longitud tidak terdeteksi.',
        ]);
    }
}
