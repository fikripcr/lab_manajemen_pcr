<?php
namespace App\Http\Requests\Hr;

use App\Http\Requests\BaseRequest;

class LemburRequest extends BaseRequest
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
        if ($this->isMethod('POST') && $this->routeIs('hr.lembur.approve')) {
            return [
                'status'     => 'required|in:approved,rejected,pending',
                'pejabat'    => 'required|string|max:191',
                'keterangan' => 'nullable|string',
            ];
        }

        return [
            'pengusul_id'      => 'required|exists:pegawai,pegawai_id',
            'judul'            => 'required|string|max:255',
            'uraian_pekerjaan' => 'nullable|string',
            'alasan'           => 'nullable|string',
            'tgl_pelaksanaan'  => 'required|date',
            'jam_mulai'        => 'required',
            'jam_selesai'      => 'required',
            'pegawai_ids'      => 'required|array|min:1',
            'catatan_pegawai'  => 'nullable|array',
        ];
    }

    public function attributes(): array
    {
        return [
            'status'           => 'Status',
            'pejabat'          => 'Pejabat',
            'keterangan'       => 'Keterangan',
            'pengusul_id'      => 'Pengusul',
            'judul'            => 'Judul Lembur',
            'uraian_pekerjaan' => 'Uraian Pekerjaan',
            'alasan'           => 'Alasan',
            'tgl_pelaksanaan'  => 'Tanggal Pelaksanaan',
            'jam_mulai'        => 'Jam Mulai',
            'jam_selesai'      => 'Jam Selesai',
            'pegawai_ids'      => 'Pegawai',
            'pegawai_ids.*'    => 'Pegawai',
            'catatan_pegawai'  => 'Catatan Pegawai',
        ];
    }
}
