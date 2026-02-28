<?php
namespace App\Http\Controllers\Sys;

use App\Http\Controllers\Controller;
use App\Services\Sys\SysGlobalSearchService;
use Illuminate\Http\Request;

class SysGlobalSearchController extends Controller
{
    public function __construct(
        protected SysGlobalSearchService $sysGlobalSearchService
    ) {
    }

    public function search(Request $request)
    {
        $query = $request->input('q');

        $results = $this->sysGlobalSearchService->globalSearch($query);

        return response()->json($results);
    }
}
