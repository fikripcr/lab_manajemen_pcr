<?php

namespace Database\Seeders\Sys;

use App\Models\Sys\Permission;
use App\Models\Sys\Role;
use Illuminate\Database\Seeder;

class SysSuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear any cached permissions/roles
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Super Admin role if it doesn't exist
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']);

        // Get all sys permissions and impersonate permissions
        $sysPermissions = Permission::where('name', 'like', 'sys.%')
            ->orWhere('name', 'like', 'impersonate.%')
            ->get();

        // Assign all sys and impersonate permissions to the super admin role
        $superAdminRole->syncPermissions($sysPermissions->pluck('name')->toArray());
    }
}