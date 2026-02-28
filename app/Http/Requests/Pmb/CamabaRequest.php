<?php

namespace App\Http\Requests\Pmb;

use App\Http\Requests\BaseRequest;

class CamabaRequest extends BaseRequest
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
     */
    public function rules(): array
    {
        $id = $this->route('camaba') ? $this->route('camaba')->camaba_id : null;

        return [
            'nik' => 'required|unique:pmb_camaba,nik,' . ($id ?? 'NULL') . ',camaba_id',
            'no_hp' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat_lengkap' => 'required',
            'asal_sekolah' => 'required',
        ];
    }

    public function attributes(): array
    {
        return [
            'nik' => 'NIK',
            'no_hp' => 'Nomor HP',
            'tempat_lahir' => 'Tempat Lahir',
            'tanggal_lahir' => 'Tanggal Lahir',
            'jenis_kelamin' => 'Jenis Kelamin',
            'alamat_lengkap' => 'Alamat Lengkap',
            'asal_sekolah' => 'Asal Sekolah',
        ];
    }
}
