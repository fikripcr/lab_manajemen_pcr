<?php
namespace App\Services\Hr;

use App\Models\Hr\JenisIzin;
use Illuminate\Support\Facades\DB;

class JenisIzinService
{
    /**
     * Store a newly created resource.
     */
    public function store(array $data): JenisIzin
    {
        return DB::transaction(function () use ($data) {
            $jenisIzin = JenisIzin::create($data);
            logActivity('hr', "Menambahkan jenis izin: {$jenisIzin->nama}", $jenisIzin);
            return $jenisIzin;
        });
    }

    /**
     * Update the specified resource.
     */
    public function update(JenisIzin $jenisIzin, array $data): bool
    {
        return DB::transaction(function () use ($jenisIzin, $data) {
            $updated = $jenisIzin->update($data);
            logActivity('hr', "Memperbarui jenis izin: {$jenisIzin->nama}", $jenisIzin);
            return $updated;
        });
    }

    /**
     * Remove the specified resource.
     */
    public function delete(JenisIzin $jenisIzin): bool
    {
        return DB::transaction(function () use ($jenisIzin) {
            logActivity('hr', "Menghapus jenis izin: {$jenisIzin->nama}", $jenisIzin);
            return $jenisIzin->delete();
        });
    }
}
