<?php

namespace App\Http\Requests\Pemutu;

use App\Http\Requests\BaseRequest;

class PegawaiRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'nama' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'orgunit_departemen_id' => 'nullable|exists:hr_struktur_organisasi,orgunit_id',
            'user_id' => 'nullable|exists:users,id',
        ];
    }

    public function attributes(): array
    {
        return [
            'nama' => 'Nama',
            'nip' => 'NIP',
            'email' => 'Email',
            'orgunit_departemen_id' => 'Unit Organisasi',
            'user_id' => 'User',
        ];
    }
}
