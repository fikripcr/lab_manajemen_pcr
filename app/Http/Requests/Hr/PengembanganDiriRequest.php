<?php
namespace App\Http\Requests\Hr;

use App\Http\Requests\BaseRequest;

class PengembanganDiriRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'jenis_kegiatan'     => 'required|string|max:100',
            'nama_kegiatan'      => 'required|string|max:255',
            'nama_penyelenggara' => 'nullable|string|max:255',
            'peran'              => 'nullable|string|max:100',
            'tgl_mulai'          => 'required|date',
            'tgl_selesai'        => 'nullable|date|after_or_equal:tgl_mulai',
            'keterangan'         => 'nullable|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'jenis_kegiatan'     => 'Jenis Kegiatan',
            'nama_kegiatan'      => 'Nama Kegiatan',
            'nama_penyelenggara' => 'Penyelenggara',
            'peran'              => 'Peran',
            'tgl_mulai'          => 'Tanggal Mulai',
            'tgl_selesai'        => 'Tanggal Selesai',
            'tahun'              => 'Tahun',
            'keterangan'         => 'Keterangan',
        ];
    }
}
