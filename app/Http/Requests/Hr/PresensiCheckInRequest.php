<?php

namespace App\Http\Requests\Hr;

use App\Http\Requests\BaseRequest;

class PresensiCheckInRequest extends BaseRequest
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
            'latitude.required' => 'Latitude harus diisi.',
            'latitude.numeric' => 'Latitude harus berupa angka.',
            'longitude.required' => 'Longitude harus diisi.',
            'longitude.numeric' => 'Longitude harus berupa angka.',
            'address.string' => 'Address harus berupa string.',
            'address.max' => 'Address maksimal 500 karakter.',
            'lat_in.required' => 'Lokasi latitud tidak terdeteksi.',
            'lng_in.required' => 'Lokasi longitud tidak terdeteksi.',
            'photo.required'  => 'Foto wajib diambil.',
            'photo.image'     => 'Lampiran harus berupa gambar.',
        ]);
    }
}
