<?php
namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;

class StatusAktifitasRequest extends FormRequest
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
