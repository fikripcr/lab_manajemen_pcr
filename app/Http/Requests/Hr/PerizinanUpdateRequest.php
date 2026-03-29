<?php

namespace App\Http\Requests\Hr;

use App\Http\Requests\BaseRequest;

class PerizinanUpdateRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'jenisizin_id' => 'required|exists:hr_jenis_izin,jenisizin_id',
            'pengusul' => 'required|exists:hr_pegawai,pegawai_id',
            'pekerjaan_ditinggalkan' => 'nullable|string|max:500',
            'keterangan' => 'nullable|string',
            'alamat_izin' => 'nullable|string',
            'tgl_awal' => 'required|date',
            'tgl_akhir' => 'required|date|after_or_equal:tgl_awal',
            'jam_akhir' => 'nullable',
        ];
    }

    public function attributes(): array
    {
        return [
            'jenisizin_id' => 'Jenis Izin',
            'pengusul' => 'Pengusul',
            'pekerjaan_ditinggalkan' => 'Pekerjaan Ditinggalkan',
            'keterangan' => 'Keterangan',
            'alamat_izin' => 'Alamat Izin',
            'tgl_awal' => 'Tanggal Awal',
            'tgl_akhir' => 'Tanggal Akhir',
            'jam_awal' => 'Jam Awal',
            'jam_akhir' => 'Jam Akhir',
        ];
    }
}
