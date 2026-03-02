<?php
namespace App\Http\Requests\Hr;

use App\Http\Requests\BaseRequest;

class AttDeviceRequest extends BaseRequest
{
    public function authorize(): bool
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

    public function attributes(): array
    {
        return [
            'name'      => 'Nama Device',
            'sn'        => 'Serial Number',
            'ip'        => 'IP Address',
            'port'      => 'Port',
            'is_active' => 'Status Aktif',
        ];
    }
}
