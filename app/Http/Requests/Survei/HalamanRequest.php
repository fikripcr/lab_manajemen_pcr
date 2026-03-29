<?php

namespace App\Http\Requests\Survei;

use App\Http\Requests\BaseRequest;

class HalamanRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'judul_halaman' => 'nullable|string|max:255',
            'deskripsi_halaman' => 'nullable|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'judul_halaman' => 'Judul Halaman',
            'deskripsi_halaman' => 'Deskripsi Halaman',
        ];
    }
}
