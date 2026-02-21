<?php
namespace App\Services\Hr;

use App\Models\Hr\StatusAktifitas;
use Illuminate\Support\Facades\DB;

class StatusAktifitasService
{
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $statusAktifitas = StatusAktifitas::create($data);
            logActivity('hr', "Menambahkan status aktifitas: {$statusAktifitas->nama_status}", $statusAktifitas);
            return $statusAktifitas;
        });
    }

    public function update(StatusAktifitas $statusAktifitas, array $data)
    {
        return DB::transaction(function () use ($statusAktifitas, $data) {
            $statusAktifitas->update($data);
            logActivity('hr', "Memperbarui status aktifitas: {$statusAktifitas->nama_status}", $statusAktifitas);
            return $statusAktifitas;
        });
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $statusAktifitas = StatusAktifitas::findOrFail($id);
            logActivity('hr', "Menghapus status aktifitas: {$statusAktifitas->nama_status}", $statusAktifitas);
            $statusAktifitas->delete();
        });
    }
}
