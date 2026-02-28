<?php
namespace App\Http\Requests\Hr;

use App\Http\Requests\BaseRequest;

class StatusAktifitasRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'kode_status' => 'required|string|max:5',
            'nama_status' => 'required|string|max:50',
            'is_active'   => 'boolean',
        ];
    }
}
