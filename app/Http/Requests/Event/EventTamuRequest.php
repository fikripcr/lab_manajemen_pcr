<?php
namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;

class EventTamuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'event_id'     => 'required|exists:events,event_id',
            'nama_tamu'    => 'required|string|max:150',
            'instansi'     => 'nullable|string|max:150',
            'jabatan'      => 'nullable|string|max:150',
            'kontak'       => 'nullable|string|max:100',
            'tujuan'       => 'nullable|string|max:200',
            'waktu_datang' => 'nullable|date',
            'foto'         => 'nullable|image|max:5120', // FilePond temp image or direct
            'ttd'          => 'nullable|image|max:5120',
            'keterangan'   => 'nullable|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'event_id'  => 'Event',
            'nama_tamu' => 'Nama Tamu',
            'instansi'  => 'Instansi',
            'kontak'    => 'Kontak',
        ];
    }
}
