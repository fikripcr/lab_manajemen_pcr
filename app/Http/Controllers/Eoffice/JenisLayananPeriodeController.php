<?php
namespace App\Http\Controllers\Eoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Eoffice\JenisLayananPeriodeStoreRequest;
use App\Services\Eoffice\JenisLayananPeriodeService;
use Illuminate\Http\Request;

class JenisLayananPeriodeController extends Controller
{
    protected $service;

    public function __construct(JenisLayananPeriodeService $service)
    {
        $this->service = $service;
    }

    /**
     * Store a new periode for a Jenis Layanan.
     */
    public function store(JenisLayananPeriodeStoreRequest $request, $jenislayananId)
    {
        $validated = $request->validated();
        $validated['jenislayanan_id'] = $jenislayananId;

        try {
            $this->service->createPeriode($validated);
            return jsonSuccess('Periode berhasil dibuat.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    /**
     * Update an existing periode.
     */
    public function update(JenisLayananPeriodeStoreRequest $request, $id)
    {
        $validated = $request->validated();

        try {
            $this->service->update($id, $validated);
            return jsonSuccess('Periode berhasil diperbarui.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    /**
     * Delete a periode.
     */
    public function destroy($id)
    {
        try {
            $this->service->destroy($id);
            return jsonSuccess('Periode berhasil dihapus.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    /**
     * Get periode data for AJAX.
     */
    public function data($jenislayananId)
    {
        $data = $this->service->getByJenisLayanan($jenislayananId);

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
    public function show($id)
    {
        $periode = $this->service->getById($id);
        return jsonSuccess('Data retrieved', null, [
            'jlperiode_id' => $periode->jlperiode_id,
            'tgl_mulai'    => $periode->tgl_mulai->format('Y-m-d'),
            'tgl_selesai'  => $periode->tgl_selesai->format('Y-m-d'),
            'tahun_ajaran' => $periode->tahun_ajaran,
            'semester'     => $periode->semester,
        ]);
    }
}
