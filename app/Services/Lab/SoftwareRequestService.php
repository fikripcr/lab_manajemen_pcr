<?php
namespace App\Services\Lab;

use App\Models\Lab\RequestSoftware;
use Illuminate\Support\Facades\DB;

class SoftwareRequestService
{
    /**
     * Get Query for DataTables
     */
    public function getFilteredQuery(array $filters = [])
    {
        $query = RequestSoftware::with(['dosen', 'mataKuliahs'])->select('request_software.*');

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
    public function updateRequest(string $id, array $data): bool
    {
        return DB::transaction(function () use ($id, $data) {
            $request = $this->findOrFail($id);

            $oldStatus = $request->status;

            $request->update($data);

            if ($oldStatus !== $request->status) {
                logActivity(
                    'software_request_management',
                    "Mengubah status request software ID {$id} dari {$oldStatus} ke {$request->status}"
                );
            } else {
                logActivity(
                    'software_request_management',
                    "Memperbarui data request software ID {$id}"
                );
            }

            return true;
        });
    }

    protected function findOrFail(string $id): RequestSoftware
    {
        $model = RequestSoftware::find($id);
        if (! $model) {
            throw new \Exception("Request Software dengan ID {$id} tidak ditemukan.");
        }
        return $model;
    }
}
