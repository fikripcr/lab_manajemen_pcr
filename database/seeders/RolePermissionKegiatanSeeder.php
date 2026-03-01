<?php
namespace Database\Seeders;

use App\Models\Sys\Permission;
use App\Models\Sys\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

/**
 * Seeder for Kegiatan (Event / Rapat / Buku Tamu) module
 * Maps to the "Kegiatan" nav menu in the sidebar.
 */
class RolePermissionKegiatanSeeder extends Seeder
{
    public function run(): void
    {
        Log::info('RolePermissionKegiatanSeeder started');
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissionData = [
            // ── LIST KEGIATAN ─────────────────────────────────────────────────
            ['name' => 'kegiatan.index.view', 'category' => 'Kegiatan', 'sub_category' => 'List Kegiatan', 'description' => 'Melihat daftar semua kegiatan'],
            ['name' => 'kegiatan.index.data', 'category' => 'Kegiatan', 'sub_category' => 'List Kegiatan', 'description' => 'Mengambil data kegiatan (DataTables)'],
            ['name' => 'kegiatan.index.create', 'category' => 'Kegiatan', 'sub_category' => 'List Kegiatan', 'description' => 'Menambah kegiatan baru'],
            ['name' => 'kegiatan.index.update', 'category' => 'Kegiatan', 'sub_category' => 'List Kegiatan', 'description' => 'Mengubah detail kegiatan'],
            ['name' => 'kegiatan.index.delete', 'category' => 'Kegiatan', 'sub_category' => 'List Kegiatan', 'description' => 'Menghapus kegiatan'],

            // ── MANAJEMEN RAPAT ───────────────────────────────────────────────
            ['name' => 'kegiatan.rapat.view', 'category' => 'Kegiatan', 'sub_category' => 'Manajemen Rapat', 'description' => 'Melihat daftar rapat'],
            ['name' => 'kegiatan.rapat.data', 'category' => 'Kegiatan', 'sub_category' => 'Manajemen Rapat', 'description' => 'Mengambil data rapat (DataTables)'],
            ['name' => 'kegiatan.rapat.create', 'category' => 'Kegiatan', 'sub_category' => 'Manajemen Rapat', 'description' => 'Membuat agenda / undangan rapat'],
            ['name' => 'kegiatan.rapat.update', 'category' => 'Kegiatan', 'sub_category' => 'Manajemen Rapat', 'description' => 'Mengubah detail rapat (notulensi, peserta)'],
            ['name' => 'kegiatan.rapat.delete', 'category' => 'Kegiatan', 'sub_category' => 'Manajemen Rapat', 'description' => 'Menghapus entri rapat'],

            // ── BUKU TAMU ─────────────────────────────────────────────────────
            ['name' => 'kegiatan.tamu.view', 'category' => 'Kegiatan', 'sub_category' => 'Buku Tamu', 'description' => 'Melihat daftar rekap tamu'],
            ['name' => 'kegiatan.tamu.data', 'category' => 'Kegiatan', 'sub_category' => 'Buku Tamu', 'description' => 'Mengambil data buku tamu (DataTables)'],
            ['name' => 'kegiatan.tamu.create', 'category' => 'Kegiatan', 'sub_category' => 'Buku Tamu', 'description' => 'Menambah entri tamu baru'],
            ['name' => 'kegiatan.tamu.update', 'category' => 'Kegiatan', 'sub_category' => 'Buku Tamu', 'description' => 'Mengubah data tamu'],
            ['name' => 'kegiatan.tamu.delete', 'category' => 'Kegiatan', 'sub_category' => 'Buku Tamu', 'description' => 'Menghapus entri tamu'],
        ];

        foreach ($permissionData as $permission) {
            Permission::updateOrCreate(['name' => $permission['name']], [
                'guard_name'   => 'web',
                'category'     => $permission['category'],
                'sub_category' => $permission['sub_category'],
                'description'  => $permission['description'],
            ]);
        }

        $admin = Role::where('name', 'Administrator')->first();
        if ($admin) {
            $admin->givePermissionTo(array_column($permissionData, 'name'));
        }

        $pimpinan = Role::where('name', 'Pimpinan Unit')->first();
        if ($pimpinan) {
            $pimpinan->givePermissionTo(['kegiatan.rapat.view', 'kegiatan.index.view', 'kegiatan.tamu.view']);
        }
    }
}
