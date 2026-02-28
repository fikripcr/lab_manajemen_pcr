<?php
namespace App\Http\Requests\Hr;

use App\Http\Requests\BaseRequest;

class KeluargaRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nama'          => 'required|string|max:100',
            'hubungan'      => 'required|string|max:50',
            'jenis_kelamin' => 'required|in:L,P',
            'tgl_lahir'     => 'nullable|date',
            'alamat'        => 'nullable|string',
            'telp'          => 'nullable|string|max:20',
        ];
    }

    public function attributes(): array
    {
        return [
            'nama'          => 'Nama Keluarga',
            'hubungan'      => 'Hubungan Keluarga',
            'jenis_kelamin' => 'Jenis Kelamin',
            'tgl_lahir'     => 'Tanggal Lahir',
            'alamat'        => 'Alamat',
            'telp'          => 'No. Telepon',
        ];
    }
}
