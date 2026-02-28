<?php
namespace App\Http\Requests\Event;

use App\Http\Requests\BaseRequest;

class EventRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'judul_Kegiatan'  => 'required|string|max:200',
            'jenis_Kegiatan'  => 'nullable|string|max:100',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'lokasi'          => 'nullable|string|max:200',
            'deskripsi'       => 'nullable|string',
            'pic_user_id'     => 'nullable|exists:users,id',
        ];
    }

    public function attributes(): array
    {
        return [
            'judul_Kegiatan'  => 'Judul Kegiatan',
            'jenis_Kegiatan'  => 'Jenis Kegiatan',
            'tanggal_mulai'   => 'Tanggal Mulai',
            'tanggal_selesai' => 'Tanggal Selesai',
            'lokasi'          => 'Lokasi',
            'pic_user_id'     => 'PIC',
        ];
    }
}
