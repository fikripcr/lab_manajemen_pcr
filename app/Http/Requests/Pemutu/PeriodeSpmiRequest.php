<?php
namespace App\Http\Requests\Pemutu;

use App\Http\Requests\BaseRequest;

class PeriodeSpmiRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'periode'            => 'required|integer',
            'jenis_periode'      => 'required|string|max:20',
            'penetapan_awal'     => 'nullable|date',
            'penetapan_akhir'    => 'nullable|date',
            'ed_awal'            => 'nullable|date',
            'ed_akhir'           => 'nullable|date',
            'ami_awal'           => 'nullable|date',
            'ami_akhir'          => 'nullable|date',
            'pengendalian_awal'  => 'nullable|date',
            'pengendalian_akhir' => 'nullable|date',
            'peningkatan_awal'   => 'nullable|date',
            'peningkatan_akhir'  => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'periode.required'       => 'Periode wajib diisi.',
            'jenis_periode.required' => 'Jenis periode wajib diisi.',
            'tgl_mulai.required'   => 'Tanggal Mulai harus diisi.',
            'tgl_selesai.required' => 'Tanggal Selesai harus diisi.',
            'tgl_selesai.after'    => 'Tanggal Selesai harus setelah Tanggal Mulai.',
        ]);
    }
}
