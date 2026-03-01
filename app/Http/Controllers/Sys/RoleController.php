<?php
namespace App\Http\Controllers\Sys;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sys\RoleRequest;
use App\Models\Sys\Permission;
use App\Models\Sys\Role;
use App\Services\Sys\PermissionService;
use App\Services\Sys\RoleService;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct(
        protected RoleService $roleService,
        protected PermissionService $permissionService,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->matrix($request);
    }

    /**
     * Display the permission matrix.
     */
    public function matrix(Request $request)
    {
        $roles          = Role::with('permissions')->get();
        $allCategories  = Permission::distinct()->pluck('category');
        $activeCategory = $request->query('system', $allCategories->first());

        $permissions = Permission::where('category', $activeCategory)
            ->get()
            ->groupBy(['sub_category']);

        return view('pages.sys.roles.matrix', compact('roles', 'permissions', 'allCategories', 'activeCategory'));
    }

    /**
     * Update permissions from the matrix.
     */
    public function updateMatrix(Request $request)
    {
        $matrixIds = $request->input('matrix', []); // Format: role_id => [permission_names]

        foreach ($matrixIds as $roleId => $permissionNames) {
            $this->roleService->updateRolePermissions((int) $roleId, (array) $permissionNames);
        }

        return jsonSuccess('Matriks hak akses berhasil diperbarui.', route('sys.roles.matrix'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $role = new Role();
        return view('pages.sys.roles.create-edit-ajax', compact('role'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request)
    {
        $data  = $request->validated();
        $names = preg_split('/[,\n\r]+/', $data['name']);
        $count = 0;

        foreach ($names as $name) {
            $name = trim($name);
            if (empty($name)) {
                continue;
            }

            // Check if role already exists
            if (Role::where('name', $name)->exists()) {
                continue;
            }

            $singleData         = $data;
            $singleData['name'] = $name;
            $this->roleService->createRole($singleData);
            $count++;
        }

        if ($count === 0) {
            return jsonError('Tidak ada peran baru yang dibuat (kemungkinan sudah ada).');
        }

        return jsonSuccess($count . ' peran berhasil dibuat.', route('sys.roles.index'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $role = $this->roleService->getRoleById($role->id);

        return view('pages.sys.roles.create-edit-ajax', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleRequest $request, Role $role)
    {
        $data = $request->validated();
        $this->roleService->updateRole($role->id, $data);

        return jsonSuccess('Data berhasil diperbarui.', route('sys.roles.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $this->roleService->deleteRole($role->id);

        return jsonSuccess('Data berhasil dihapus.', route('sys.roles.index'));
    }
}
