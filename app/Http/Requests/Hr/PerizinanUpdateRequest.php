<?php
namespace App\Http\Requests\Hr;

use App\Http\Requests\BaseRequest;

class PerizinanUpdateRequest extends BaseRequest
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
            'keterangan'             => 'nullable|string',
            'alamat_izin'            => 'nullable|string',
            'tgl_awal'               => 'required|date',
            'tgl_akhir'              => 'required|date|after_or_equal:tgl_awal',
            'jam_awal'               => 'nullable',
            'jam_akhir'              => 'nullable',
        ];
    }
}
