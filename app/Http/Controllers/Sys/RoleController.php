<?php
namespace App\Http\Controllers\Sys;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RolePermissionRequest;
use App\Http\Requests\Admin\RoleRequest;
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
        $roles = Role::select('id', 'name')
            ->withCount('users')
            ->get();
        return view('pages.sys.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::all();
        return view('pages.sys.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request)
    {
        DB::beginTransaction();
        try {
            $role = Role::create(['name' => $request->name]);

            if ($request->has('permissions') && is_array($request->permissions)) {
                // Ensure we have valid permission names
                $permissions = array_values(array_filter($request->permissions, function ($permission) {
                    return ! empty($permission);
                }));
                $role->givePermissionTo($permissions);
            }

            DB::commit();

            return redirect()->route('roles.index')->with('success', 'Peran berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal membuat peran: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        $role->load('permissions', 'users');
        $allPermissions = \Spatie\Permission\Models\Permission::all();
        return view('pages.sys.roles.show', compact('role', 'allPermissions'));
    }

    /**
     * Update permissions for the specified role.
     */
    public function updatePermissions(RolePermissionRequest $request, Role $role)
    {
        DB::beginTransaction();
        try {
            // Sync the selected permissions with proper array handling
            $permissions = $request->permissions ?? [];
            if (is_array($permissions)) {
                // Ensure we have valid permission names
                $permissions = array_values(array_filter($permissions, function ($permission) {
                    return ! empty($permission);
                }));
            }
            $role->syncPermissions($permissions);

            DB::commit();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Permissions updated successfully.',
                ]);
            }

            return redirect()->back()->with('success', 'Permissions updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui izin: ' . $e->getMessage(),
                ]);
            }

            return redirect()->back()->with('error', 'Gagal memperbarui izin: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $permissions     = Permission::all();
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        return view('pages.sys.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleRequest $request, Role $role)
    {
        DB::beginTransaction();
        try {
            $role->update(['name' => $request->name]);

            // Sync permissions with proper array handling
            $permissions = $request->permissions ?? [];
            if (is_array($permissions)) {
                // Ensure we have valid permission names
                $permissions = array_values(array_filter($permissions, function ($permission) {
                    return ! empty($permission);
                }));
            }
            $role->syncPermissions($permissions);

            DB::commit();

            return redirect()->route('roles.index')->with('success', 'Peran berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal memperbarui peran: ' . $e->getMessage());
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
            return redirect()->route('roles.index')->with('success', 'Peran berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus peran: ' . $e->getMessage());
        }
    }
}
