<?php
namespace App\Http\Requests\Hr;

use App\Http\Requests\BaseRequest;

class PerizinanStoreRequest extends BaseRequest
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
            'jenisizin_id'           => 'required|exists:hr_jenis_izin,jenisizin_id',
            'pengusul'               => 'required|exists:pegawai,pegawai_id',
            'pekerjaan_ditinggalkan' => 'nullable|string|max:500',
            'tgl_mulai'              => 'required|date',
            'tgl_selesai'            => 'required|date|after_or_equal:tgl_mulai',
            'keterangan'             => 'nullable|string|max:1000',
        ];
    }

    public function attributes(): array
    {
        return [
            'jenisizin_id'           => 'Jenis Izin',
            'pengusul'               => 'Pengusul',
            'pekerjaan_ditinggalkan' => 'Pekerjaan Ditinggalkan',
            'tgl_mulai'              => 'Tanggal Mulai',
            'tgl_selesai'            => 'Tanggal Selesai',
            'keterangan'             => 'Keterangan',
        ];
    }
}
