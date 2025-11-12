<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:manage permissions']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::withCount('roles')->get();
        return view('pages.admin.permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.admin.permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name',
        ]);

        try {
            Permission::create(['name' => $request->name]);

            return redirect()->route('permissions.index')->with('success', 'Permission created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error creating permission: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission)
    {
        return view('pages.admin.permissions.show', compact('permission'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        return view('pages.admin.permissions.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name,' . $permission->id,
        ]);

        try {
            $permission->update(['name' => $request->name]);

            return redirect()->route('permissions.index')->with('success', 'Permission updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating permission: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        // Prevent deletion of permissions that are assigned to roles
        if ($permission->roles->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete permission that is assigned to roles.');
        }

        try {
            $permission->delete();
            return redirect()->route('permissions.index')->with('success', 'Permission deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting permission: ' . $e->getMessage());
        }
    }
}
