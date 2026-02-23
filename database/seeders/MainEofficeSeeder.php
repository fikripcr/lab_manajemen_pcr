<?php
namespace Database\Seeders;

use App\Models\Eoffice\JenisLayanan;
use App\Models\Eoffice\JenisLayananDisposisi;
use App\Models\Eoffice\JenisLayananIsian;
use App\Models\Eoffice\JenisLayananPic;
use App\Models\Eoffice\KategoriIsian;
use App\Models\Eoffice\Layanan;
use App\Models\Eoffice\LayananDiskusi;
use App\Models\Eoffice\LayananStatus;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MainEofficeSeeder extends Seeder
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

        // NOTE: eoffice_pegawai and eoffice_mahasiswa tables removed — now shared entities
        // Pegawai and mahasiswa data is managed by HR and shared seeders

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

        // 7. Dynamic Transactions for ALL Service Types
        $allServices = JenisLayanan::where('is_active', true)->get();

        foreach ($allServices as $service) {
            $user = $userFikri; // Default applicant

            // Create Transaction
            $layanan = Layanan::create([
                'no_layanan'      => 'T-' . $service->kategori . '-' . date('ymd') . rand(100, 999),
                'jenislayanan_id' => $service->jenislayanan_id,
                'pengusul_nama'   => $user->nama ?? $user->name,
                'pengusul_nim'    => '20210001', // Dummy
                'pengusul_email'  => $user->email,
                'keterangan'      => 'Pengajuan testing otomatis seeder untuk ' . $service->nama_layanan,
                'created_by'      => $user->id,
                'pic_awal'        => $service->pics->first()->user_id ?? $userAdmin->id,
                'created_at'      => now(),
            ]);

            // Fill Fields (Isian)
            foreach ($service->isians as $field_config) {
                $dummyValue = '-';
                $fieldInfo  = $field_config->kategoriIsian;

                switch ($fieldInfo->type) {
                    case 'number':
                        $dummyValue = '1234567890';
                        break;
                    case 'date':
                        $dummyValue = date('Y-m-d');
                        break;
                    case 'textarea':
                        $dummyValue = "Ini adalah contoh isian paragraf untuk " . $fieldInfo->nama_isian;
                        break;
                    default:
                        $dummyValue = "Contoh " . $fieldInfo->nama_isian;
                }

                $layanan->isians()->create([
                    'nama_isian' => $fieldInfo->nama_isian,
                    'isi'        => $dummyValue,
                ]);
            }

            // Create Initial History
            $s1 = LayananStatus::create([
                'layanan_id'     => $layanan->layanan_id,
                'status_layanan' => 'Diajukan',
                'keterangan'     => 'Layanan Diajukan Otomatis oleh Seeder',
                'created_by'     => $user->id,
                'created_at'     => $layanan->created_at,
                'done_at'        => $layanan->created_at->addMinutes(1),
            ]);

            $layanan->update(['latest_layananstatus_id' => $s1->layananstatus_id]);

            // If it has discussion, add dummy chat
            if ($service->is_diskusi) {
                LayananDiskusi::create([
                    'layanan_id'      => $layanan->layanan_id,
                    'user_id'         => $user->id,
                    'pesan'           => 'Halo admin, apakah berkas ini sudah sesuai?',
                    'status_pengirim' => 'Pemohon',
                    'created_by'      => $user->id,
                ]);
            }
        }

        $this->command->info('E-Office Seeder: Completed successfully with transactions for all types!');
    }

    /**
     * Helper to truncate all E-Office tables
     */
    protected function truncateEofficeTables(): void
    {
        $this->command->info('Truncating E-Office tables...');

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $tables = [
            // eoffice_pegawai and eoffice_mahasiswa removed — now shared tables
            'eoffice_jenis_layanan',
            'eoffice_jenis_layanan_pic',
            'eoffice_kategori_isian',
            'eoffice_jenis_layanan_isian',
            'eoffice_layanan',
            'eoffice_layanan_status',
            'eoffice_layanan_isian',
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
