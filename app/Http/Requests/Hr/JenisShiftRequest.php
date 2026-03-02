<?php
namespace App\Http\Requests\Hr;

use App\Http\Requests\BaseRequest;

class JenisShiftRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'jenis_shift'      => 'required|string|max:255',
            'jam_masuk'        => 'required',
            'jam_masuk_awal'   => 'required',
            'jam_masuk_akhir'  => 'required',
            'jam_pulang'       => 'required',
            'jam_pulang_awal'  => 'required',
            'jam_pulang_akhir' => 'required',
            'is_active'        => 'boolean',
        ];
    }

    public function attributes(): array
    {
        return [
            'jenis_shift'      => 'Jenis Shift',
            'jam_masuk'        => 'Jam Masuk',
            'jam_masuk_awal'   => 'Jam Masuk Awal',
            'jam_masuk_akhir'  => 'Jam Masuk Akhir',
            'jam_pulang'       => 'Jam Pulang',
            'jam_pulang_awal'  => 'Jam Pulang Awal',
            'jam_pulang_akhir' => 'Jam Pulang Akhir',
            'is_active'        => 'Status Aktif',
        ];
    }
}
