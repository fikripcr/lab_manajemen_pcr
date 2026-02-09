<?php
namespace Database\Seeders\Hr;

use App\Models\Hr\JabatanFungsional;
use App\Models\Hr\Keluarga;
use App\Models\Hr\OrgUnit;
use App\Models\Hr\Pegawai;
use App\Models\Hr\PengembanganDiri;
use App\Models\Hr\RiwayatDataDiri;
use App\Models\Hr\RiwayatInpassing;
use App\Models\Hr\RiwayatJabFungsional;
use App\Models\Hr\RiwayatPendidikan;
use App\Models\Hr\RiwayatPenugasan;
use App\Models\Hr\RiwayatStatAktifitas;
use App\Models\Hr\RiwayatStatPegawai;
use App\Models\Hr\StatusAktifitas;
use App\Models\Hr\StatusPegawai;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HumanCapitalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Ensure we have user 1 for created_by
        $sysUserId = 1;

        // Fetch Master Data
        $depts          = OrgUnit::where('type', 'Jurusan')->get();
        $posisis        = OrgUnit::where('type', 'posisi')->get();
        $statusPegawais = StatusPegawai::all();
        $statusAktifs   = StatusAktifitas::where('nama_status', 'Aktif')->first();
        $jabFungsionals = JabatanFungsional::all();
        $orgUnits       = OrgUnit::whereIn('type', ['Bagian', 'Prodi', 'posisi'])->get();
        $strUnits       = OrgUnit::where('type', 'jabatan_struktural')->get();

        // If no master data, we can't seed properly
        if ($orgUnits->whereIn('type', ['Bagian', 'Prodi'])->isEmpty() || $orgUnits->where('type', 'posisi')->isEmpty()) {
            $this->command->error('HR Master Data (Bagian/Prodi/Posisi) missing. Please ensure HrOrgUnitSeeder ran.');
            return;
        }

        if ($statusPegawais->isEmpty()) {
            $this->command->error('Status Pegawai Master Data missing.');
            return;
        }

        DB::transaction(function () use ($faker, $sysUserId, $depts, $posisis, $statusPegawais, $statusAktifs, $jabFungsionals, $orgUnits, $strUnits) {

            for ($i = 0; $i < 30; $i++) {
                // 1. Create Pegawai Skeleton
                $pegawai = Pegawai::create(['created_by' => $sysUserId]);

                // 2. Data Diri
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
                        'orgunit_departemen_id' => $deptUnits->random()->org_unit_id,
                        'orgunit_posisi_id'     => $posUnits->random()->org_unit_id,
                        'created_by'            => $sysUserId,
                    ];
                    $riwayatDiri = $this->createApprovedHistory(RiwayatDataDiri::class, $riwayatDataDiriData, $sysUserId);
                    $pegawai->update(['latest_riwayatdatadiri_id' => $riwayatDiri->getKey()]);
                }
                // 3. Pendidikan (1-2 records)
                $numPend    = $faker->numberBetween(1, 2);
                $lastPendId = null;
                for ($j = 0; $j < $numPend; $j++) {
                    $pendData = [
                        'pegawai_id'         => $pegawai->pegawai_id,
                        'jenjang_pendidikan' => $j == 0 ? 'S1' : 'S2',
                        'nama_pt'            => 'Universitas ' . $faker->city,
                        'thn_lulus'          => $faker->year(),
                        'tgl_ijazah'         => $faker->date(),
                        'bidang_ilmu'        => $faker->jobTitle,
                        'created_by'         => $sysUserId,
                    ];
                    $riwayatPend = $this->createApprovedHistory(RiwayatPendidikan::class, $pendData, $sysUserId);
                    $lastPendId  = $riwayatPend->getKey();
                }
                $pegawai->update(['latest_riwayatpendidikan_id' => $lastPendId]);

                // 4. Jabatan Fungsional (Random for Lecturers)
                if ($jabFungsionals->isNotEmpty() && $faker->boolean(60)) {
                    $riwayatJabFung = $this->createApprovedHistory(RiwayatJabFungsional::class, [
                        'pegawai_id'       => $pegawai->pegawai_id,
                        'jabfungsional_id' => $jabFungsionals->random()->jabfungsional_id,
                        'tmt'              => $faker->dateTimeBetween('-5 years', 'now')->format('Y-m-d'),
                        'no_sk_internal'   => $faker->bothify('SK/###/YPCR/????'),
                        'created_by'       => $sysUserId,
                    ], $sysUserId);
                    $pegawai->update(['latest_riwayatjabfungsional_id' => $riwayatJabFung->getKey()]);
                }

                // 5. Status Pegawai
                $statData = [
                    'pegawai_id'       => $pegawai->pegawai_id,
                    'statuspegawai_id' => $statusPegawais->random()->statuspegawai_id,
                    'tmt'              => $faker->dateTimeBetween('-5 years', 'now')->format('Y-m-d'),
                    'no_sk'            => $faker->bothify('SK/###/YPCR/????'),
                    'created_by'       => $sysUserId,
                ];
                $riwayatStat = $this->createApprovedHistory(RiwayatStatPegawai::class, $statData, $sysUserId);
                $pegawai->update(['latest_riwayatstatpegawai_id' => $riwayatStat->getKey()]);

                // 6. Status Aktifitas (Aktif)
                if ($statusAktifs) {
                    $statAktifData = [
                        'pegawai_id'         => $pegawai->pegawai_id,
                        'statusaktifitas_id' => $statusAktifs->statusaktifitas_id,
                        'tmt'                => $faker->dateTimeBetween('-5 years', 'now')->format('Y-m-d'),
                        'created_by'         => $sysUserId,
                    ];
                    $riwayatAktif = $this->createApprovedHistory(RiwayatStatAktifitas::class, $statAktifData, $sysUserId);
                    $pegawai->update(['latest_riwayatstataktifitas_id' => $riwayatAktif->getKey()]);
                }

                // 7. Jabatan Struktural (Random)
                if ($strUnits->isNotEmpty() && $faker->boolean(20)) {
                    $strUnit    = $strUnits->random();
                    $jabStrData = [
                        'pegawai_id'  => $pegawai->pegawai_id,
                        'org_unit_id' => $strUnit->org_unit_id,
                        'tgl_awal'    => $faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
                        'no_sk'       => $faker->bothify('SK/###/YPCR/????'),
                        'created_by'  => $sysUserId,
                    ];
                    $riwayatJabStr = $this->createApprovedHistory(\App\Models\Hr\RiwayatJabStruktural::class, $jabStrData, $sysUserId);
                    $pegawai->update(['latest_riwayatjabstruktural_id' => $riwayatJabStr->getKey()]);
                }

                // 8. Keluarga
                $numFam = $faker->numberBetween(0, 3);
                for ($k = 0; $k < $numFam; $k++) {
                    $famData = [
                        'pegawai_id'    => $pegawai->pegawai_id,
                        'nama'          => $faker->name,
                        'hubungan'      => $faker->randomElement(['Istri', 'Suami', 'Anak']),
                        'jenis_kelamin' => $faker->randomElement(['L', 'P']),
                        'tgl_lahir'     => $faker->date(),
                        'created_by'    => $sysUserId,
                    ];
                    Keluarga::create($famData);
                }

                // 9. Pengembangan Diri (Random)
                if ($faker->boolean(50)) {
                    $tglMulai = $faker->dateTimeBetween('-3 years', 'now');
                    $devData  = [
                        'pegawai_id'         => $pegawai->pegawai_id,
                        'jenis_kegiatan'     => $faker->randomElement(['Pelatihan', 'Workshop', 'Sertifikasi', 'Seminar']),
                        'nama_kegiatan'      => $faker->sentence(3),
                        'nama_penyelenggara' => $faker->company,
                        'tgl_mulai'          => $tglMulai->format('Y-m-d'),
                        'tgl_selesai'        => $faker->dateTimeBetween($tglMulai, 'now')->format('Y-m-d'),
                        'tahun'              => $tglMulai->format('Y'),
                        'created_by'         => $sysUserId,
                    ];
                    $this->createApprovedHistory(PengembanganDiri::class, $devData, $sysUserId);
                }

                // 10. Inpassing (Random)
                if (\App\Models\Hr\GolonganInpassing::exists() && $faker->boolean(40)) {
                    $inpData = [
                        'pegawai_id'       => $pegawai->pegawai_id,
                        'gol_inpassing_id' => \App\Models\Hr\GolonganInpassing::all()->random()->gol_inpassing_id,
                        'no_sk'            => $faker->bothify('SK/###/YPCR/????'),
                        'tgl_sk'           => $faker->date(),
                        'tmt'              => $faker->date(),
                        'masa_kerja_tahun' => $faker->numberBetween(1, 15),
                        'masa_kerja_bulan' => $faker->numberBetween(0, 11),
                        'gaji_pokok'       => $faker->numberBetween(3000000, 7000000),
                        'created_by'       => $sysUserId,
                    ];
                    $riwayatInpassing = RiwayatInpassing::create($inpData);
                    $pegawai->update(['latest_riwayatinpassing_id' => $riwayatInpassing->getKey()]);
                }

                // 11. Penugasan (Random)
                if ($orgUnits->isNotEmpty() && $faker->boolean(70)) {
                    $unit          = $orgUnits->random();
                    $penugasanData = [
                        'pegawai_id'  => $pegawai->pegawai_id,
                        'org_unit_id' => $unit->org_unit_id,
                        'tgl_mulai'   => $faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
                        'no_sk'       => $faker->bothify('SK/###/YPCR/????'),
                        'status'      => 'approved',
                        'approved_at' => now(),
                        'approved_by' => $sysUserId,
                        'created_by'  => $sysUserId,
                    ];
                    $riwayatPenugasan = RiwayatPenugasan::create($penugasanData);
                    $pegawai->update(['latest_riwayatpenugasan_id' => $riwayatPenugasan->getKey()]);
                }
            }
        });
    }

    private function createApprovedHistory($modelClass, $data, $userId)
    {
        // 1. Create Approval using DB::table to avoid model events/id issues
        $approvalId = DB::table('hr_riwayat_approval')->insertGetId([
            'model'      => $modelClass,
            'model_id'   => 0, // temp
            'status'     => 'Approved',
            'keterangan' => 'Seeder Data',
            'pejabat'    => 'System Seeder',
            'created_by' => $userId,
            'updated_by' => $userId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $data['latest_riwayatapproval_id'] = $approvalId;

        $model = $modelClass::create($data);

        // 4. Update Approval
        DB::table('hr_riwayat_approval')->where('riwayatapproval_id', $approvalId)->update(['model_id' => $model->getKey()]);

        return $model;
    }
}
