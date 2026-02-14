<?php
namespace App\Http\Controllers\Sys;

use App\Http\Controllers\Controller;
use App\Services\Sys\SysGlobalSearchService;
use Exception;
use Illuminate\Http\Request;

class SysGlobalSearchController extends Controller
{
    public function __construct(
        protected SysGlobalSearchService $sysGlobalSearchService
    ) {
    }

    public function search(Request $request)
    {
        try {
            $query = $request->input('q');

            $results = $this->sysGlobalSearchService->globalSearch($query);

            return response()->json($results);
        } catch (Exception $e) {
            \Log::error('Global search error: ' . $e->getMessage());
            return response()->json([
                'users'         => [],
                'roles'         => [],
                'permissions'   => [],
                'activities'    => [],
                'error_logs'    => [],
                'server_hosts'  => [],
                'server_checks' => [],
                'error'         => 'Terjadi kesalahan saat pencarian global.',
            ]);
        }
    }
}
