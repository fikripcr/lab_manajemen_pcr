<?php
namespace App\Http\Controllers\Sys;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sys\PermissionRequest;
use App\Services\Sys\PermissionService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\DataTables;

class PermissionController extends Controller
{
    public function __construct(protected PermissionService $permissionService)
    {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get unique categories for the filter
        $categories    = $this->permissionService->getUniqueCategories();
        $subCategories = $this->permissionService->getUniqueSubCategories();

        return view('pages.sys.permissions.index', compact('categories', 'subCategories'));
    }

    /**
     * Process datatables ajax request.
     */
    public function paginate(Request $request)
    {
        // Get all request parameters and use them as filters
        $filters = $request->all();

        // Use the service to get the filtered query
        $permissions = $this->permissionService->getFilteredQuery($filters);

        return DataTables::of($permissions)
            ->addIndexColumn()
            ->editColumn('id', function ($permission) {
                return $permission->encryptedId;
            })
            ->editColumn('created_at', function ($permission) {
                return formatTanggalIndo($permission->created_at);
            })
            ->addColumn('category', function ($permission) {
                return $permission->category ?? '-';
            })
            ->addColumn('sub_category', function ($permission) {
                return $permission->sub_category ?? '-';
            })
            ->addColumn('action', function ($permission) {
                return view('components.tabler.datatables-actions', [
                    'editUrl'   => route('sys.permissions.edit', $permission->encryptedId),
                    'deleteUrl' => route('sys.permissions.destroy', $permission->encryptedId),
                ])->render();
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permission = new Permission();
        return view('pages.sys.permissions.create-edit-ajax', compact('permission'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PermissionRequest $request)
    {
        $data  = $request->validated();
        $names = preg_split('/[,\n\r]+/', $data['name']);
        $count = 0;

        foreach ($names as $name) {
            $name = trim($name);
            if (empty($name)) {
                continue;
            }

            // Check if permission already exists
            if (Permission::where('name', $name)->exists()) {
                continue;
            }

            $singleData         = $data;
            $singleData['name'] = $name;
            $this->permissionService->createPermission($singleData);
            $count++;
        }

        if ($count === 0) {
            return jsonError('Tidak ada izin baru yang dibuat (kemungkinan sudah ada).');
        }

        return jsonSuccess($count . ' izin berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        return view('pages.sys.permissions.create-edit-ajax', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PermissionRequest $request, Permission $permission)
    {
        $data = $request->validated();
        $this->permissionService->updatePermission($permission->id, $data);

        return jsonSuccess();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        $this->permissionService->deletePermission($permission->id);

        return jsonSuccess('Izin berhasil dihapus.');
    }
}
