<?php
namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;

class EventTamuRegistrationRequest extends FormRequest
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
            'keperluan' => 'nullable|string',
            'foto'      => 'nullable|string', // Base64
            'ttd'       => 'nullable|string', // Base64
        ];
    }

    public function attributes(): array
    {
        return [
            'nama_tamu' => 'Nama Lengkap',
            'instansi'  => 'Instansi / Organisasi',
            'keperluan' => 'Keperluan',
            'foto'      => 'Foto',
            'ttd'       => 'Tanda Tangan',
        ];
    }
}
