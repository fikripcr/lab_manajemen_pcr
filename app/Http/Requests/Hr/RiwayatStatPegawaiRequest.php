<?php
namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;

class RiwayatStatPegawaiRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'statuspegawai_id' => 'required|exists:hr_status_pegawai,statuspegawai_id',
            'tmt'              => 'required|date',
            'no_sk'            => 'nullable|string|max:100',
            // 'file_sk' => 'nullable|file...',
        ];
    }
}
