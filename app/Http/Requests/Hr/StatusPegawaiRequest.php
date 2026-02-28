<?php
namespace App\Http\Requests\Hr;

use App\Http\Requests\BaseRequest;

class StatusPegawaiRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'kode_status' => 'required|string|max:10',
            'nama_status' => 'required|string|max:50',
            'is_active'   => 'boolean',
        ];
    }

    public function attributes(): array
    {
        return [
            'kode_status' => 'Kode Status',
            'nama_status' => 'Nama Status',
            'organisasi'  => 'Organisasi',
            'is_active'   => 'Status Aktif',
        ];
    }
}
