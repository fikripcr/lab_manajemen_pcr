<?php
namespace App\Services;

use App\Models\Sys\Activity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ActivityLogsService
{
    /**
     * Return a filtered query builder (reusable for all contexts)
     */
    public function getFilteredQuery(array $filters = []): Builder
    {
        $query = Activity::select([
            'sys_activity_log.id',
            'sys_activity_log.created_at',
            'sys_activity_log.log_name',
            'sys_activity_log.description',
            DB::raw('users.name as causer_name'),
        ])
            ->leftJoin('users', 'sys_activity_log.causer_id', '=', 'users.id')
            ->orderBy('created_at', 'desc');

        if (! empty($filters['log_name'])) {
            $query->where('log_name', $filters['log_name']);
        }

        if (! empty($filters['event'])) {
            $query->where('event', $filters['event']);
        }

        if (! empty($filters['date_from']) && ! empty($filters['date_to'])) {
            $query->whereBetween('sys_activity_log.created_at', [$filters['date_from'], $filters['date_to']]);
        }

        if (! empty($filters['subject_type'])) {
            $query->where('subject_type', $filters['subject_type']);
        }

        if (! empty($filters['causer_id'])) {
            $query->where('causer_id', $filters['causer_id']);
        }

        return $query;
    }

    /**
     * Build filters from request
     */
    public function buildFiltersFromRequest($request, string $context = 'ui'): array
    {
        $filters = [];

        $filters['log_name']     = $request->filled('log_name') ? $request->log_name : null;
        $filters['event']        = $request->filled('event') ? $request->event : null;
        $filters['subject_type'] = $request->filled('subject_type') ? $request->subject_type : null;
        $filters['causer_id']    = $request->filled('causer_id') ? $request->causer_id : null;

        if ($request->filled('date_from') && $request->filled('date_to')) {
            $filters['date_from'] = $request->date_from;
            $filters['date_to']   = $request->date_to;
        }

        $filters['per_page'] = $request->get('per_page', $context === 'api' ? 15 : 10);

        // Hapus null/empty values agar tidak ganggu applyFilters
        return array_filter($filters, fn($value) => $value !== null && $value !== '');
    }

    /**
     * Get paginated list for API
     */
    public function getActivitiesList(array $filters = []): LengthAwarePaginator
    {
        $query = $this->getFilteredQuery($filters);
        return $query->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Get single activity
     */
    public function getActivityById(int $activityId): ?Activity
    {
        return Activity::with(['causer:id,name', 'subject'])->find($activityId);
    }

    /**
     * Count activities (reuses same filter logic)
     */
    public function countActivities(array $filters = []): int
    {
        return $this->getFilteredQuery($filters)->count();
    }
}
