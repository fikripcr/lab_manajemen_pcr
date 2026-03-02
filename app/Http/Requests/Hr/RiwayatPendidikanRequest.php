<?php
namespace App\Http\Requests\Hr;

use App\Http\Requests\BaseRequest;

class RiwayatPendidikanRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'jenjang_pendidikan' => 'required|string|max:10',
            'nama_pt'            => 'required|string|max:100',
            'bidang_ilmu'        => 'nullable|string|max:100',
            'tgl_ijazah'         => 'required|date',
            'kotaasal_pt'        => 'nullable|string|max:100',
            'kodenegara_pt'      => 'nullable|string|max:100',
        ];
    }

    public function attributes(): array
    {
        return [
            'jenjang_pendidikan' => 'Jenjang Pendidikan',
            'nama_pt'            => 'Nama Perguruan Tinggi',
            'bidang_ilmu'        => 'Bidang Ilmu',
            'tgl_ijazah'         => 'Tanggal Ijazah',
            'kotaasal_pt'        => 'Kota Asal Perguruan Tinggi',
            'kodenegara_pt'      => 'Negara Asal Perguruan Tinggi',
        ];
    }
}
