<?php
namespace App\Http\Controllers\Eoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Eoffice\JenisLayananPeriodeStoreRequest;
use App\Models\Eoffice\JenisLayanan;
use App\Models\Eoffice\JenisLayananPeriode;
use App\Services\Eoffice\JenisLayananPeriodeService;
use Exception;

class JenisLayananPeriodeController extends Controller
{
    protected $JenisLayananPeriodeService;

    public function __construct(JenisLayananPeriodeService $JenisLayananPeriodeService)
    {
        $this->JenisLayananPeriodeService = $JenisLayananPeriodeService;
    }

    /**
     * Store a new periode for a Jenis Layanan.
     */
    public function store(JenisLayananPeriodeStoreRequest $request, JenisLayanan $jenis_layanan)
    {
        $validated                    = $request->validated();
        $validated['jenislayanan_id'] = $jenis_layanan->jenislayanan_id;

        try {
            $this->JenisLayananPeriodeService->createPeriode($validated);
            return jsonSuccess('Periode berhasil dibuat.');
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    /**
     * Update an existing periode.
     */
    public function update(JenisLayananPeriodeStoreRequest $request, JenisLayananPeriode $periode)
    {
        $validated = $request->validated();

        try {
            $this->JenisLayananPeriodeService->update($periode->jlperiode_id, $validated);
            return jsonSuccess('Periode berhasil diperbarui.');
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    /**
     * Delete a periode.
     */
    public function destroy(JenisLayananPeriode $periode)
    {
        try {
            $this->JenisLayananPeriodeService->destroy($periode->jlperiode_id);
            return jsonSuccess('Periode berhasil dihapus.');
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    /**
     * Get periode data for AJAX.
     */
    public function data(JenisLayanan $jenis_layanan)
    {
        $data = $this->JenisLayananPeriodeService->getByJenisLayanan($jenis_layanan->jenislayanan_id);

        // Format dates for display
        $formatted = $data->map(function ($item) {
            return [
                'jlperiode_id' => $item->jlperiode_id,
                'hashid'       => $item->hashid,
                'tgl_mulai'    => $item->tgl_mulai->format('d M Y'),
                'tgl_selesai'  => $item->tgl_selesai->format('d M Y'),
                'tahun_ajaran' => $item->tahun_ajaran ?? '-',
                'semester'     => $item->semester ?? '-',
                'is_active'    => now()->between($item->tgl_mulai, $item->tgl_selesai),
            ];
        });

        return jsonSuccess('Data retrieved', null, $formatted);
    }

    /**
     * Get a single periode (for edit form pre-fill).
     */
    public function show(JenisLayananPeriode $periode)
    {
        return jsonSuccess('Data retrieved', null, [
            'jlperiode_id' => $periode->jlperiode_id,
            'tgl_mulai'    => $periode->tgl_mulai->format('Y-m-d'),
            'tgl_selesai'  => $periode->tgl_selesai->format('Y-m-d'),
            'tahun_ajaran' => $periode->tahun_ajaran,
            'semester'     => $periode->semester,
        ]);
    }
}
