<?php
namespace App\Http\Requests\Event;

use App\Http\Requests\BaseRequest;

class EventTamuRegistrationRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_tamu' => 'required|string|max:255',
            'instansi'  => 'nullable|string|max:255',
            'jabatan'   => 'nullable|string|max:255',
            'kontak'    => 'nullable|string|max:255',
            'tujuan'    => 'nullable|string',
            'foto'      => 'nullable|string', // Base64
            'ttd'       => 'nullable|string', // Base64
        ];
    }

    public function attributes(): array
    {
        return [
            'nama_tamu' => 'Nama Lengkap',
            'instansi'  => 'Instansi / Organisasi',
            'jabatan'   => 'Jabatan',
            'kontak'    => 'Kontak',
            'tujuan'    => 'Tujuan / Keperluan',
            'foto'      => 'Foto',
            'ttd'       => 'Tanda Tangan',
        ];
    }
}
