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
            // Dashboard
            ['name' => 'lab.dashboard.view', 'category' => 'Laboratorium', 'sub_category' => 'Dashboard', 'description' => 'Melihat dashboard penggunaan laboratorium'],

            // Inventaris
            ['name' => 'lab.inventaris.view', 'category' => 'Laboratorium', 'sub_category' => 'Inventaris', 'description' => 'Melihat daftar alat dan bahan lab'],
            ['name' => 'lab.inventaris.data', 'category' => 'Laboratorium', 'sub_category' => 'Inventaris', 'description' => 'Mengambil data inventaris (DataTables)'],
            ['name' => 'lab.inventaris.create', 'category' => 'Laboratorium', 'sub_category' => 'Inventaris', 'description' => 'Menambah data inventaris baru'],
            ['name' => 'lab.inventaris.update', 'category' => 'Laboratorium', 'sub_category' => 'Inventaris', 'description' => 'Mengubah kondisi atau detail alat'],
            ['name' => 'lab.inventaris.delete', 'category' => 'Laboratorium', 'sub_category' => 'Inventaris', 'description' => 'Menghapus data alat dari daftar'],
            ['name' => 'lab.inventaris.export', 'category' => 'Laboratorium', 'sub_category' => 'Inventaris', 'description' => 'Mengekspor daftar alat ke Excel'],
            ['name' => 'lab.inventaris.import', 'category' => 'Laboratorium', 'sub_category' => 'Inventaris', 'description' => 'Mengimpor data alat dari file'],

            // Peminjaman
            ['name' => 'lab.peminjaman.view', 'category' => 'Laboratorium', 'sub_category' => 'Peminjaman', 'description' => 'Melihat daftar permohonan pinjam alat'],
            ['name' => 'lab.peminjaman.data', 'category' => 'Laboratorium', 'sub_category' => 'Peminjaman', 'description' => 'Mengambil data peminjaman (DataTables)'],
            ['name' => 'lab.peminjaman.view-own', 'category' => 'Laboratorium', 'sub_category' => 'Peminjaman', 'description' => 'Melihat riwayat peminjaman pribadi'],
            ['name' => 'lab.peminjaman.update', 'category' => 'Laboratorium', 'sub_category' => 'Peminjaman', 'description' => 'Menyetujui atau mengembalikan alat'],

            // Ruangan / Jadwal
            ['name' => 'lab.jadwal.view', 'category' => 'Laboratorium', 'sub_category' => 'Jadwal', 'description' => 'Melihat ketersediaan jadwal ruangan lab'],
            ['name' => 'lab.jadwal.data', 'category' => 'Laboratorium', 'sub_category' => 'Jadwal', 'description' => 'Mengambil data jadwal (DataTables)'],
            ['name' => 'lab.jadwal.update', 'category' => 'Laboratorium', 'sub_category' => 'Jadwal', 'description' => 'Mengatur plotting jadwal ruangan'],

            // Labs
            ['name' => 'lab.labs.view', 'category' => 'Laboratorium', 'sub_category' => 'Lab', 'description' => 'Melihat daftar lab'],
            ['name' => 'lab.labs.data', 'category' => 'Laboratorium', 'sub_category' => 'Lab', 'description' => 'Mengambil data lab (DataTables)'],
            ['name' => 'lab.labs.create', 'category' => 'Laboratorium', 'sub_category' => 'Lab', 'description' => 'Menambah lab baru'],
            ['name' => 'lab.labs.update', 'category' => 'Laboratorium', 'sub_category' => 'Lab', 'description' => 'Mengubah data lab'],
            ['name' => 'lab.labs.delete', 'category' => 'Laboratorium', 'sub_category' => 'Lab', 'description' => 'Menghapus data lab'],

            // Lab Teams
            ['name' => 'lab.labs.teams.view', 'category' => 'Laboratorium', 'sub_category' => 'Lab Team', 'description' => 'Melihat tim lab'],
            ['name' => 'lab.labs.teams.data', 'category' => 'Laboratorium', 'sub_category' => 'Lab Team', 'description' => 'Mengambil data tim lab (DataTables)'],

            // Lab Inventaris
            ['name' => 'lab.labs.inventaris.view', 'category' => 'Laboratorium', 'sub_category' => 'Lab Inventaris', 'description' => 'Melihat inventaris lab'],
            ['name' => 'lab.labs.inventaris.data', 'category' => 'Laboratorium', 'sub_category' => 'Lab Inventaris', 'description' => 'Mengambil data inventaris lab (DataTables)'],

            // Software Requests
            ['name' => 'lab.software-requests.view', 'category' => 'Laboratorium', 'sub_category' => 'Software Request', 'description' => 'Melihat daftar request software'],
            ['name' => 'lab.software-requests.data', 'category' => 'Laboratorium', 'sub_category' => 'Software Request', 'description' => 'Mengambil data request software (DataTables)'],

            // Laporan Kerusakan
            ['name' => 'lab.laporan-kerusakan.view', 'category' => 'Laboratorium', 'sub_category' => 'Laporan Kerusakan', 'description' => 'Melihat laporan kerusakan'],
            ['name' => 'lab.laporan-kerusakan.data', 'category' => 'Laboratorium', 'sub_category' => 'Laporan Kerusakan', 'description' => 'Mengambil data laporan kerusakan (DataTables)'],

            // Surat Bebas Lab
            ['name' => 'lab.surat-bebas.view', 'category' => 'Laboratorium', 'sub_category' => 'Surat Bebas Lab', 'description' => 'Melihat pengajuan surat bebas lab'],
            ['name' => 'lab.surat-bebas.data', 'category' => 'Laboratorium', 'sub_category' => 'Surat Bebas Lab', 'description' => 'Mengambil data surat bebas lab (DataTables)'],

            // Mahasiswa
            ['name' => 'lab.mahasiswa.view', 'category' => 'Laboratorium', 'sub_category' => 'Mahasiswa', 'description' => 'Melihat daftar mahasiswa'],
            ['name' => 'lab.mahasiswa.data', 'category' => 'Laboratorium', 'sub_category' => 'Mahasiswa', 'description' => 'Mengambil data mahasiswa (DataTables)'],

            // Personil
            ['name' => 'lab.personil.view', 'category' => 'Laboratorium', 'sub_category' => 'Personil', 'description' => 'Melihat daftar personil lab'],
            ['name' => 'lab.personil.data', 'category' => 'Laboratorium', 'sub_category' => 'Personil', 'description' => 'Mengambil data personil lab (DataTables)'],

            // Pengumuman & Berita
            ['name' => 'lab.pengumuman.view', 'category' => 'Laboratorium', 'sub_category' => 'Pengumuman', 'description' => 'Melihat pengumuman'],
            ['name' => 'lab.pengumuman.data', 'category' => 'Laboratorium', 'sub_category' => 'Pengumuman', 'description' => 'Mengambil data pengumuman (DataTables)'],
            ['name' => 'lab.berita.view', 'category' => 'Laboratorium', 'sub_category' => 'Berita', 'description' => 'Melihat berita'],
            ['name' => 'lab.berita.data', 'category' => 'Laboratorium', 'sub_category' => 'Berita', 'description' => 'Mengambil data berita (DataTables)'],

            // Kegiatan
            ['name' => 'lab.kegiatan.view', 'category' => 'Laboratorium', 'sub_category' => 'Kegiatan', 'description' => 'Melihat daftar kegiatan di lab'],
            ['name' => 'lab.kegiatan.data', 'category' => 'Laboratorium', 'sub_category' => 'Kegiatan', 'description' => 'Mengambil data kegiatan (DataTables)'],
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
