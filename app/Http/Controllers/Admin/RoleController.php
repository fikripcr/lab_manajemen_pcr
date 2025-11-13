<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoleRequest;
use App\Http\Requests\Admin\RolePermissionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{

    public function __construct()
    {
        // $this->middleware('permission:access-departemen', [
        //     'only' => ['show'],
        // ]);

        // $this->middleware('permission:manage-departemen', [
        //     'only' => ['create', 'store', 'edit', 'update', 'destroy'],
        // ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::withCount('users')->get();
        return view('pages.admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::all();
        return view('pages.admin.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request)
    {
        DB::beginTransaction();
        try {
            $role = Role::create(['name' => $request->name]);

            if ($request->has('permissions')) {
                $role->givePermissionTo($request->permissions);
            }

            DB::commit();

            return redirect()->route('roles.index')->with('success', 'Role created successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Error creating role: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        $role->load('permissions', 'users');
        $allPermissions = \Spatie\Permission\Models\Permission::all();
        return view('pages.admin.roles.show', compact('role', 'allPermissions'));
    }

    /**
     * Update permissions for the specified role.
     */
    public function updatePermissions(RolePermissionRequest $request, Role $role)
    {
        DB::beginTransaction();
        try {
            // Sync the selected permissions
            $role->syncPermissions($request->permissions ?? []);

            DB::commit();

            if(request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Permissions updated successfully.'
                ]);
            }

            return redirect()->back()->with('success', 'Permissions updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            if(request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating permissions: ' . $e->getMessage()
                ]);
            }

            return redirect()->back()->with('error', 'Error updating permissions: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $permissions     = Permission::all();
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        return view('pages.admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleRequest $request, Role $role)
    {
        DB::beginTransaction();
        try {
            $role->update(['name' => $request->name]);

            // Sync permissions
            $role->syncPermissions($request->permissions ?? []);

            DB::commit();

            return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Error updating role: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        // Prevent deletion of roles that have users assigned
        if ($role->users->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete role with assigned users.');
        }

        try {
            $role->delete();
            return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting role: ' . $e->getMessage());
        }
    }
}