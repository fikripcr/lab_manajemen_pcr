<?php
namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;

class RapatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'jenis_rapat'     => 'required|string|max:20',
            'judul_kegiatan'  => 'required|string|max:100',
            'tgl_rapat'       => 'required|date',
            'waktu_mulai'     => 'required|date_format:H:i',
            'waktu_selesai'   => 'required|date_format:H:i|after:waktu_mulai',
            'tempat_rapat'    => 'required|string|max:200',
            'ketua_user_id'   => 'nullable|exists:users,id',
            'notulen_user_id' => 'nullable|exists:users,id',
            'author_user_id'  => 'nullable|exists:users,id',
            'keterangan'      => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'waktu_selesai.after' => 'Waktu Selesai harus setelah Waktu Mulai.',
            'date_format'         => 'Format :attribute tidak valid (HH:MM).',
        ];
    }

    public function attributes(): array
    {
        return [
            'jenis_rapat'     => 'Jenis Rapat',
            'judul_kegiatan'  => 'Judul Kegiatan',
            'tgl_rapat'       => 'Tanggal Rapat',
            'waktu_mulai'     => 'Waktu Mulai',
            'waktu_selesai'   => 'Waktu Selesai',
            'tempat_rapat'    => 'Tempat Rapat',
            'ketua_user_id'   => 'Ketua Rapat',
            'notulen_user_id' => 'Notulen Rapat',
            'author_user_id'  => 'Author',
        ];
    }
}
