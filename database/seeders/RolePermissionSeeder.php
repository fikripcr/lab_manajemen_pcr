<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Artisan;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear any cached permissions/roles
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        // Create roles for the application
        $roles = [
            'mahasiswa',
            'dosen', 
            'pic_lab',
            'teknisi',
            'ka_lab',
            'kajur',
            'penyelenggara_kegiatan',
            'peserta_kegiatan'
        ];

        foreach ($roles as $roleName) {
            Role::updateOrCreate(['name' => $roleName]);
        }

        // Optionally, you can also define permissions here
        $permissions = [
            // General permissions
            'view own profile',
            'edit own profile',
            
            // Lab management permissions
            'view labs',
            'create labs',
            'edit labs',
            'delete labs',
            
            // Schedule management permissions
            'view schedules',
            'create schedules',
            'edit schedules',
            'delete schedules',
            
            // PC assignment permissions
            'view pc assignments',
            'create pc assignments',
            'edit pc assignments',
            'delete pc assignments',
            
            // Log management permissions
            'view pc logs',
            'create pc logs',
            'edit pc logs',
            'delete pc logs',
            
            // Event management permissions
            'view events',
            'create events',
            'edit events',
            'delete events',
            
            // Log lab usage permissions
            'view lab logs',
            'create lab logs',
            'edit lab logs',
            'delete lab logs',
            
            // Inventory management permissions
            'view inventory',
            'create inventory',
            'edit inventory',
            'delete inventory',
            
            // Damage report permissions
            'view damage reports',
            'create damage reports',
            'edit damage reports',
            'delete damage reports',
            
            // Software request permissions
            'view software requests',
            'create software requests',
            'edit software requests',
            'delete software requests',
        ];

        foreach ($permissions as $permissionName) {
            Permission::updateOrCreate(['name' => $permissionName]);
        }
    }
}