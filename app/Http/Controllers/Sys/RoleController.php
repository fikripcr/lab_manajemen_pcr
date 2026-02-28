<?php
namespace App\Http\Controllers\Sys;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sys\RoleRequest;
use App\Models\Sys\Role;
use App\Services\Sys\PermissionService;
use App\Services\Sys\RoleService;

class RoleController extends Controller
{
    public function __construct(
        protected RoleService $roleService,
        protected PermissionService $permissionService,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = $this->roleService->getRoleList([]);
        return view('pages.sys.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $role            = new Role();
        $permissions     = $this->permissionService->getAllPermissions();
        $rolePermissions = [];
        return view('pages.sys.roles.create-edit-ajax', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request)
    {
        $data                = $request->validated();
        $data['permissions'] = $request->permissions ?? [];
        $this->roleService->createRole($data);

        return jsonSuccess('Peran berhasil dibuat.', route('sys.roles.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        $role           = $this->roleService->getRoleById($role->id);
        $allPermissions = $this->permissionService->getAllPermissions();

        return view('pages.sys.roles.show', compact('role', 'allPermissions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $role            = $this->roleService->getRoleById($role->id);
        $permissions     = $this->permissionService->getAllPermissions();
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('pages.sys.roles.create-edit-ajax', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleRequest $request, Role $role)
    {
        $data                = $request->validated();
        $data['permissions'] = $request->input('permissions', []);
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
