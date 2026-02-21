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
    public function __construct(
        protected RoleService $roleService,
        protected PermissionService $permissionService,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $roles = $this->roleService->getRoleList([]);
            return view('pages.sys.roles.index', compact('roles'));
        } catch (Exception $e) {
            logError($e);
            return redirect()->back()->with('error', 'Failed to load roles: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $role            = new Role();
            $permissions     = $this->permissionService->getAllPermissions();
            $rolePermissions = [];
            return view('pages.sys.roles.create-edit-ajax', compact('role', 'permissions', 'rolePermissions'));
        } catch (Exception $e) {
            logError($e);
            return redirect()->back()->with('error', 'Failed to load form: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request)
    {
        try {
            $data                = $request->validated();
            $data['permissions'] = $request->permissions ?? [];
            $this->roleService->createRole($data);

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
        try {
            $role           = $this->roleService->getRoleById($role->id);
            $allPermissions = $this->permissionService->getAllPermissions();

            return view('pages.sys.roles.show', compact('role', 'allPermissions'));
        } catch (Exception $e) {
            logError($e);
            return redirect()->back()->with('error', 'Failed to load role details: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        try {
            $role            = $this->roleService->getRoleById($role->id);
            $permissions     = $this->permissionService->getAllPermissions();
            $rolePermissions = $role->permissions->pluck('name')->toArray();

            return view('pages.sys.roles.create-edit-ajax', compact('role', 'permissions', 'rolePermissions'));
        } catch (Exception $e) {
            logError($e);
            return redirect()->back()->with('error', 'Failed to load edit form: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleRequest $request, Role $role)
    {
        try {
            $data                = $request->validated();
            $data['permissions'] = $request->input('permissions', []);
            $this->roleService->updateRole($role->id, $data);

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
            $this->roleService->deleteRole($role->id);

            return jsonSuccess('Data berhasil dihapus.', route('sys.roles.index'));
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }
}
