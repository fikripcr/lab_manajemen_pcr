<?php
namespace App\Http\Requests\Pemutu;

use App\Http\Requests\BaseRequest;

class PemantauanRequest extends BaseRequest
{

    public function rules(): array
    {
        return [
            'judul_kegiatan'   => ['required', 'string', 'max:255'],
            'tgl_rapat'        => ['required', 'date'],
            'waktu_mulai'      => ['required'],
            'waktu_selesai'    => ['required'],
            'tempat_rapat'     => ['required', 'string'],
            'ketua_user_id'    => ['nullable'],
            'notulen_user_id'  => ['nullable'],
            'keterangan'       => ['nullable', 'string'],
            'indikorgunit_ids' => ['nullable', 'array'],
        ];
    }

    public function attributes(): array
    {
        return [
            'judul_kegiatan'   => 'Judul Kegiatan',
            'tgl_rapat'        => 'Tanggal Rapat',
            'waktu_mulai'      => 'Waktu Mulai',
            'waktu_selesai'    => 'Waktu Selesai',
            'tempat_rapat'     => 'Tempat Rapat',
            'ketua_user_id'    => 'Ketua Rapat',
            'notulen_user_id'  => 'Notulen',
            'keterangan'       => 'Keterangan',
            'indikorgunit_ids' => 'Indikator Terkait',
        ];
    }
}
