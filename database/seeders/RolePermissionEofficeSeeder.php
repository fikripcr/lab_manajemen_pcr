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
            // ── DASHBOARD ─────────────────────────────────────────────────────
            ['name' => 'eoffice.dashboard.view', 'category' => 'E-Office', 'sub_category' => 'Dashboard', 'description' => 'Melihat dashboard korespondensi digital'],

            // ── MASTER DATA ── Jenis Layanan ──────────────────────────────────
            ['name' => 'eoffice.jenis-layanan.view', 'category' => 'E-Office', 'sub_category' => 'Master Data – Jenis Layanan', 'description' => 'Melihat daftar jenis layanan'],
            ['name' => 'eoffice.jenis-layanan.data', 'category' => 'E-Office', 'sub_category' => 'Master Data – Jenis Layanan', 'description' => 'Mengambil data jenis layanan (DataTables)'],
            ['name' => 'eoffice.jenis-layanan.create', 'category' => 'E-Office', 'sub_category' => 'Master Data – Jenis Layanan', 'description' => 'Menambah jenis layanan baru'],
            ['name' => 'eoffice.jenis-layanan.update', 'category' => 'E-Office', 'sub_category' => 'Master Data – Jenis Layanan', 'description' => 'Mengubah jenis layanan'],
            ['name' => 'eoffice.jenis-layanan.delete', 'category' => 'E-Office', 'sub_category' => 'Master Data – Jenis Layanan', 'description' => 'Menghapus jenis layanan'],

            // ── MASTER DATA ── Master Isian ───────────────────────────────────
            ['name' => 'eoffice.kategori-isian.view', 'category' => 'E-Office', 'sub_category' => 'Master Data – Master Isian', 'description' => 'Melihat daftar kategori isian'],
            ['name' => 'eoffice.kategori-isian.data', 'category' => 'E-Office', 'sub_category' => 'Master Data – Master Isian', 'description' => 'Mengambil data kategori isian (DataTables)'],
            ['name' => 'eoffice.kategori-isian.create', 'category' => 'E-Office', 'sub_category' => 'Master Data – Master Isian', 'description' => 'Menambah kategori isian baru'],
            ['name' => 'eoffice.kategori-isian.update', 'category' => 'E-Office', 'sub_category' => 'Master Data – Master Isian', 'description' => 'Mengubah kategori isian'],
            ['name' => 'eoffice.kategori-isian.delete', 'category' => 'E-Office', 'sub_category' => 'Master Data – Master Isian', 'description' => 'Menghapus kategori isian'],

            // ── LAYANAN SAYA / PENGAJUAN ──────────────────────────────────────
            ['name' => 'eoffice.layanan.view', 'category' => 'E-Office', 'sub_category' => 'Layanan Saya', 'description' => 'Melihat daftar pengajuan layanan milik sendiri'],
            ['name' => 'eoffice.layanan.data', 'category' => 'E-Office', 'sub_category' => 'Layanan Saya', 'description' => 'Mengambil data pengajuan (DataTables)'],
            ['name' => 'eoffice.layanan.create', 'category' => 'E-Office', 'sub_category' => 'Layanan Saya', 'description' => 'Membuat pengajuan layanan baru (Buat Pengajuan)'],
            ['name' => 'eoffice.layanan.update', 'category' => 'E-Office', 'sub_category' => 'Layanan Saya', 'description' => 'Memproses / mengubah status pengajuan'],

            // ── FEEDBACK ──────────────────────────────────────────────────────
            ['name' => 'eoffice.feedback.view', 'category' => 'E-Office', 'sub_category' => 'Feedback', 'description' => 'Melihat daftar feedback layanan'],
            ['name' => 'eoffice.feedback.data', 'category' => 'E-Office', 'sub_category' => 'Feedback', 'description' => 'Mengambil data feedback (DataTables)'],
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
