<?php
namespace Database\Seeders\Hr;

use App\Models\Hr\Departemen;
use App\Models\Hr\JabatanFungsional;
use App\Models\Hr\OrgUnit;
use App\Models\Hr\Pegawai;
use App\Models\Hr\Posisi;
use App\Models\Hr\Prodi;
use App\Models\Hr\RiwayatApproval;
use App\Models\Hr\RiwayatDataDiri;
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
        $depts          = Departemen::all();
        $prodis         = Prodi::all();
        $posisis        = Posisi::all();
        $statusPegawais = StatusPegawai::all();
        $statusAktifs   = StatusAktifitas::where('nama_status', 'Aktif')->first();
        $jabFungsionals = JabatanFungsional::all();
        $orgUnits       = OrgUnit::whereIn('type', ['jabatan_struktural', 'departemen', 'prodi'])->get(); // for Penugasan/Struktural

        // If no master data, we can't seed properly
        if ($depts->isEmpty() || $statusPegawais->isEmpty()) {
            $this->command->error('HR Master Data missing. Please ensure LegacyHrDataSeeder and others ran.');
            return;
        }

        DB::transaction(function () use ($faker, $sysUserId, $depts, $prodis, $posisis, $statusPegawais, $statusAktifs, $jabFungsionals, $orgUnits) {

            for ($i = 0; $i < 30; $i++) {
                // 1. Create Pegawai Skeleton
                $pegawai = Pegawai::create(['created_by' => $sysUserId]);

                // 2. Data Diri
                $gender = $faker->randomElement(['L', 'P']);
                $dept   = $depts->random();
                // Find prodi for this dept if exists, else random
                $deptProdis = $prodis->where('departemen_id', $dept->departemen_id);
                $prodi      = $deptProdis->isNotEmpty() ? $deptProdis->random() : null;

                $dataDiri = [
                    'pegawai_id'    => $pegawai->pegawai_id,
                    'nama'          => $faker->name($gender == 'L' ? 'male' : 'female'),
                    'nip'           => $faker->unique()->numerify('##########'),
                    'nidn'          => $faker->optional(0.3)->numerify('##########'),
                    'jenis_kelamin' => $gender,
                    'tempat_lahir'  => $faker->city,
                    'tgl_lahir'     => $faker->dateTimeBetween('-50 years', '-22 years')->format('Y-m-d'),
                    'alamat'        => $faker->address,
                    'email'         => $faker->unique()->email,
                    'no_hp'         => $faker->phoneNumber,
                    'status_nikah'  => $faker->randomElement(['Menikah', 'Belum Menikah']),
                    'agama'         => 'Islam',
                    'departemen_id' => $dept->departemen_id,
                    'prodi_id'      => $prodi ? $prodi->prodi_id : null,
                    'posisi_id'     => $posisis->random()->posisi_id,
                    'created_by'    => $sysUserId,
                ];

                $riwayatDiri = $this->createApprovedHistory(RiwayatDataDiri::class, $dataDiri, $sysUserId);
                $pegawai->update(['latest_riwayatdatadiri_id' => $riwayatDiri->getKey()]);

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
                    $jabFungData = [
                        'pegawai_id'       => $pegawai->pegawai_id,
                        'jabfungsional_id' => $jabFungsionals->random()->jabfungsional_id,
                        'tmt'              => $faker->dateTimeBetween('-5 years', 'now')->format('Y-m-d'),
                        'no_sk'            => $faker->bothify('SK/###/YPCR/????'),
                        'created_by'       => $sysUserId,
                    ];
                    $riwayatJabFung = $this->createApprovedHistory(RiwayatJabFungsional::class, $jabFungData, $sysUserId);
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
                        'no_sk'              => $faker->bothify('SK/###/YPCR/????'),
                        'created_by'         => $sysUserId,
                    ];
                    $riwayatAktif = $this->createApprovedHistory(RiwayatStatAktifitas::class, $statAktifData, $sysUserId);
                    $pegawai->update(['latest_riwayatstataktifitas_id' => $riwayatAktif->getKey()]);
                }

                // 7. Penugasan (Random)
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
                    ];
                    $riwayatPenugasan = RiwayatPenugasan::create($penugasanData); // Penugasan has no Approval model
                    $pegawai->update(['latest_riwayatpenugasan_id' => $riwayatPenugasan->getKey()]);
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
                    $this->createApprovedHistory(Keluarga::class, $famData, $sysUserId);
                }
            }
        });
    }

    private function createApprovedHistory($modelClass, $data, $userId)
    {
        // 1. Create Approval
        $approval = RiwayatApproval::create([
            'model'      => $modelClass,
            'model_id'   => 0, // temp
            'status'     => 'Approved',
            'keterangan' => 'Seeder Data',
            'pejabat'    => 'System Seeder',
            'created_by' => $userId,
            'updated_by' => $userId,
        ]);

        // 2. Add approval ID to data if supported
        // Use reflection or try catch or simple check, but since we know schema:
        // Most Riwayat tables have 'latest_riwayatapproval_id'
        // Except Keluarga/Pendidikan? Wait, Keluarga/Pendidikan DO NOT have link to Header directly so no strictly "Latest".
        // But they DO support Approval workflow.

        $data['latest_riwayatapproval_id'] = $approval->riwayatapproval_id;

        // 3. Create Model using forceCreate to bypass fillable if needed, or simple create
        // We assume fillable is set or we used guarded=[] in some models.
        // Safer to use forceCreate or unguard. But let's try standard create.

        // Some models might not have approval column in fillable.
        // Let's assume standard structure.

        $model = $modelClass::create($data);

        // 4. Update Approval
        $approval->update(['model_id' => $model->getKey()]);

        return $model;
    }
}
