<?php
namespace App\Http\Controllers\Sys;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sys\PermissionRequest;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\DataTables;

class PermissionController extends Controller
{

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
        $permissions = Permission::withCount('roles');

        return DataTables::of($permissions)
            ->addIndexColumn()
            ->filterColumn('name', function ($query, $keyword) {
                $query->where('name', 'like', "%{$keyword}%");
            })
            ->filter(function ($query) use ($request) {
                // Global search functionality
                if ($request->has('search') && $request->search['value'] != '') {
                    $searchValue = $request->search['value'];
                    $query->where(function($q) use ($searchValue) {
                        $q->where('name', 'like', '%' . $searchValue . '%');
                    });
                }
            })
            ->editColumn('created_at', function ($permission) {
                return formatTanggalIndo($permission->created_at);
            })
            ->addColumn('action', function ($permission) {
                $encryptedId = encryptId($permission->id);
                return '
                    <div class="d-flex align-items-center">
                        <a class="btn btn-sm btn-icon btn-outline-primary me-1 edit-permission"  href="javascript:void(0)" data-id="' . $encryptedId . '" title="Edit">
                            <i class="bx bx-edit"></i>
                        </a>
                        <div class="dropdown">
                            <button type="button" class="btn btn-sm btn-icon btn-outline-secondary" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item " href="' . route('permissions.show', $encryptedId) . '">
                                    <i class="bx bx-show me-1"></i> View
                                </a>
                                <a href="javascript:void(0)" class="dropdown-item text-danger" onclick="confirmDelete(\'' . route('permissions.destroy', $encryptedId) . '\', \'permissions-table\')" >
                                    <i class="bx bx-trash me-1"></i> Delete
                                </a>
                            </div>
                        </div>
                    </div>';
            })
            ->rawColumns(['action'])
            ->make(true);
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
            Permission::create(['name' => $request->name]);

            return redirect()->route('permissions.index')->with('success', 'Izin berhasil dibuat.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membuat izin: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission)
    {
        return view('pages.sys.permissions.show', compact('permission'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($permissionId)
    {
        $realId = decryptId($permissionId);
        if (! $realId) {
            abort(404);
        }

        $permission = Permission::findOrFail($realId);
        return view('pages.sys.permissions.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PermissionRequest $request, $permissionId)
    {
        $realId = decryptId($permissionId);
        if (! $realId) {
            abort(404);
        }

        $permission = Permission::findOrFail($realId);

        try {
            $permission->update(['name' => $request->name]);

            return redirect()->route('permissions.index')->with('success', 'Izin berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui izin: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($permissionId)
    {
        $realId = decryptId($permissionId);
        if (! $realId) {
            abort(404);
        }

        $permission = Permission::findOrFail($realId);

        // Prevent deletion of permissions that are assigned to roles
        if ($permission->roles->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete permission that is assigned to roles.',
                ]);
        }

        try {
            $permission->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Izin berhasil dihapus.',
                ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus izin: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new permission via modal.
     */
    public function createModal()
    {
        return view('pages.sys.permissions.create-ajax');
    }

    /**
     * Show the form for editing the specified permission via modal.
     */
    public function editModal($permissionId)
    {
        $realId = decryptId($permissionId);

        $permission = Permission::findOrFail($realId);

        return view('pages.sys.permissions.edit-ajax', compact('permission'));
    }

}
