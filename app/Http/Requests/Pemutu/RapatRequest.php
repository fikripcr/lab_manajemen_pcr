<?php
namespace App\Http\Requests\Pemutu;

use App\Http\Requests\BaseRequest;

class RapatRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
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
        return array_merge(parent::messages(), [
            'waktu_selesai.after' => 'Waktu Selesai harus setelah Waktu Mulai.',
            'date_format'         => 'Format :attribute tidak valid (HH:MM).',
        ]);
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
