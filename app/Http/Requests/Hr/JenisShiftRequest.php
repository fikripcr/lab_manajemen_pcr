<?php
namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;

class JenisShiftRequest extends FormRequest
{
    public function authorize()
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
}
