<?php
namespace App\Http\Controllers\Eoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Eoffice\JenisLayananDisposisiRequest;
use App\Models\Eoffice\JenisLayanan;
use App\Models\Eoffice\JenisLayananDisposisi;
use App\Services\Eoffice\JenisLayananDisposisiService;
use Exception;

class JenisLayananDisposisiController extends Controller
{
    public function __construct(protected \App\Services\Eoffice\JenisLayananDisposisiService $JenisLayananDisposisiService)
    {}

    public function create(JenisLayanan $jenisLayanan)
    {
        return view('pages.eoffice.jenis_layanan.ajax.form-disposisi', compact('jenisLayanan'));
    }

    public function edit(JenisLayanan $jenisLayanan, JenisLayananDisposisi $disposisi)
    {
        return view('pages.eoffice.jenis_layanan.ajax.form-disposisi', compact('jenisLayanan', 'disposisi'));
    }

    /**
     * Store a new disposisi for a Jenis Layanan.
     */
    public function store(JenisLayananDisposisiRequest $request, JenisLayanan $jenisLayanan)
    {
        try {
            $this->JenisLayananDisposisiService->store($jenisLayanan->jenislayanan_id, $request->validated());
            return jsonSuccess('Disposisi berhasil ditambahkan.');
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    /**
     * Update disposisi (seq, notify email, or text/ket).
     */
    public function update(\App\Http\Requests\Eoffice\UpdateDisposisiRequest $request, JenisLayananDisposisi $disposisi, $action = null)
    {
        try {
            $id = $disposisi->jldisposisi_id;
            if ($action === 'seq') {
                $this->JenisLayananDisposisiService->updateSeq($id, $request->validated('seq'));
            } elseif ($action === 'notify') {
                $this->JenisLayananDisposisiService->updateNotifyEmail($id, $request->validated('is_notify_email'));
            } else {
                $this->JenisLayananDisposisiService->updateTextKet($id, $request->validated());
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
    public function data(JenisLayanan $jenisLayanan)
    {
        $data = $this->JenisLayananDisposisiService->getByJenisLayanan($jenisLayanan->jenislayanan_id);
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
    public function updateSeq(\App\Http\Requests\Shared\ReorderRequest $request, $id)
    {
        try {
            $this->JenisLayananDisposisiService->updateSeq($id, $request->validated('seq'));
            return jsonSuccess('Urutan berhasil diperbarui.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }
}
