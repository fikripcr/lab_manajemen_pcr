<?php
namespace App\Http\Requests\Hr;

use App\Http\Requests\BaseRequest;

class StatusAktifitasRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'kode_status' => 'required|string|max:5',
            'is_active'   => 'boolean',
        ];
    }

    public function attributes(): array
    {
        return [
            'kode_status' => 'Kode Status',
            'nama_status' => 'Nama Status',
            'is_active'   => 'Status Aktif',
        ];
    }
}
