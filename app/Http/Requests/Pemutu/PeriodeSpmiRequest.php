<?php
namespace App\Http\Requests\Pemutu;

use Illuminate\Foundation\Http\FormRequest;

class PeriodeSpmiRequest extends FormRequest
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
        return [
            'periode.required'       => 'Periode wajib diisi.',
            'jenis_periode.required' => 'Jenis periode wajib diisi.',
        ];
    }
}
