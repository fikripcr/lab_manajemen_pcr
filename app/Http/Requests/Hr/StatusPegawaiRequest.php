<?php
namespace App\Http\Requests\Hr;

use App\Http\Requests\BaseRequest;

class StatusPegawaiRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'kode_status' => 'required|string|max:10',
            'nama_status' => 'required|string|max:50',
            'organisasi'  => 'nullable|string|max:50',
            'is_active'   => 'boolean',
        ];
    }
}
