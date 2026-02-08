<?php
namespace App\Services\Hr;

use App\Models\Hr\AttDevice;

class AttDeviceService
{
    public function create(array $data)
    {
        return AttDevice::create($data);
    }

    public function update(AttDevice $attDevice, array $data)
    {
        $attDevice->update($data);
        return $attDevice;
    }

    public function delete($id)
    {
        $attDevice = AttDevice::findOrFail($id);
        $attDevice->delete();
    }
}
