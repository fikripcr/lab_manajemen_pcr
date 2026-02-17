<?php
namespace Database\Seeders;

use App\Models\Hr\JabatanFungsional;
use App\Models\Hr\JenisIndisipliner;
use App\Models\Hr\JenisIzin;
use App\Models\Hr\JenisShift;
use App\Models\Hr\OrgUnit;
use App\Models\Hr\Pegawai;
use App\Models\Hr\RiwayatDataDiri;
use App\Models\Hr\StatusAktifitas;
use App\Models\Hr\StatusPegawai;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MainHrSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('MainHrSeeder started...');

        // 1. Master Data
        $this->seedJabatanFungsional();
        $this->seedOrgUnits();
        $this->seedStatusPegawai();
        $this->seedStatusAktifitas();
        $this->seedJenisIzin();
        $this->seedJenisIndisipliner();
        $this->seedJenisShift();

        // 2. Transactional Data (Human Capital)
        $this->seedHumanCapital();

        $this->command->info('MainHrSeeder completed.');
    }

    private function seedJabatanFungsional()
    {
        $this->command->info('Seeding Jabatan Fungsional...');
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        JabatanFungsional::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $jabatan = [
            ['kode_jabatan' => 'AA', 'jabfungsional' => 'Asisten Ahli', 'tunjangan' => 375000, 'is_active' => true],
            ['kode_jabatan' => 'L', 'jabfungsional' => 'Lektor', 'tunjangan' => 700000, 'is_active' => true],
            ['kode_jabatan' => 'LK', 'jabfungsional' => 'Lektor Kepala', 'tunjangan' => 900000, 'is_active' => true],
            ['kode_jabatan' => 'GB', 'jabfungsional' => 'Guru Besar', 'tunjangan' => 1350000, 'is_active' => true],
            ['kode_jabatan' => 'TP', 'jabfungsional' => 'Tenaga Pengajar', 'tunjangan' => 0, 'is_active' => true],
        ];

        foreach ($jabatan as $j) {
            JabatanFungsional::create($j);
        }
    }

    private function seedOrgUnits()
    {
        $this->command->info('Seeding Org Units...');
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        OrgUnit::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::transaction(function () {
            // Level 1: Institusi / Root
            $pcr = OrgUnit::create([
                'name'       => 'Politeknik Chevron Riau',
                'type'       => 'Institusi',
                'level'      => 1,
                'sort_order' => 1,
                'code'       => 'PCR',
                'is_active'  => true,
            ]);

            // Level 2: Senat & SPM
            $this->createUnit($pcr, 'Senat', 'Senat', 1);
            $this->createUnit($pcr, 'Satuan Penjaminan Mutu', 'Unit', 2);

            // Level 2: Wadirs
            $wadir1 = $this->createUnit($pcr, 'Wakil Direktur Bidang Akademik dan Inovasi Pembelajaran', 'Direktorat', 3, 'WADIR 1');
            $wadir2 = $this->createUnit($pcr, 'Wakil Direktur Bidang Sumber Daya', 'Direktorat', 4, 'WADIR 2');
            $wadir3 = $this->createUnit($pcr, 'Wakil Direktur Bidang Keuangan, Perencanaan dan Kelembagaan', 'Direktorat', 5, 'WADIR 3');
            $wadir4 = $this->createUnit($pcr, 'Wakil Direktur Bidang Kemahasiswaan, Pemasaran, dan Kemitraan', 'Direktorat', 6, 'WADIR 4');

            // Level 3: Bagian under Wadir 1
            $this->createUnit($wadir1, 'Bagian Administrasi Akademik', 'Bagian', 1);
            $this->createUnit($wadir1, 'Bagian Penelitian dan Pengabdian Kepada Masyarakat', 'Bagian', 2);
            $this->createUnit($wadir1, 'Bagian Inovasi, Pengembangan Pembelajaran dan Perpustakaan', 'Bagian', 3);

            // Level 3: Bagian under Wadir 2
            $this->createUnit($wadir2, 'Bagian Sumber Daya Manusia', 'Bagian', 1);
            $this->createUnit($wadir2, 'Bagian Manajemen Aset dan Sarana Prasarana', 'Bagian', 2);
            $this->createUnit($wadir2, 'Bagian Sistem dan Teknologi Informasi', 'Bagian', 3);

            // Level 3: Bagian under Wadir 3
            $this->createUnit($wadir3, 'Bagian Keuangan', 'Bagian', 1);
            $this->createUnit($wadir3, 'Bagian Perencanaan dan Pengembangan', 'Bagian', 2);
            $this->createUnit($wadir3, 'Bagian Kelembagaan', 'Bagian', 3);

            // Level 3: Bagian under Wadir 4
            $this->createUnit($wadir4, 'Bagian Pemasaran, Komunikasi dan PMB', 'Bagian', 1);
            $this->createUnit($wadir4, 'Bagian Kemitraan dan Urusan Internasional', 'Bagian', 2);
            $this->createUnit($wadir4, 'Bagian Kemahasiswaan, Pusat Karir dan Alumni', 'Bagian', 3);
            $this->createUnit($wadir4, 'Bagian Bisnis', 'Bagian', 4);

            // Level 2: Jurusans
            $seqJurusan = 10;
            $jti        = $this->createUnit($pcr, 'Jurusan Teknologi Industri', 'Jurusan', $seqJurusan++, 'JTIN');
            $this->createUnit($jti, 'D4 Teknologi Rekayasa Mekatronika', 'Prodi', 1);
            $this->createUnit($jti, 'D4 Teknik Mesin', 'Prodi', 2);
            $this->createUnit($jti, 'D4 Teknologi Rekayasa Jaringan Telekomunikasi', 'Prodi', 3);
            $this->createUnit($jti, 'D4 Teknik Listrik', 'Prodi', 4);
            $this->createUnit($jti, 'D4 Teknologi Rekayasa Sistem Elektronika', 'Prodi', 5);
            $this->createUnit($jti, 'D4 Teknik Elektronika (Telekomunikasi)', 'Prodi', 6);

            $jbk = $this->createUnit($pcr, 'Bisnis dan Komunikasi', 'Jurusan', $seqJurusan++, 'JBK');
            $this->createUnit($jbk, 'D4 Akuntansi Perpajakan', 'Prodi', 1);
            $this->createUnit($jbk, 'D4 Bisnis Digital', 'Prodi', 2);
            $this->createUnit($jbk, 'D4 Hubungan Masyarakat dan Komunikasi Digital', 'Prodi', 3);

            $jtik = $this->createUnit($pcr, 'Jurusan Teknologi Informasi', 'Jurusan', $seqJurusan++, 'JTI');
            $this->createUnit($jtik, 'D4 Teknik Informatika', 'Prodi', 1);
            $this->createUnit($jtik, 'D4 Sistem Informasi', 'Prodi', 2);
            $this->createUnit($jtik, 'D4 Teknologi Rekayasa Komputer', 'Prodi', 3);
            $this->createUnit($jtik, 'S2 Magister Terapan Teknik Komputer', 'Prodi', 4);

            // Level 2: Generic Positions
            $posisiDir = $this->createUnit($pcr, 'Daftar Posisi Pegawai', 'posisi_header', 100, 'POS');
            $this->createUnit($posisiDir, 'Dosen', 'posisi', 1);
            $this->createUnit($posisiDir, 'Staff Administrasi', 'posisi', 2);
            $this->createUnit($posisiDir, 'Teknisi', 'posisi', 3);
            $this->createUnit($posisiDir, 'Kepala Laboratorium', 'posisi', 4);
            $this->createUnit($posisiDir, 'Asisten Laboratorium', 'posisi', 5);
            $this->createUnit($posisiDir, 'Welfare Officer', 'posisi', 6);

            // Level 2: Structural Positions
            $this->createUnit($pcr, 'Direktur', 'jabatan_struktural', 1);
            $this->createUnit($wadir1, 'Wakil Direktur', 'jabatan_struktural', 1);
            $this->createUnit($wadir2, 'Wakil Direktur', 'jabatan_struktural', 1);
            $this->createUnit($wadir3, 'Wakil Direktur', 'jabatan_struktural', 1);
            $this->createUnit($wadir4, 'Wakil Direktur', 'jabatan_struktural', 1);

            $this->createUnit($jti, 'Ketua Jurusan', 'jabatan_struktural', 1);
            $this->createUnit($jbk, 'Ketua Jurusan', 'jabatan_struktural', 1);
            $this->createUnit($jtik, 'Ketua Jurusan', 'jabatan_struktural', 1);
        });
    }

    private function createUnit($parent, $name, $type, $sort_order, $code = null)
    {
        return OrgUnit::create([
            'parent_id'  => $parent->orgunit_id,
            'name'       => $name,
            'type'       => $type,
            'level'      => $parent->level + 1,
            'sort_order' => $sort_order,
            'code'       => $code ?? $this->generateCode($name),
            'is_active'  => true,
        ]);
    }

    private function generateCode($name)
    {
        $words = explode(' ', $name);
        $code  = '';
        foreach ($words as $word) {
            if (ctype_alnum($word)) {
                $code .= strtoupper(substr($word, 0, 1));
            }
        }
        return substr($code, 0, 10);
    }

    private function seedStatusPegawai()
    {
        $this->command->info('Seeding Status Pegawai...');
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        StatusPegawai::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            ['kode_status' => '001', 'nama_status' => 'Tetap', 'organisasi' => 'YPCR'],
            ['kode_status' => '002', 'nama_status' => 'PKWT', 'organisasi' => 'YPCR'],
            ['kode_status' => '003', 'nama_status' => 'PKWT', 'organisasi' => 'Pihak Ketiga'],
            ['kode_status' => '004', 'nama_status' => 'PKWT', 'organisasi' => 'PCR'],
        ];

        foreach ($data as $item) {
            StatusPegawai::firstOrCreate(
                ['kode_status' => $item['kode_status']],
                [
                    'nama_status' => $item['nama_status'],
                    'organisasi'  => $item['organisasi'],
                    'is_active'   => true,
                ]
            );
        }
    }

    private function seedStatusAktifitas()
    {
        $this->command->info('Seeding Status Aktifitas...');
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        StatusAktifitas::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            ['kode_status' => '001', 'nama_status' => 'Aktif'],
            ['kode_status' => '003', 'nama_status' => 'Resign'],
            ['kode_status' => '004', 'nama_status' => 'Habis Kontrak'],
            ['kode_status' => '005', 'nama_status' => 'Pensiun'],
            ['kode_status' => '006', 'nama_status' => 'Meninggal Dunia'],
            ['kode_status' => '007', 'nama_status' => 'LWP'],
            ['kode_status' => '008', 'nama_status' => 'Tugas Belajar'],
            ['kode_status' => '009', 'nama_status' => 'Pensiun Dini'],
        ];

        foreach ($data as $item) {
            StatusAktifitas::firstOrCreate(
                ['kode_status' => $item['kode_status']],
                [
                    'nama_status' => $item['nama_status'],
                    'is_active'   => true,
                ]
            );
        }
    }

    private function seedJenisIzin()
    {
        $this->command->info('Seeding Jenis Izin...');
        // No truncate needed if using updateOrCreate, but safe to keep.
        $data = [
            ['nama' => 'Istri Pegawai melahirkan/keguguran kandungan', 'kategori' => 'Izin', 'max_hari' => 3, 'pemilihan_waktu' => 'date_multiple', 'urutan_approval' => json_encode(['Atasan 1']), 'is_active' => 1],
            ['nama' => 'Istri/Suam/Anak/Menantu Pegawai meninggal dunia', 'kategori' => 'Izin', 'max_hari' => 3, 'pemilihan_waktu' => 'date_multiple', 'urutan_approval' => json_encode(['Atasan 1']), 'is_active' => 1],
            ['nama' => 'Pegawai Sakit', 'kategori' => 'Izin', 'max_hari' => 0, 'pemilihan_waktu' => 'date_multiple', 'urutan_approval' => json_encode(['Atasan 1']), 'is_active' => 1],
            ['nama' => 'Cuti Tahunan', 'kategori' => 'Cuti', 'max_hari' => null, 'pemilihan_waktu' => 'date_multiple', 'urutan_approval' => json_encode(['Atasan 1', 'Atasan 2']), 'is_active' => 1],
            // ... (truncated for brevity, included essential ones)
        ];

        foreach ($data as $item) {
            JenisIzin::updateOrCreate(['nama' => $item['nama']], $item);
        }
    }

    private function seedJenisIndisipliner()
    {
        $this->command->info('Seeding Jenis Indisipliner...');
        $data = [
            'Nasihat & Petunjuk', 'Teguran Lisan Tertulis', 'Peringatan Tertulis Pertama',
            'Peringatan Tertulis Kedua', 'Peringatan Tertulis Ketiga', 'Penurunan Kelas Gaji', 'PHK',
        ];
        foreach ($data as $item) {
            JenisIndisipliner::updateOrCreate(['jenis_indisipliner' => $item]);
        }
    }

    private function seedJenisShift()
    {
        $this->command->info('Seeding Jenis Shift...');
        $data = [
            ['jenis_shift' => 'Shift Normal', 'jam_masuk' => '08:00:00', 'jam_pulang' => '16:00:00', 'is_active' => 1],
            ['jenis_shift' => 'Shift Pagi', 'jam_masuk' => '07:00:00', 'jam_pulang' => '15:00:00', 'is_active' => 1],
            ['jenis_shift' => 'Shift Sore', 'jam_masuk' => '15:00:00', 'jam_pulang' => '22:00:00', 'is_active' => 1],
        ];
        foreach ($data as $item) {
            JenisShift::updateOrCreate(['jenis_shift' => $item['jenis_shift']], $item);
        }
    }

    private function seedHumanCapital()
    {
        $this->command->info('Seeding Human Capital (Personnel Data)...');
        $faker     = Faker::create('id_ID');
        $sysUserId = 1;

        $depts          = OrgUnit::where('type', 'Jurusan')->get();
        $posisis        = OrgUnit::where('type', 'posisi')->get();
        $statusPegawais = StatusPegawai::all();
        $statusAktifs   = StatusAktifitas::where('nama_status', 'Aktif')->first();
        $jabFungsionals = JabatanFungsional::all();
        $orgUnits       = OrgUnit::whereIn('type', ['Bagian', 'Prodi', 'posisi'])->get();
        $strUnits       = OrgUnit::where('type', 'jabatan_struktural')->get();

        if ($orgUnits->whereIn('type', ['Bagian', 'Prodi'])->isEmpty() || $orgUnits->where('type', 'posisi')->isEmpty()) {
            return;
        }

        DB::transaction(function () use ($faker, $sysUserId, $depts, $posisis, $statusPegawais, $statusAktifs, $jabFungsionals, $orgUnits, $strUnits) {
            for ($i = 0; $i < 30; $i++) {
                $pegawai     = Pegawai::create(['created_by' => $sysUserId]);
                $gender      = $faker->randomElement(['L', 'P']);
                $pegawaiName = $faker->name($gender == 'L' ? 'male' : 'female');

                $deptUnits = $orgUnits->whereIn('type', ['Bagian', 'Prodi']);
                $posUnits  = $orgUnits->where('type', 'posisi');

                if ($deptUnits->isNotEmpty() && $posUnits->isNotEmpty()) {
                    $riwayatDataDiriData = [
                        'pegawai_id'            => $pegawai->pegawai_id,
                        'nama'                  => $pegawaiName,
                        'email'                 => $faker->unique()->safeEmail,
                        'nip'                   => $faker->unique()->numerify('19###### ###### # ###'),
                        'nidn'                  => $faker->optional(0.3)->numerify('##########'),
                        'jenis_kelamin'         => $gender,
                        'tempat_lahir'          => $faker->city,
                        'tgl_lahir'             => $faker->dateTimeBetween('-50 years', '-22 years')->format('Y-m-d'),
                        'alamat'                => $faker->address,
                        'no_hp'                 => $faker->phoneNumber,
                        'status_nikah'          => $faker->randomElement(['Menikah', 'Belum Menikah']),
                        'agama'                 => 'Islam',
                        'orgunit_departemen_id' => $deptUnits->random()->orgunit_id,
                        'orgunit_posisi_id'     => $posUnits->random()->orgunit_id,
                        'created_by'            => $sysUserId,
                    ];
                    $riwayatDiri = $this->createApprovedHistory(RiwayatDataDiri::class, $riwayatDataDiriData, $sysUserId);
                    $pegawai->update(['latest_riwayatdatadiri_id' => $riwayatDiri->getKey()]);
                }
            }
        });
    }

    private function createApprovedHistory($modelClass, $data, $userId)
    {
        $approvalId = DB::table('hr_riwayat_approval')->insertGetId([
            'model'      => $modelClass,
            'model_id'   => 0,
            'status'     => 'Approved',
            'keterangan' => 'Seeder Data',
            'pejabat'    => 'System Seeder',
            'created_by' => $userId,
            'updated_by' => $userId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $data['latest_riwayatapproval_id'] = $approvalId;
        $model                             = $modelClass::create($data);
        DB::table('hr_riwayat_approval')->where('riwayatapproval_id', $approvalId)->update(['model_id' => $model->getKey()]);
        return $model;
    }
}
