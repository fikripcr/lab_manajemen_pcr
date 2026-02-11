<?php
namespace App\Http\Controllers\Eoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Eoffice\JenisLayananDisposisiRequest;
use App\Services\Eoffice\JenisLayananDisposisiService;
use Illuminate\Http\Request;

class JenisLayananDisposisiController extends Controller
{
    protected $service;

    public function __construct(JenisLayananDisposisiService $service)
    {
        $this->service = $service;
    }

    /**
     * Store a new disposisi for a Jenis Layanan.
     */
    public function store(JenisLayananDisposisiRequest $request, $jenislayananId)
    {
        try {
            $this->service->store($jenislayananId, $request->validated());
            return jsonSuccess('Disposisi berhasil ditambahkan.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    /**
     * Update disposisi (seq, notify email, or text/ket).
     */
    public function update(Request $request, $id, $action = null)
    {
        try {
            if ($action === 'seq') {
                $this->service->updateSeq($id, $request->input('seq'));
            } elseif ($action === 'notify') {
                $this->service->updateNotifyEmail($id, $request->input('is_notify_email'));
            } else {
                $this->service->updateTextKet($id, $request->all());
            }
            return jsonSuccess('Disposisi berhasil diperbarui.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    /**
     * Delete a disposisi (and re-sequence).
     */
    public function destroy($id)
    {
        try {
            $this->service->destroy($id);
            return jsonSuccess('Disposisi berhasil dihapus.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    /**
     * Get disposisi data for AJAX (e.g., on Jenis Layanan show page).
     */
    public function data($jenislayananId)
    {
        $data = $this->service->getByJenisLayanan($jenislayananId);
        return jsonSuccess('Data retrieved', null, $data);
    }

    /**
     * Get single disposisi data for edit modal
     */
    public function show($id)
    {
        try {
            $data = $this->service->getById($id);
            return jsonSuccess('Data retrieved', null, $data);
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    /**
     * Update sequence for drag-and-drop
     */
    public function updateSeq(Request $request, $id)
    {
        try {
            $this->service->updateSeq($id, $request->input('seq'));
            return jsonSuccess('Urutan berhasil diperbarui.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }
}
