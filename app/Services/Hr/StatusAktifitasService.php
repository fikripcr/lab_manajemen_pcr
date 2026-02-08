<?php
namespace App\Services\Hr;

use App\Models\Hr\StatusAktifitas;

class StatusAktifitasService
{
    public function create(array $data)
    {
        return StatusAktifitas::create($data);
    }

    public function update(StatusAktifitas $statusAktifitas, array $data)
    {
        $statusAktifitas->update($data);
        return $statusAktifitas;
    }

    public function delete($id)
    {
        $statusAktifitas = StatusAktifitas::findOrFail($id);
        $statusAktifitas->delete();
    }
}
