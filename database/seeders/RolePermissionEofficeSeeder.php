<?php
namespace Database\Seeders;

use App\Models\Sys\Permission;
use App\Models\Sys\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class RolePermissionEofficeSeeder extends Seeder
{
    public function run(): void
    {
        Log::info('RolePermissionEofficeSeeder started');
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissionData = [
            ['name' => 'eoffice.dashboard.view', 'category' => 'E-Office', 'sub_category' => 'Dashboard', 'description' => 'Melihat dashboard korespondensi digital'],
            ['name' => 'eoffice.surat-masuk.view', 'category' => 'E-Office', 'sub_category' => 'Surat Masuk', 'description' => 'Melihat daftar surat masuk ke unit'],
            ['name' => 'eoffice.surat-masuk.create', 'category' => 'E-Office', 'sub_category' => 'Surat Masuk', 'description' => 'Mencatat surat masuk baru'],
            ['name' => 'eoffice.surat-masuk.update', 'category' => 'E-Office', 'sub_category' => 'Surat Masuk', 'description' => 'Mengubah detail data surat masuk'],
            ['name' => 'eoffice.surat-masuk.delete', 'category' => 'E-Office', 'sub_category' => 'Surat Masuk', 'description' => 'Menghapus catatan surat masuk'],
            ['name' => 'eoffice.surat-masuk.export', 'category' => 'E-Office', 'sub_category' => 'Surat Masuk', 'description' => 'Mengekspor agenda surat masuk'],

            ['name' => 'eoffice.surat-keluar.view', 'category' => 'E-Office', 'sub_category' => 'Surat Keluar', 'description' => 'Melihat daftar surat keluar unit'],
            ['name' => 'eoffice.surat-keluar.create', 'category' => 'E-Office', 'sub_category' => 'Surat Keluar', 'description' => 'Membuat draf surat keluar'],
            ['name' => 'eoffice.surat-keluar.update', 'category' => 'E-Office', 'sub_category' => 'Surat Keluar', 'description' => 'Mengubah detail surat keluar'],

            ['name' => 'eoffice.disposisi.view', 'category' => 'E-Office', 'sub_category' => 'Disposisi', 'description' => 'Melihat daftar disposisi surat'],
            ['name' => 'eoffice.disposisi.create', 'category' => 'E-Office', 'sub_category' => 'Disposisi', 'description' => 'Membuat instruksi disposisi baru'],
            ['name' => 'eoffice.disposisi.update', 'category' => 'E-Office', 'sub_category' => 'Disposisi', 'description' => 'Mengubah instruksi disposisi'],
        ];

        foreach ($permissionData as $permission) {
            Permission::updateOrCreate(['name' => $permission['name']], [
                'guard_name'   => 'web',
                'category'     => $permission['category'],
                'sub_category' => $permission['sub_category'],
                'description'  => $permission['description'],
            ]);
        }

        // Global Role Assignment
        $admin = Role::where('name', 'Administrator')->first();
        if ($admin) {
            $admin->givePermissionTo(array_column($permissionData, 'name'));
        }

        $pimpinan = Role::where('name', 'Pimpinan Unit')->first();
        if ($pimpinan) {
            $pimpinan->givePermissionTo(['eoffice.dashboard.view', 'eoffice.surat-masuk.view', 'eoffice.disposisi.create']);
        }
    }
}
