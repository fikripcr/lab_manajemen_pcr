<?php
namespace App\Http\Controllers\Eoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Eoffice\JenisLayananDisposisiRequest;
use App\Models\Eoffice\JenisLayanan;
use App\Models\Eoffice\JenisLayananDisposisi;
use App\Services\Eoffice\JenisLayananDisposisiService;
use Illuminate\Http\Request;

class JenisLayananDisposisiController extends Controller
{
    protected $JenisLayananDisposisiService;

    public function __construct(JenisLayananDisposisiService $JenisLayananDisposisiService)
    {
        $this->JenisLayananDisposisiService = $JenisLayananDisposisiService;
    }

    /**
     * Store a new disposisi for a Jenis Layanan.
     */
    public function store(JenisLayananDisposisiRequest $request, JenisLayanan $jenis_layanan)
    {
        try {
            $this->JenisLayananDisposisiService->store($jenis_layanan->jenislayanan_id, $request->validated());
            return jsonSuccess('Disposisi berhasil ditambahkan.');
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    /**
     * Update disposisi (seq, notify email, or text/ket).
     */
    public function update(Request $request, JenisLayananDisposisi $disposisi, $action = null)
    {
        try {
            $id = $disposisi->jldisposisi_id;
            if ($action === 'seq') {
                $this->JenisLayananDisposisiService->updateSeq($id, $request->input('seq'));
            } elseif ($action === 'notify') {
                $this->JenisLayananDisposisiService->updateNotifyEmail($id, $request->input('is_notify_email'));
            } else {
                $this->JenisLayananDisposisiService->updateTextKet($id, $request->all());
            }
            return jsonSuccess('Disposisi berhasil diperbarui.');
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    /**
     * Delete a disposisi (and re-sequence).
     */
    public function destroy(JenisLayananDisposisi $disposisi)
    {
        try {
            $this->JenisLayananDisposisiService->destroy($disposisi->jldisposisi_id);
            return jsonSuccess('Disposisi berhasil dihapus.');
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    /**
     * Get disposisi data for AJAX (e.g., on Jenis Layanan show page).
     */
    public function data(JenisLayanan $jenis_layanan)
    {
        $data = $this->JenisLayananDisposisiService->getByJenisLayanan($jenis_layanan->jenislayanan_id);
        return jsonSuccess('Data retrieved', null, $data);
    }

    /**
     * Get single disposisi data for edit modal
     */
    public function show(JenisLayananDisposisi $disposisi)
    {
        try {
            $data = $disposisi;
            return jsonSuccess('Data retrieved', null, $data);
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    /**
     * Update sequence for drag-and-drop
     */
    public function updateSeq(Request $request, $id)
    {
        try {
            $this->JenisLayananDisposisiService->updateSeq($id, $request->input('seq'));
            return jsonSuccess('Urutan berhasil diperbarui.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }
}
