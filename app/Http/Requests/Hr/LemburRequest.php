<?php
namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;

class LemburRequest extends FormRequest
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
            'is_dibayar'       => 'boolean',
            'metode_bayar'     => 'nullable|in:uang,cuti_pengganti,tidak_dibayar',
            'nominal_per_jam'  => 'nullable|numeric|min:0',
            'pegawai_ids'      => 'required|array|min:1',
            'pegawai_ids.*'    => 'exists:pegawai,pegawai_id',
            'override_nominal' => 'nullable|array',
            'catatan_pegawai'  => 'nullable|array',
        ];
    }
}
