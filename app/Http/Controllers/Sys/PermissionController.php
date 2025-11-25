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
    protected $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.sys.permissions.index');
    }

    /**
     * Process datatables ajax request.
     */
    public function paginate(Request $request)
    {
        try {
            $filters = $request->only(['name', 'category', 'sub_category']);

            // Use the service to get the filtered query
            $permissions = $this->permissionService->getFilteredQuery($filters);

            return DataTables::of($permissions)
                ->addIndexColumn()
                ->order(function ($query) {
                    $query->latest('sys_permissions.created_at'); // Sort by created_at DESC by default
                })
                ->filterColumn('name', function ($query, $keyword) {
                    $query->where('sys_permissions.name', 'like', "%{$keyword}%");
                })
                ->filterColumn('category', function ($query, $keyword) {
                    $query->where('sys_permissions.category', 'like', "%{$keyword}%");
                })
                ->editColumn('created_at', function ($permission) {
                    return formatTanggalIndo($permission->created_at);
                })
                ->addColumn('action', function ($permission) {
                    return '
                        <div class="d-flex align-items-center">
                            <a class="btn btn-sm btn-icon btn-outline-primary me-1 edit-permission"  href="javascript:void(0)" data-id="' . $permission->encryptedId . '" title="Edit">
                                <i class="bx bx-edit"></i>
                            </a>
                            <div class="dropdown">
                                <button type="button" class="btn btn-sm btn-icon btn-outline-secondary" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a href="javascript:void(0)" class="dropdown-item text-danger" onclick="confirmDelete(\'' . route('sys.permissions.destroy', $permission->encryptedId) . '\', \'permissions-table\')" >
                                        <i class="bx bx-trash me-1"></i> Delete
                                    </a>
                                </div>
                            </div>
                        </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan saat mengambil data izin.'], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.sys.permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PermissionRequest $request)
    {
        try {
            $data = $request->validated();
            $this->permissionService->createPermission($data);

            return redirect()->route('sys.permissions.index')->with('success', 'Izin berhasil dibuat.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
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
    public function edit($id)
    {
        $realId     = decryptId($id);
        $permission = $this->permissionService->getPermissionById($realId);

        return view('pages.sys.permissions.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PermissionRequest $request, $permissionId)
    {
        try {
            $realId = decryptId($permissionId);
            $data   = $request->validated();
            $this->permissionService->updatePermission($realId, $data);

            return redirect()->route('sys.permissions.index')->with('success', 'Izin berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($permissionId)
    {
        try {
            $realId = decryptId($permissionId);
            $result = $this->permissionService->deletePermission($realId);

            return response()->json([
                'success' => true,
                'message' => 'Izin berhasil dihapus.',
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
