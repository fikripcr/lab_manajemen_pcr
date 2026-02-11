<?php
namespace App\Services\Eoffice;

use App\Models\Eoffice\JenisLayananPeriode;

class JenisLayananPeriodeService
{
    /**
     * Get all periods for a given Jenis Layanan.
     */
    public function getByJenisLayanan($jenislayananId)
    {
        return JenisLayananPeriode::where('jenislayanan_id', $jenislayananId)
            ->orderBy('tgl_mulai', 'desc')
            ->get();
    }

    /**
     * Store a new period with overlap check.
     */
    public function store($jenislayananId, array $data)
    {
        if (JenisLayananPeriode::hasOverlap($jenislayananId, $data['tgl_mulai'], $data['tgl_selesai'])) {
            throw new \Exception('Tanggal overlap dengan periode yang sudah tersedia.');
        }

        $data['jenislayanan_id'] = $jenislayananId;
        return JenisLayananPeriode::create($data);
    }

    /**
     * Update a period with overlap check.
     */
    public function update($id, array $data)
    {
        $periode = JenisLayananPeriode::findOrFail($id);

        if (JenisLayananPeriode::hasOverlap($periode->jenislayanan_id, $data['tgl_mulai'], $data['tgl_selesai'], $id)) {
            throw new \Exception('Tanggal overlap dengan periode yang sudah tersedia.');
        }

        $periode->update($data);
        return $periode;
    }

    /**
     * Delete a period.
     */
    public function destroy($id)
    {
        return JenisLayananPeriode::findOrFail($id)->delete();
    }

    /**
     * Get single period by ID.
     */
    public function getById($id)
    {
        return JenisLayananPeriode::findOrFail($id);
    }

    /**
     * Get active periods for a service type.
     */
    public function getAktif($jenislayananId)
    {
        return JenisLayananPeriode::getAktif($jenislayananId);
    }
}
