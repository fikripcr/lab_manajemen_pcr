<?php
namespace App\Services\Hr;

use App\Models\Hr\AttDevice;
use Illuminate\Support\Facades\DB;

class AttDeviceService
{
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $attDevice = AttDevice::create($data);
            logActivity('hr', "Menambahkan device presensi: {$attDevice->device_name}", $attDevice);
            return $attDevice;
        });
    }

    public function update(AttDevice $attDevice, array $data)
    {
        return DB::transaction(function () use ($attDevice, $data) {
            $attDevice->update($data);
            logActivity('hr', "Memperbarui device presensi: {$attDevice->device_name}", $attDevice);
            return $attDevice;
        });
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $attDevice = AttDevice::findOrFail($id);
            logActivity('hr', "Menghapus device presensi: {$attDevice->device_name}", $attDevice);
            $attDevice->delete();
        });
    }
}
