<?php
namespace App\Http\Requests\Hr;

use App\Http\Requests\BaseRequest;

class RiwayatInpassingRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'gol_inpassing_id' => 'required|exists:hr_golongan_inpassing,gol_inpassing_id',
            'tmt'              => 'required|date',
            'no_sk'            => 'required|string|max:50',
            'tgl_sk'           => 'required|date',
            'file_sk'          => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'angka_kredit'     => 'nullable|numeric',
            'masa_kerja_tahun' => 'nullable|integer',
            'masa_kerja_bulan' => 'nullable|integer',
            'keterangan'       => 'nullable|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'gol_inpassing_id' => 'Golongan Inpassing',
            'tmt'              => 'TMT',
            'no_sk'            => 'Nomor SK',
            'tgl_sk'           => 'Tanggal SK',
            'file_sk'          => 'File SK',
            'angka_kredit'     => 'Angka Kredit',
            'masa_kerja_tahun' => 'Masa Kerja (Tahun)',
            'masa_kerja_bulan' => 'Masa Kerja (Bulan)',
            'gaji_pokok'       => 'Gaji Pokok',
            'keterangan'       => 'Keterangan',
        ];
    }
}
