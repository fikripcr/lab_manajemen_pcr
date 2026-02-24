<?php
namespace App\Services\Lab;

use App\Models\Lab\RequestSoftware;
use Exception;
use Illuminate\Support\Facades\DB;

class SoftwareRequestService
{
    /**
     * Get Query for DataTables
     */
    public function getFilteredQuery(array $filters = [])
    {
        $query = RequestSoftware::with(['dosen', 'mataKuliahs'])->select('lab_request_software.*');

        // Check if filtering by specific status
        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Search handled by Controller DataTables closure typically,
        // or we can add global search logic here if we pass search term.
        // Controller uses `DataTables::of($query)` which handles global search if columns are defined.

        return $query;
    }

    /**
     * Get Request by ID
     */
    public function getRequestById(string $id): ?RequestSoftware
    {
        return RequestSoftware::with(['dosen', 'mataKuliahs'])->find($id);
    }

    /**
     * Update Software Request (Used for Status Updates)
     */
    public function updateRequest(RequestSoftware $request, array $data): bool
    {
        return DB::transaction(function () use ($request, $data) {
            $oldStatus = $request->status;

            $request->update($data);

            if ($oldStatus !== $request->status) {
                logActivity(
                    'software_request_management',
                    "Mengubah status request software ID {$request->request_software_id} dari {$oldStatus} ke {$request->status}"
                );
            } else {
                logActivity(
                    'software_request_management',
                    "Memperbarui data request software ID {$request->request_software_id}"
                );
            }

            return true;
        });
    }

    /**
     * Create New Software Request
     */
    public function createRequest(array $data): RequestSoftware
    {
        return DB::transaction(function () use ($data) {
            $request = RequestSoftware::create([
                'dosen_id'         => auth()->id(),
                'periodsoftreq_id' => $data['periodsoftreq_id'],
                'nama_software'    => $data['nama_software'],
                'versi'            => $data['versi'] ?? null,
                'url_download'     => $data['url_download'] ?? null,
                'deskripsi'        => $data['deskripsi'],
                'status'           => 'pending',
            ]);

            // Sync Mata Kuliah
            if (! empty($data['mata_kuliah_ids'])) {
                $request->mataKuliahs()->sync($data['mata_kuliah_ids']);
            }

            // Create Initial Approval (Pending)
            $approval = \App\Models\Lab\RiwayatApproval::create([
                'model'    => RequestSoftware::class,
                'model_id' => $request->request_software_id,
                'status'   => 'pending',
                'catatan'  => 'Menunggu persetujuan',
            ]);

            $request->update(['latest_riwayatapproval_id' => $approval->riwayatapproval_id]);

            logActivity('software_request_management', "Membuat request software baru: {$data['nama_software']}");

            return $request;
        });
    }

    /**
     * Approve Software Request
     */
    public function approveRequest(RequestSoftware $request, array $data): void
    {
        DB::transaction(function () use ($request, $data) {
            // Create new approval record
            $approval = \App\Models\Lab\RiwayatApproval::create([
                'model'    => RequestSoftware::class,
                'model_id' => $request->request_software_id,
                'status'   => $data['status'],
                'pejabat'  => $data['pejabat'] ?? auth()->user()->name,
                'catatan'  => $data['keterangan'] ?? null,
            ]);

            // Update latest approval pointer and sync status
            $request->update([
                'latest_riwayatapproval_id' => $approval->riwayatapproval_id,
                'status'                    => $data['status'],
            ]);

            logActivity(
                'software_request_management',
                "Approval request software ID {$request->request_software_id}: status changed to {$data['status']}"
            );
        });
    }

    protected function findOrFail(string $id): RequestSoftware
    {
        $model = RequestSoftware::find($id);
        if (! $model) {
            throw new Exception("Request Software dengan ID {$id} tidak ditemukan.");
        }
        return $model;
    }
}
