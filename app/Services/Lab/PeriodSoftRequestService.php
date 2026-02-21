<?php
namespace App\Services\Lab;

use App\Models\Lab\PeriodSoftRequest;
use Illuminate\Support\Facades\DB;

class PeriodSoftRequestService
{
    /**
     * Get Query for DataTables
     */
    public function getFilteredQuery(array $filters = [])
    {
        return PeriodSoftRequest::with('semester');
    }

    /**
     * Get Period by ID
     */
    public function getById(string $id): ?PeriodSoftRequest
    {
        return PeriodSoftRequest::with('semester')->find($id);
    }

    /**
     * Create New Software Request Period
     */
    public function createPeriod(array $data): PeriodSoftRequest
    {
        return DB::transaction(function () use ($data) {
            if (! empty($data['is_active']) && $data['is_active']) {
                PeriodSoftRequest::where('is_active', true)->update(['is_active' => false]);
            }

            $period = PeriodSoftRequest::create($data);

            logActivity('period_soft_request', "Membuat periode request software baru: {$data['nama_periode']}");

            return $period;
        });
    }

    /**
     * Update Software Request Period
     */
    public function updatePeriod(PeriodSoftRequest $period, array $data): bool
    {
        return DB::transaction(function () use ($period, $data) {
            if (! empty($data['is_active']) && $data['is_active']) {
                PeriodSoftRequest::where('is_active', true)
                    ->where('periodsoftreq_id', '!=', $period->periodsoftreq_id)
                    ->update(['is_active' => false]);
            }

            $period->update($data);

            logActivity('period_soft_request', "Memperbarui periode request software ID {$period->periodsoftreq_id}");

            return true;
        });
    }

    /**
     * Delete Software Request Period
     */
    public function deletePeriod(PeriodSoftRequest $period): bool
    {
        return DB::transaction(function () use ($period) {
            $period->delete();

            logActivity('period_soft_request', "Menghapus periode request software ID {$period->periodsoftreq_id}");

            return true;
        });
    }
}
