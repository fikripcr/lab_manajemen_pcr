<?php
namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Map standard controller methods to Spatie permissions dynamically.
     * For example, passing 'pemutu.dokumen' automatically applies:
     * - permission:pemutu.dokumen.view to index, show, data
     * - permission:pemutu.dokumen.create to create, store
     * - permission:pemutu.dokumen.update to edit, update
     * - permission:pemutu.dokumen.delete to destroy
     */
    protected function authorizeResourcePermissions(string $prefix)
    {
        $this->middleware("permission:{$prefix}.view")->only(['index', 'show', 'data']);
        $this->middleware("permission:{$prefix}.view-all")->only(['index', 'show', 'data']);
        $this->middleware("permission:{$prefix}.view-own")->only(['index', 'show', 'data']);
        $this->middleware("permission:{$prefix}.create")->only(['create', 'store']);
        $this->middleware("permission:{$prefix}.update")->only(['edit', 'update']);
        $this->middleware("permission:{$prefix}.delete")->only(['destroy']);
        $this->middleware("permission:{$prefix}.export")->only(['export']);
        $this->middleware("permission:{$prefix}.import")->only(['import']);
    }
}
