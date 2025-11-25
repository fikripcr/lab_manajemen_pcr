<?php
namespace App\Http\Controllers\Sys;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sys\RoleRequest;
use App\Models\Sys\Role;
use App\Services\Sys\PermissionService;
use App\Services\Sys\RoleService;

class RoleController extends Controller
{
    protected $roleService;

    public function __construct(RoleService $roleService, PermissionService $permissionService)
    {
        $this->roleService       = $roleService;
        $this->permissionService = $permissionService;
    }

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
        $permissions = $this->permissionService->getAllPermissions();
        return view('pages.sys.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request)
    {
        try {
            $data                = $request->validated();
            $data['permissions'] = $request->has('permissions') ? $request->permissions : [];
            $this->roleService->createRole($data);

            return redirect()->route('sys.roles.index')->with('success', 'Peran berhasil dibuat.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
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

        return view('pages.sys.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleRequest $request, $id)
    {
        try {
            $roleId              = decryptId($id);
            $data                = $request->validated();
            $data['permissions'] = $request->has('permissions') ? $request->permissions : [];
            $this->roleService->updateRole($roleId, $data);

            return redirect()->route('sys.roles.index')->with('success', 'Data berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        try {
            $this->roleService->deleteRole($role->id);

            return redirect()->route('sys.roles.index')->with('success', 'Data berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
