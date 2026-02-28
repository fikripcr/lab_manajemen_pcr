<?php
namespace App\Http\Requests\Survei;

use App\Http\Requests\BaseRequest;

class SurveiRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'judul'           => 'required|string|max:255',
            'deskripsi'       => 'nullable|string',
            'target_role'     => 'required|in:Mahasiswa,Dosen,Tendik,Alumni,Umum',
            'tanggal_mulai'   => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'is_aktif'        => 'boolean',
            'wajib_login'     => 'boolean',
            'bisa_isi_ulang'  => 'boolean',
            'mode'            => 'required|in:Linear,Bercabang',
        ];
    }

    public function attributes(): array
    {
        return [
            'judul'           => 'Judul Survei',
            'deskripsi'       => 'Deskripsi Survei',
            'target_role'     => 'Target Role',
            'tanggal_mulai'   => 'Tanggal Mulai',
            'tanggal_selesai' => 'Tanggal Selesai',
            'is_aktif'        => 'Status Aktif',
            'wajib_login'     => 'Wajib Login',
            'bisa_isi_ulang'  => 'Bisa Isi Ulang',
            'mode'            => 'Mode Survei',
        ];
    }
}
