<?php
namespace Database\Seeders\Eoffice;

use App\Models\Eoffice\JenisLayanan;
use App\Models\Eoffice\JenisLayananDisposisi;
use App\Models\Eoffice\JenisLayananIsian;
use App\Models\Eoffice\JenisLayananPic;
use App\Models\Eoffice\KategoriIsian;
use App\Models\Eoffice\KategoriPerusahaan;
use App\Models\Eoffice\Layanan;
use App\Models\Eoffice\LayananDiskusi;
use App\Models\Eoffice\LayananStatus;
use App\Models\Eoffice\Perusahaan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EofficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('E-Office Seeder: Starting consolidation...');

        // 1. Safety Truncation
        $this->truncateEofficeTables();

        // 2. Personnel & Users
        $userFikri = User::updateOrCreate(
            ['email' => 'fikri@pcr.ac.id'],
            ['name' => 'Fikri Muhaffizh Imani', 'password' => Hash::make('password')]
        );

        $userAdmin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin System', 'password' => Hash::make('password')]
        );

        DB::table('eoffice_pegawai')->insert([
            [
                'user_id'    => $userAdmin->id,
                'nip'        => '123456789',
                'nama'       => 'Admin System',
                'inisial'    => 'ADM',
                'email'      => 'admin@example.com',
                'departemen' => 'TIK',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id'    => $userFikri->id,
                'nip'        => '19980101202301',
                'nama'       => 'Fikri Muhaffizh Imani',
                'inisial'    => 'FMI',
                'email'      => 'fikri@pcr.ac.id',
                'departemen' => 'TIK',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('eoffice_mahasiswa')->insert([
            'user_id'       => $userFikri->id,
            'nim'           => '20210001',
            'nama'          => 'Fikri Muhaffizh Imani',
            'email'         => 'fikri@pcr.ac.id',
            'program_studi' => 'Teknik Informatika',
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        // 3. Kategori Perusahaan & Perusahaan
        $catTekno      = KategoriPerusahaan::create(['nama_kategori' => 'Teknologi & Informasi']);
        $catManuf      = KategoriPerusahaan::create(['nama_kategori' => 'Manufaktur']);
        $catPemerintah = KategoriPerusahaan::create(['nama_kategori' => 'Instansi Pemerintah']);

        Perusahaan::create([
            'kategoriperusahaan_id' => $catTekno->kategoriperusahaan_id,
            'nama_perusahaan'       => 'PT. Global Inovasi',
            'alamat'                => 'Jl. Teknologi No. 1, Jakarta',
            'kota'                  => 'Jakarta',
            'telp'                  => '021-123456',
        ]);

        Perusahaan::create([
            'kategoriperusahaan_id' => $catPemerintah->kategoriperusahaan_id,
            'nama_perusahaan'       => 'Dinas Kominfo Provinsi Riau',
            'alamat'                => 'Jl. Jend. Sudirman, Pekanbaru',
            'kota'                  => 'Pekanbaru',
            'telp'                  => '0761-98765',
        ]);

        // 4. Kategori Isian (Fields)
        $fieldNik = KategoriIsian::create([
            'nama_isian'        => 'NIK / No. KTP',
            'type'              => 'number',
            'alias_on_document' => '${nik}',
        ]);

        $fieldAlasan = KategoriIsian::create([
            'nama_isian'        => 'Alasan Pengajuan',
            'type'              => 'textarea',
            'alias_on_document' => '${alasan}',
        ]);

        $fieldPelaksana = KategoriIsian::create([
            'nama_isian'        => 'Pelaksana Acara',
            'type'              => 'text',
            'alias_on_document' => '${pelaksana_acara}',
        ]);

        $fieldMulai = KategoriIsian::create([
            'nama_isian'        => 'Tanggal Mulai',
            'type'              => 'date',
            'alias_on_document' => '${tgl_mulai}',
        ]);

        // 5. Jenis Layanan
        $jlAktif = JenisLayanan::create([
            'nama_layanan'     => 'Surat Keterangan Aktif Kuliah',
            'kategori'         => 'Akademik',
            'batas_pengerjaan' => 24,
            'only_show_on'     => ['Mahasiswa'],
            'is_active'        => true,
            'is_diskusi'       => true,
        ]);

        $jlSTM = JenisLayanan::create([
            'nama_layanan'     => 'Surat Tugas Mahasiswa',
            'kategori'         => 'Administrasi',
            'bidang_terkait'   => 'BP3M',
            'batas_pengerjaan' => 144,
            'is_active'        => true,
            'is_diskusi'       => true,
        ]);

        $jlSdm = JenisLayanan::create([
            'nama_layanan'     => 'Pengajuan Cuti Pegawai',
            'kategori'         => 'SDM',
            'batas_pengerjaan' => 48,
            'only_show_on'     => ['Pegawai', 'Dosen'],
            'is_active'        => true,
        ]);

        // 6. Relationship & Configs
        // Mapping Isian
        JenisLayananIsian::create(['jenislayanan_id' => $jlAktif->jenislayanan_id, 'kategoriisian_id' => $fieldAlasan->kategoriisian_id, 'seq' => 1, 'is_required' => true]);
        JenisLayananIsian::create(['jenislayanan_id' => $jlSTM->jenislayanan_id, 'kategoriisian_id' => $fieldPelaksana->kategoriisian_id, 'seq' => 1, 'is_required' => true, 'fill_by' => 'Pemohon']);
        JenisLayananIsian::create(['jenislayanan_id' => $jlSTM->jenislayanan_id, 'kategoriisian_id' => $fieldMulai->kategoriisian_id, 'seq' => 2, 'is_required' => true, 'fill_by' => 'Pemohon']);
        JenisLayananIsian::create(['jenislayanan_id' => $jlSdm->jenislayanan_id, 'kategoriisian_id' => $fieldNik->kategoriisian_id, 'seq' => 1, 'is_required' => true]);

        // Disposisi Chain for STM
        JenisLayananDisposisi::create(['jenislayanan_id' => $jlSTM->jenislayanan_id, 'seq' => 1, 'model' => 'JabatanStruktural', 'value' => 'PIC', 'text' => 'Pilih PIC']);
        JenisLayananDisposisi::create(['jenislayanan_id' => $jlSTM->jenislayanan_id, 'seq' => 2, 'model' => 'JabatanStruktural', 'value' => 'Kepala Bagian Administrasi Akademik dan Kemahasiswaan', 'text' => 'Pilih Kabag']);

        // PICs
        JenisLayananPic::create(['jenislayanan_id' => $jlAktif->jenislayanan_id, 'user_id' => $userAdmin->id]);
        JenisLayananPic::create(['jenislayanan_id' => $jlSTM->jenislayanan_id, 'user_id' => $userFikri->id]);
        JenisLayananPic::create(['jenislayanan_id' => $jlSdm->jenislayanan_id, 'user_id' => $userAdmin->id]);

        // 7. Sample Transaction
        $layanan = Layanan::create([
            'no_layanan'      => 'STM' . date('ymd') . '001',
            'jenislayanan_id' => $jlSTM->jenislayanan_id,
            'pengusul_nama'   => 'Fikri Muhaffizh Imani',
            'pengusul_nim'    => '20210001',
            'pengusul_email'  => 'fikri@pcr.ac.id',
            'keterangan'      => 'Mohon bantuannya untuk pembuatan Surat Tugas Lomba.',
            'created_by'      => $userFikri->id,
            'pic_awal'        => $userFikri->id,
            'created_at'      => now()->subDays(2),
        ]);

        $layanan->isians()->create(['nama_isian' => 'Pelaksana Acara', 'isi' => 'Tim Robotika PCR']);
        $layanan->isians()->create(['nama_isian' => 'Tanggal Mulai', 'isi' => date('Y-m-d')]);

        // History
        $s1 = LayananStatus::create([
            'layanan_id'     => $layanan->layanan_id,
            'status_layanan' => 'Diajukan',
            'keterangan'     => 'Layanan Diajukan oleh Fikri Muhaffizh Imani',
            'created_by'     => $userFikri->id,
            'created_at'     => $layanan->created_at,
            'done_at'        => $layanan->created_at->addMinutes(5),
        ]);

        $s2 = LayananStatus::create([
            'layanan_id'     => $layanan->layanan_id,
            'status_layanan' => 'Diproses',
            'keterangan'     => 'Layanan sedang dipreview oleh PIC',
            'created_by'     => $userFikri->id,
            'created_at'     => $s1->done_at,
        ]);

        $layanan->update(['latest_layananstatus_id' => $s2->layananstatus_id]);

        // Discussion
        LayananDiskusi::create([
            'layanan_id'      => $layanan->layanan_id,
            'user_id'         => $userFikri->id,
            'pesan'           => 'Apakah berkas sertifikat perlu dilampirkan juga?',
            'status_pengirim' => 'Pemohon',
            'created_by'      => $userFikri->id,
        ]);

        $this->command->info('E-Office Seeder: Completed successfully!');
    }

    /**
     * Helper to truncate all E-Office tables
     */
    protected function truncateEofficeTables(): void
    {
        $this->command->info('Truncating E-Office tables...');

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $tables = [
            'eoffice_pegawai',
            'eoffice_mahasiswa',
            'eoffice_jenis_layanan',
            'eoffice_jenis_layanan_pic',
            'eoffice_kategori_isian',
            'eoffice_jenis_layanan_isian',
            'eoffice_layanan',
            'eoffice_layanan_status',
            'eoffice_layanan_isian',
            'eoffice_kategori_perusahaan',
            'eoffice_perusahaan',
            'eoffice_layanan_diskusi',
            'eoffice_layanan_keterlibatan',
            'eoffice_jenis_layanan_disposisi',
            'eoffice_jenis_layanan_periode',
            'eoffice_layanan_periode',
            'eoffice_tanggal_tidak_hadir',
            'eoffice_feedback',
        ];

        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
