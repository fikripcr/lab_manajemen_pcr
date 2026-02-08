<?php
namespace App\Services\Hr;

use App\Models\Hr\StatusPegawai;

class StatusPegawaiService
{
    public function create(array $data)
    {
        return StatusPegawai::create($data);
    }

    public function update(StatusPegawai $statusPegawai, array $data)
    {
        $statusPegawai->update($data);
        return $statusPegawai;
    }

    public function delete($id)
    {
        $statusPegawai = StatusPegawai::findOrFail($id);
        $statusPegawai->delete();
    }
}
