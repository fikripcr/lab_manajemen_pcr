<?php
namespace App\Services\Sys;

use App\Models\Sys\ErrorLog;
use Exception;
use Illuminate\Support\Facades\DB;

class ErrorLogService
{

    /**
     * Get a specific error log by ID
     */
    public function getErrorLogById(int $errorLogId): ?ErrorLog
    {
        return ErrorLog::find($errorLogId);
    }

    public function findOrFail(int $id): ErrorLog
    {
        $model = $this->getErrorLogById($id);
        if (! $model) {
            throw new Exception("Data tidak ditemukan.");
        }
        return $model;
    }

    /**
     * Get filtered query for DataTables
     */
    public function getFilteredQuery(array $filters = [])
    {
        $query = ErrorLog::with('user')
            ->orderBy('created_at', 'desc');

        // Apply filters
        if (! empty($filters['level'])) {
            $query->where('level', $filters['level']);
        }

        if (! empty($filters['start_date']) && ! empty($filters['end_date'])) {
            $query->whereBetween('created_at', [
                $filters['start_date'],
                $filters['end_date'] . ' 23:59:59',
            ]);
        } elseif (! empty($filters['start_date'])) {
            $query->whereDate('created_at', '>=', $filters['start_date']);
        } elseif (! empty($filters['end_date'])) {
            $query->whereDate('created_at', '<=', $filters['end_date']);
        }

        return $query;
    }

    /**
     * Clear all error logs
     */
    public function clearAllErrorLogs(): bool
    {
        return DB::transaction(function () {
            ErrorLog::truncate();
            return true;
        });
    }

}
