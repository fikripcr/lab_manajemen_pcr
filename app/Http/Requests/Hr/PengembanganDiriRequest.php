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
            'tahun'              => 'required|integer|digits:4',
            'keterangan'         => 'nullable|string',
        ];
    }
}
