<?php
namespace App\Http\Controllers\Sys;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sys\RoleRequest;
use App\Models\Sys\Role;
use App\Services\Sys\PermissionService;
use App\Services\Sys\RoleService;
use Exception;

class RoleController extends Controller
{
    protected $RoleService;

    public function __construct(RoleService $RoleService, PermissionService $permissionService)
    {
        $this->RoleService       = $RoleService;
        $this->permissionService = $permissionService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = $this->RoleService->getRoleList([]);
        return view('pages.sys.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.sys.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request)
    {
        try {
            $data                = $request->validated();
            $data['permissions'] = $request->permissions ?? [];
            $this->RoleService->createRole($data);

            return jsonSuccess('Peran berhasil dibuat.', route('sys.roles.index'));
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        $role           = $this->RoleService->getRoleById($role->id);
        $allPermissions = $this->permissionService->getAllPermissions();

        return view('pages.sys.roles.show', compact('role', 'allPermissions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $role            = $this->RoleService->getRoleById($role->id);
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
            $data['permissions'] = $request->input('permissions', []);
            $this->RoleService->updateRole($roleId, $data);

            return jsonSuccess('Data berhasil diperbarui.', route('sys.roles.index'));
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        try {
            $this->RoleService->deleteRole($role->id);

            return jsonSuccess('Data berhasil dihapus.', route('sys.roles.index'));
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }
}
