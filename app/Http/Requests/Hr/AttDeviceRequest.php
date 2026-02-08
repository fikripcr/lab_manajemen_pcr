<?php
namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;

class AttDeviceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'      => 'required|string|max:255',
            'sn'        => 'required|string|max:255|unique:hr_att_device,sn,' . $this->route('att_device'),
            'ip'        => 'required|ip',
            'port'      => 'required|numeric',
            'is_active' => 'boolean',
        ];
    }
}
