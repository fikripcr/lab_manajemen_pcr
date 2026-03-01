<?php
namespace Database\Seeders;

use App\Models\Sys\Permission;
use App\Models\Sys\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class RolePermissionLabSeeder extends Seeder
{
    public function run(): void
    {
        Log::info('RolePermissionLabSeeder started');
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Module Specific Role
        Role::firstOrCreate(['name' => 'Teknisi']);

        $permissionData = [
            ['name' => 'lab.dashboard.view', 'category' => 'Laboratorium', 'sub_category' => 'Dashboard', 'description' => 'Melihat dashboard penggunaan laboratorium'],
            ['name' => 'lab.inventaris.view', 'category' => 'Laboratorium', 'sub_category' => 'Inventaris', 'description' => 'Melihat daftar alat dan bahan lab'],
            ['name' => 'lab.inventaris.create', 'category' => 'Laboratorium', 'sub_category' => 'Inventaris', 'description' => 'Menambah data inventaris baru'],
            ['name' => 'lab.inventaris.update', 'category' => 'Laboratorium', 'sub_category' => 'Inventaris', 'description' => 'Mengubah kondisi atau detail alat'],
            ['name' => 'lab.inventaris.delete', 'category' => 'Laboratorium', 'sub_category' => 'Inventaris', 'description' => 'Menghapus data alat dari daftar'],
            ['name' => 'lab.inventaris.export', 'category' => 'Laboratorium', 'sub_category' => 'Inventaris', 'description' => 'Mengekspor daftar alat ke Excel'],
            ['name' => 'lab.inventaris.import', 'category' => 'Laboratorium', 'sub_category' => 'Inventaris', 'description' => 'Mengimpor data alat dari file'],

            ['name' => 'lab.peminjaman.view', 'category' => 'Laboratorium', 'sub_category' => 'Peminjaman', 'description' => 'Melihat daftar permohonan pinjam alat'],
            ['name' => 'lab.peminjaman.view-own', 'category' => 'Laboratorium', 'sub_category' => 'Peminjaman', 'description' => 'Melihat riwayat peminjaman pribadi'],
            ['name' => 'lab.peminjaman.update', 'category' => 'Laboratorium', 'sub_category' => 'Peminjaman', 'description' => 'Menyetujui atau mengembalikan alat'],

            ['name' => 'lab.ruangan.view', 'category' => 'Laboratorium', 'sub_category' => 'Ruangan', 'description' => 'Melihat ketersediaan jadwal ruangan lab'],
            ['name' => 'lab.ruangan.update', 'category' => 'Laboratorium', 'sub_category' => 'Ruangan', 'description' => 'Mengatur plotting jadwal ruangan'],

            ['name' => 'lab.kegiatan.view', 'category' => 'Laboratorium', 'sub_category' => 'Kegiatan', 'description' => 'Melihat daftar kegiatan di lab'],
            ['name' => 'lab.kegiatan.update', 'category' => 'Laboratorium', 'sub_category' => 'Kegiatan', 'description' => 'Memvalidasi pelaksanaan kegiatan'],
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
            $pimpinan->givePermissionTo(['lab.dashboard.view', 'lab.inventaris.view', 'lab.ruangan.view']);
        }
    }
}
