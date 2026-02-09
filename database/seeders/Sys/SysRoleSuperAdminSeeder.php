<?php
namespace Database\Seeders\Sys;

use App\Models\Sys\Permission;
use App\Models\Sys\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SysRoleSuperAdminSeeder extends Seeder
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

        // Create or update the admin user
        $admin = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name'              => 'Super Administrator',
                'password'          => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Assign the super admin role to the user
        $admin->assignRole('super_admin');
    }
}
