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

            // ── DASHBOARD ─────────────────────────────────────────────────────
            ['name' => 'lab.dashboard.view', 'category' => 'Layanan Lab', 'sub_category' => 'Dashboard', 'description' => 'Melihat dashboard penggunaan laboratorium'],

            // ── MASTER DATA ── Data Lab ───────────────────────────────────────
            ['name' => 'lab.labs.view', 'category' => 'Layanan Lab', 'sub_category' => 'Master Data – Data Lab', 'description' => 'Melihat daftar lab'],
            ['name' => 'lab.labs.data', 'category' => 'Layanan Lab', 'sub_category' => 'Master Data – Data Lab', 'description' => 'Mengambil data lab (DataTables)'],
            ['name' => 'lab.labs.create', 'category' => 'Layanan Lab', 'sub_category' => 'Master Data – Data Lab', 'description' => 'Menambah lab baru'],
            ['name' => 'lab.labs.update', 'category' => 'Layanan Lab', 'sub_category' => 'Master Data – Data Lab', 'description' => 'Mengubah data lab'],
            ['name' => 'lab.labs.delete', 'category' => 'Layanan Lab', 'sub_category' => 'Master Data – Data Lab', 'description' => 'Menghapus data lab'],
            ['name' => 'lab.labs.teams.view', 'category' => 'Layanan Lab', 'sub_category' => 'Master Data – Data Lab', 'description' => 'Melihat tim pengelola lab'],
            ['name' => 'lab.labs.teams.data', 'category' => 'Layanan Lab', 'sub_category' => 'Master Data – Data Lab', 'description' => 'Mengambil data tim lab (DataTables)'],
            ['name' => 'lab.labs.inventaris.view', 'category' => 'Layanan Lab', 'sub_category' => 'Master Data – Data Lab', 'description' => 'Melihat inventaris per lab'],
            ['name' => 'lab.labs.inventaris.data', 'category' => 'Layanan Lab', 'sub_category' => 'Master Data – Data Lab', 'description' => 'Mengambil data inventaris lab (DataTables)'],

            // ── MASTER DATA ── Data Inventaris ────────────────────────────────
            ['name' => 'lab.inventaris.view', 'category' => 'Layanan Lab', 'sub_category' => 'Master Data – Inventaris', 'description' => 'Melihat daftar alat dan bahan lab'],
            ['name' => 'lab.inventaris.data', 'category' => 'Layanan Lab', 'sub_category' => 'Master Data – Inventaris', 'description' => 'Mengambil data inventaris (DataTables)'],
            ['name' => 'lab.inventaris.create', 'category' => 'Layanan Lab', 'sub_category' => 'Master Data – Inventaris', 'description' => 'Menambah data inventaris baru'],
            ['name' => 'lab.inventaris.update', 'category' => 'Layanan Lab', 'sub_category' => 'Master Data – Inventaris', 'description' => 'Mengubah kondisi atau detail inventaris'],
            ['name' => 'lab.inventaris.delete', 'category' => 'Layanan Lab', 'sub_category' => 'Master Data – Inventaris', 'description' => 'Menghapus data inventaris'],
            ['name' => 'lab.inventaris.export', 'category' => 'Layanan Lab', 'sub_category' => 'Master Data – Inventaris', 'description' => 'Mengekspor daftar inventaris ke Excel'],
            ['name' => 'lab.inventaris.import', 'category' => 'Layanan Lab', 'sub_category' => 'Master Data – Inventaris', 'description' => 'Mengimpor data inventaris dari file'],

            // ── MASTER DATA ── Jadwal Perkuliahan ─────────────────────────────
            ['name' => 'lab.jadwal.view', 'category' => 'Layanan Lab', 'sub_category' => 'Master Data – Jadwal Perkuliahan', 'description' => 'Melihat ketersediaan jadwal ruangan lab'],
            ['name' => 'lab.jadwal.data', 'category' => 'Layanan Lab', 'sub_category' => 'Master Data – Jadwal Perkuliahan', 'description' => 'Mengambil data jadwal (DataTables)'],
            ['name' => 'lab.jadwal.update', 'category' => 'Layanan Lab', 'sub_category' => 'Master Data – Jadwal Perkuliahan', 'description' => 'Mengatur plotting jadwal ruangan'],

            // ── MASTER DATA ── Semester & Mata Kuliah ─────────────────────────
            ['name' => 'lab.semesters.view', 'category' => 'Layanan Lab', 'sub_category' => 'Master Data – Semester', 'description' => 'Melihat daftar semester aktif'],
            ['name' => 'lab.semesters.data', 'category' => 'Layanan Lab', 'sub_category' => 'Master Data – Semester', 'description' => 'Mengambil data semester (DataTables)'],
            ['name' => 'lab.mata-kuliah.view', 'category' => 'Layanan Lab', 'sub_category' => 'Master Data – Mata Kuliah', 'description' => 'Melihat daftar mata kuliah'],
            ['name' => 'lab.mata-kuliah.data', 'category' => 'Layanan Lab', 'sub_category' => 'Master Data – Mata Kuliah', 'description' => 'Mengambil data mata kuliah (DataTables)'],

            // ── PEMINJAMAN LAB ────────────────────────────────────────────────
            ['name' => 'lab.kegiatan.view', 'category' => 'Layanan Lab', 'sub_category' => 'Peminjaman Lab', 'description' => 'Melihat daftar permohonan pinjam lab'],
            ['name' => 'lab.kegiatan.data', 'category' => 'Layanan Lab', 'sub_category' => 'Peminjaman Lab', 'description' => 'Mengambil data peminjaman (DataTables)'],
            ['name' => 'lab.kegiatan.update', 'category' => 'Layanan Lab', 'sub_category' => 'Peminjaman Lab', 'description' => 'Menyetujui atau memvalidasi peminjaman lab'],
            ['name' => 'lab.peminjaman.view', 'category' => 'Layanan Lab', 'sub_category' => 'Peminjaman Lab', 'description' => 'Melihat riwayat peminjaman inventaris'],
            ['name' => 'lab.peminjaman.data', 'category' => 'Layanan Lab', 'sub_category' => 'Peminjaman Lab', 'description' => 'Mengambil data peminjaman inventaris (DataTables)'],
            ['name' => 'lab.peminjaman.view-own', 'category' => 'Layanan Lab', 'sub_category' => 'Peminjaman Lab', 'description' => 'Melihat riwayat peminjaman pribadi'],
            ['name' => 'lab.peminjaman.update', 'category' => 'Layanan Lab', 'sub_category' => 'Peminjaman Lab', 'description' => 'Menyetujui atau mengembalikan inventaris'],

            // ── LOG PENGGUNAAN ────────────────────────────────────────────────
            ['name' => 'lab.log-lab.view', 'category' => 'Layanan Lab', 'sub_category' => 'Log Penggunaan Lab', 'description' => 'Melihat log riwayat penggunaan lab'],
            ['name' => 'lab.log-lab.data', 'category' => 'Layanan Lab', 'sub_category' => 'Log Penggunaan Lab', 'description' => 'Mengambil data log lab (DataTables)'],
            ['name' => 'lab.log-pc.view', 'category' => 'Layanan Lab', 'sub_category' => 'Log Penggunaan PC', 'description' => 'Melihat log penggunaan komputer di lab'],
            ['name' => 'lab.log-pc.data', 'category' => 'Layanan Lab', 'sub_category' => 'Log Penggunaan PC', 'description' => 'Mengambil data log PC (DataTables)'],

            // ── SURAT & LAPORAN ───────────────────────────────────────────────
            ['name' => 'lab.surat-bebas.view', 'category' => 'Layanan Lab', 'sub_category' => 'Surat Bebas Lab', 'description' => 'Melihat pengajuan surat bebas lab'],
            ['name' => 'lab.surat-bebas.data', 'category' => 'Layanan Lab', 'sub_category' => 'Surat Bebas Lab', 'description' => 'Mengambil data surat bebas lab (DataTables)'],
            ['name' => 'lab.laporan-kerusakan.view', 'category' => 'Layanan Lab', 'sub_category' => 'Laporan Kerusakan', 'description' => 'Melihat laporan kerusakan inventaris'],
            ['name' => 'lab.laporan-kerusakan.data', 'category' => 'Layanan Lab', 'sub_category' => 'Laporan Kerusakan', 'description' => 'Mengambil data laporan kerusakan (DataTables)'],

            // ── SOFTWARE REQUESTS ─────────────────────────────────────────────
            ['name' => 'lab.software-requests.view', 'category' => 'Layanan Lab', 'sub_category' => 'Software Requests', 'description' => 'Melihat daftar request instalasi software'],
            ['name' => 'lab.software-requests.data', 'category' => 'Layanan Lab', 'sub_category' => 'Software Requests', 'description' => 'Mengambil data request software (DataTables)'],
            ['name' => 'lab.software-requests.create', 'category' => 'Layanan Lab', 'sub_category' => 'Software Requests', 'description' => 'Mengajukan request software baru'],
            ['name' => 'lab.software-requests.update', 'category' => 'Layanan Lab', 'sub_category' => 'Software Requests', 'description' => 'Memproses / mengubah status request software'],

            // ── DATA UMUM ─────────────────────────────────────────────────────
            ['name' => 'lab.mahasiswa.view', 'category' => 'Layanan Lab', 'sub_category' => 'Data Mahasiswa', 'description' => 'Melihat daftar mahasiswa'],
            ['name' => 'lab.mahasiswa.data', 'category' => 'Layanan Lab', 'sub_category' => 'Data Mahasiswa', 'description' => 'Mengambil data mahasiswa (DataTables)'],
            ['name' => 'lab.personil.view', 'category' => 'Layanan Lab', 'sub_category' => 'Data Personil', 'description' => 'Melihat daftar personil lab'],
            ['name' => 'lab.personil.data', 'category' => 'Layanan Lab', 'sub_category' => 'Data Personil', 'description' => 'Mengambil data personil lab (DataTables)'],
            ['name' => 'lab.pengumuman.view', 'category' => 'Layanan Lab', 'sub_category' => 'Info Publik', 'description' => 'Melihat pengumuman lab'],
            ['name' => 'lab.pengumuman.data', 'category' => 'Layanan Lab', 'sub_category' => 'Info Publik', 'description' => 'Mengambil data pengumuman (DataTables)'],
            ['name' => 'lab.berita.view', 'category' => 'Layanan Lab', 'sub_category' => 'Info Publik', 'description' => 'Melihat berita lab'],
            ['name' => 'lab.berita.data', 'category' => 'Layanan Lab', 'sub_category' => 'Info Publik', 'description' => 'Mengambil data berita (DataTables)'],
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

        $teknisi = Role::where('name', 'Teknisi')->first();
        if ($teknisi) {
            $teknisi->givePermissionTo([
                'lab.dashboard.view', 'lab.inventaris.view', 'lab.inventaris.update',
                'lab.peminjaman.view', 'lab.peminjaman.update',
                'lab.kegiatan.view', 'lab.kegiatan.update',
                'lab.laporan-kerusakan.view', 'lab.log-lab.view', 'lab.log-pc.view',
            ]);
        }

        $pimpinan = Role::where('name', 'Pimpinan Unit')->first();
        if ($pimpinan) {
            $pimpinan->givePermissionTo(['lab.dashboard.view', 'lab.inventaris.view', 'lab.labs.view']);
        }
    }
}
