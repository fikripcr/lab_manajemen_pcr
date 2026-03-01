<?php
namespace Database\Seeders;

use App\Models\Sys\Permission;
use App\Models\Sys\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class RolePermissionCbtSeeder extends Seeder
{
    public function run(): void
    {
        Log::info('RolePermissionCbtSeeder started');
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissionData = [
            ['name' => 'cbt.dashboard.view', 'category' => 'CBT', 'sub_category' => 'Dashboard', 'description' => 'Melihat statistik hasil ujian online'],

            ['name' => 'cbt.mata-uji.view', 'category' => 'CBT', 'sub_category' => 'Mata Uji', 'description' => 'Melihat daftar mata uji'],
            ['name' => 'cbt.mata-uji.data', 'category' => 'CBT', 'sub_category' => 'Mata Uji', 'description' => 'Mengambil data mata uji (DataTables)'],
            ['name' => 'cbt.mata-uji.create', 'category' => 'CBT', 'sub_category' => 'Mata Uji', 'description' => 'Menambah mata uji baru'],
            ['name' => 'cbt.mata-uji.update', 'category' => 'CBT', 'sub_category' => 'Mata Uji', 'description' => 'Mengubah mata uji'],
            ['name' => 'cbt.mata-uji.delete', 'category' => 'CBT', 'sub_category' => 'Mata Uji', 'description' => 'Menghapus mata uji'],

            ['name' => 'cbt.soal.view', 'category' => 'CBT', 'sub_category' => 'Bank Soal', 'description' => 'Melihat daftar koleksi soal'],
            ['name' => 'cbt.soal.data', 'category' => 'CBT', 'sub_category' => 'Bank Soal', 'description' => 'Mengambil data soal (DataTables)'],
            ['name' => 'cbt.soal.create', 'category' => 'CBT', 'sub_category' => 'Bank Soal', 'description' => 'Menambah butir soal baru'],
            ['name' => 'cbt.soal.update', 'category' => 'CBT', 'sub_category' => 'Bank Soal', 'description' => 'Mengubah butir soal'],
            ['name' => 'cbt.soal.delete', 'category' => 'CBT', 'sub_category' => 'Bank Soal', 'description' => 'Menghapus butir soal'],
            ['name' => 'cbt.soal.import', 'category' => 'CBT', 'sub_category' => 'Bank Soal', 'description' => 'Mengimpor soal dari file'],

            ['name' => 'cbt.paket.view', 'category' => 'CBT', 'sub_category' => 'Paket Ujian', 'description' => 'Melihat daftar paket ujian'],
            ['name' => 'cbt.paket.data', 'category' => 'CBT', 'sub_category' => 'Paket Ujian', 'description' => 'Mengambil data paket ujian (DataTables)'],
            ['name' => 'cbt.paket.create', 'category' => 'CBT', 'sub_category' => 'Paket Ujian', 'description' => 'Membuat paket ujian baru'],
            ['name' => 'cbt.paket.update', 'category' => 'CBT', 'sub_category' => 'Paket Ujian', 'description' => 'Mengatur isi paket ujian'],
            ['name' => 'cbt.paket.delete', 'category' => 'CBT', 'sub_category' => 'Paket Ujian', 'description' => 'Menghapus paket ujian'],

            ['name' => 'cbt.jadwal.view', 'category' => 'CBT', 'sub_category' => 'Jadwal Ujian', 'description' => 'Melihat daftar jadwal sesi ujian'],
            ['name' => 'cbt.jadwal.data', 'category' => 'CBT', 'sub_category' => 'Jadwal Ujian', 'description' => 'Mengambil data jadwal (DataTables)'],
            ['name' => 'cbt.jadwal.create', 'category' => 'CBT', 'sub_category' => 'Jadwal Ujian', 'description' => 'Menjadwalkan ujian baru'],
            ['name' => 'cbt.jadwal.update', 'category' => 'CBT', 'sub_category' => 'Jadwal Ujian', 'description' => 'Mengubah jadwal ujian'],
            ['name' => 'cbt.jadwal.delete', 'category' => 'CBT', 'sub_category' => 'Jadwal Ujian', 'description' => 'Membangat/menghapus jadwal'],

            ['name' => 'cbt.hasil.view', 'category' => 'CBT', 'sub_category' => 'Hasil Ujian', 'description' => 'Melihat nilai ujian peserta'],
            ['name' => 'cbt.hasil.export', 'category' => 'CBT', 'sub_category' => 'Hasil Ujian', 'description' => 'Mengekspor rekap nilai'],
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
            $pimpinan->givePermissionTo(['cbt.dashboard.view', 'cbt.hasil.view']);
        }
    }
}
