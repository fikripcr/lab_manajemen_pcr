<?php
namespace App\Http\Requests\Hr;

use App\Http\Requests\BaseRequest;

class RiwayatStrukturalRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'org_unit_id'    => 'required|exists:struktur_organisasi,orgunit_id',
            'tgl_awal'       => 'required|date',
            'tgl_akhir'      => 'nullable|date|after_or_equal:tgl_awal',
            'no_sk'          => 'nullable|string|max:191',
            'tgl_pengesahan' => 'nullable|date',
            'keterangan'     => 'nullable|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'org_unit_id'    => 'Unit Organisasi',
            'tgl_awal'       => 'Tanggal Awal',
            'tgl_akhir'      => 'Tanggal Akhir',
            'no_sk'          => 'Nomor SK',
            'tgl_pengesahan' => 'Tanggal Pengesahan',
            'keterangan'     => 'Keterangan',
        ];
    }
}
