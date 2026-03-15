<?php
namespace App\Http\Requests\Pemutu;

use App\Http\Requests\BaseRequest;

class PeriodeKpiRequest extends BaseRequest
{
    /**
     */

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama'            => 'required|string|max:100',
            'semester'        => 'required|in:Ganjil,Genap',
            'tahun_akademik'  => 'required|string|max:20',
            'tahun'           => 'required|integer',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        ];
    }

    public function attributes(): array
    {
        return [
            'nama'            => 'Nama',
            'semester'        => 'Semester',
            'tahun_akademik'  => 'Tahun Akademik',
            'tahun'           => 'Tahun',
            'tanggal_mulai'   => 'Tanggal Mulai',
            'tanggal_selesai' => 'Tanggal Selesai',
        ];
    }
}
