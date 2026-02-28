<?php
namespace App\Http\Requests\Hr;

use App\Http\Requests\BaseRequest;

class JabatanFungsionalRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'kode_jabatan'  => 'required|string|max:10',
            'jabfungsional' => 'required|string|max:50',
            'tunjangan'     => 'nullable|numeric',
            'is_active'     => 'boolean',
        ];
    }

    public function attributes(): array
    {
        return [
            'kode_jabatan'  => 'Kode Jabatan',
            'jabfungsional' => 'Jabatan Fungsional',
            'tunjangan'     => 'Tunjangan',
            'is_active'     => 'Status Aktif',
        ];
    }
}
