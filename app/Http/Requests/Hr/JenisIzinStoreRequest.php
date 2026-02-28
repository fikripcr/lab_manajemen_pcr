<?php

namespace App\Http\Requests\Hr;

use App\Http\Requests\BaseRequest;

class JenisIzinStoreRequest extends BaseRequest
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
            'nama' => 'required|string|max:50',
            'kategori' => 'nullable|string|max:10',
            'max_hari' => 'nullable|integer|min:1|max:365',
            'is_active' => 'nullable|boolean',
            'keterangan' => 'nullable|string|max:500',
        ];
    }

    public function attributes(): array
    {
        return [
            'nama'       => 'Nama Jenis Izin',
            'kategori'   => 'Kategori',
            'max_hari'   => 'Maksimal Hari',
            'is_active'  => 'Status Aktif',
            'keterangan' => 'Keterangan',
        ];
    }
}
