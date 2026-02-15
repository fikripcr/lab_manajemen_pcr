<?php
namespace Database\Seeders\Pemutu;

use App\Models\Pemutu\OrgUnit;
use App\Models\Pemutu\Personil;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PersonilSeeder extends Seeder
{
    /**
     * Seed personnel for the organization structure.
     * Based on typical polytechnic institution staff distribution.
     */
    public function run(): void
    {
        // Truncate first
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Personil::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $faker = \Faker\Factory::create('id_ID');

        // Get all org units by type for strategic allocation
        $units = OrgUnit::all()->groupBy('type');

        // ===============================
        // LEVEL 1: INSTITUSI (PCR)
        // ===============================
        $pcr = OrgUnit::where('type', 'Institusi')->first();
        if ($pcr) {
            // Direktur
            $this->createPersonil($pcr, 'Dr. Ahmad Fauzi, M.T.', 'direktur@pcr.ac.id', 'Pimpinan', $faker);
            // Sekretaris Direktur
            $this->createPersonil($pcr, 'Siti Rahmawati, S.E.', 'sekdir@pcr.ac.id', 'Staff', $faker);
            // Admin Staff
            for ($i = 0; $i < 3; $i++) {
                $this->createPersonil($pcr, $faker->name, $faker->unique()->safeEmail, 'Staff', $faker);
            }
        }

        // ===============================
        // SENAT
        // ===============================
        $senat = OrgUnit::where('name', 'Senat')->first();
        if ($senat) {
            $this->createPersonil($senat, 'Prof. Dr. Ir. Bambang Widodo, M.Sc.', 'ketua.senat@pcr.ac.id', 'Pimpinan', $faker);
            $this->createPersonil($senat, 'Dr. Indra Kusuma, M.T.', 'sekretaris.senat@pcr.ac.id', 'Staff', $faker);
        }

        // ===============================
        // SATUAN PENJAMINAN MUTU
        // ===============================
        $spm = OrgUnit::where('name', 'Satuan Penjaminan Mutu')->first();
        if ($spm) {
            $this->createPersonil($spm, 'Dr. Ratna Dewi, M.M.', 'kepala.spm@pcr.ac.id', 'Pimpinan', $faker);
            $this->createPersonil($spm, 'Andi Wijaya, S.Kom., M.T.', 'auditor.spm@pcr.ac.id', 'Auditor', $faker);
            for ($i = 0; $i < 3; $i++) {
                $this->createPersonil($spm, $faker->name, $faker->unique()->safeEmail, 'Staff', $faker);
            }
        }

        // ===============================
        // DIREKTORAT / WADIR (4 Wakil Direktur)
        // ===============================
        $wadirs     = OrgUnit::where('type', 'Direktorat')->orderBy('seq')->get();
        $wadirNames = [
            'Dr. Hendri Susanto, M.T.',
            'Dr. Linda Marlina, M.M.',
            'Dr. Ir. Agus Setiawan, M.T.',
            'Dr. Dewi Anggraini, M.Si.',
        ];
        $i = 0;
        foreach ($wadirs as $wadir) {
            $name = $wadirNames[$i] ?? $faker->name;
            $this->createPersonil($wadir, $name, 'wadir' . ($i + 1) . '@pcr.ac.id', 'Pimpinan', $faker);
            // Each Wadir has 1 admin staff
            $this->createPersonil($wadir, $faker->name, $faker->unique()->safeEmail, 'Staff', $faker);
            $i++;
        }

        // ===============================
        // BAGIAN (13 Bagian, each has Kepala + 2-4 Staff)
        // ===============================
        $bagians = OrgUnit::where('type', 'Bagian')->get();
        foreach ($bagians as $bagian) {
            // Kepala Bagian
            $this->createPersonil($bagian, $faker->name . ', S.T., M.T.', $faker->unique()->safeEmail, 'Pimpinan', $faker);
            // Staff (2-4 per bagian)
            $staffCount = rand(2, 4);
            for ($i = 0; $i < $staffCount; $i++) {
                $this->createPersonil($bagian, $faker->name, $faker->unique()->safeEmail, 'Staff', $faker);
            }
        }

        // ===============================
        // JURUSAN (3 Jurusan, each has Kajur + Sekjur + Admin)
        // ===============================
        $jurusans      = OrgUnit::where('type', 'Jurusan')->get();
        $jurusanKajurs = [
            'Dr. Ir. Rizal Hidayat, M.T.',
            'Dr. Maya Sari, M.M.',
            'Dr. Budi Santoso, M.Kom.',
        ];
        $j = 0;
        foreach ($jurusans as $jurusan) {
            // Ketua Jurusan
            $name = $jurusanKajurs[$j] ?? $faker->name . ', Ph.D.';
            $this->createPersonil($jurusan, $name, $faker->unique()->safeEmail, 'Pimpinan', $faker);
            // Sekretaris Jurusan
            $this->createPersonil($jurusan, $faker->name . ', M.T.', $faker->unique()->safeEmail, 'Staff', $faker);
            // Admin
            $this->createPersonil($jurusan, $faker->name, $faker->unique()->safeEmail, 'Staff', $faker);
            $j++;
        }

        // ===============================
        // PRODI (18 Prodi, each has Kaprodi + 3-6 Dosen)
        // ===============================
        $prodis = OrgUnit::where('type', 'Prodi')->get();
        foreach ($prodis as $prodi) {
            // Kepala Program Studi
            $this->createPersonil($prodi, $faker->name . ', S.T., M.T.', $faker->unique()->safeEmail, 'Pimpinan', $faker);
            // Dosen Tetap (3-6 per prodi)
            $dosenCount = rand(3, 6);
            for ($i = 0; $i < $dosenCount; $i++) {
                $gelar = $faker->randomElement([', S.T., M.T.', ', S.Kom., M.Kom.', ', S.E., M.M.', ', Ph.D.', ', M.Sc.']);
                $this->createPersonil($prodi, $faker->name . $gelar, $faker->unique()->safeEmail, 'Dosen', $faker);
            }
        }

        // ===============================
        // LABORATORIUM (3 Lab, each has Kepala Lab + 2-3 Teknisi)
        // ===============================
        $labs = OrgUnit::where('type', 'Laboratorium')->get();
        foreach ($labs as $lab) {
            // Kepala Lab
            $this->createPersonil($lab, $faker->name . ', S.T., M.T.', $faker->unique()->safeEmail, 'Pimpinan', $faker);
            // Teknisi (2-3 per lab)
            $teknisiCount = rand(2, 3);
            for ($i = 0; $i < $teknisiCount; $i++) {
                $this->createPersonil($lab, $faker->name, $faker->unique()->safeEmail, 'Teknisi', $faker);
            }
        }

        // ===============================
        // SEKRETARIAT
        // ===============================
        $sekretariats = OrgUnit::where('type', 'Sekretariat')->get();
        foreach ($sekretariats as $sekretariat) {
            $this->createPersonil($sekretariat, $faker->name . ', M.T.', $faker->unique()->safeEmail, 'Staff', $faker);
        }

        $this->command->info('PersonilSeeder completed! Total: ' . Personil::count() . ' personnel created.');

        // ==========================================
        // GOLDEN PATH USER (user1@contoh-lab.ac.id)
        // ==========================================
        // Ensure user1 has a Personil record for testing assignments
        $user1Email = 'user1@contoh-lab.ac.id';
        $user1      = \App\Models\User::where('email', $user1Email)->first();

        if ($user1) {
            // Check if personil already exists for this email
            $existingPersonil = Personil::where('email', $user1Email)->first();

            if (! $existingPersonil) {
                // Assign to a generic unit (e.g. first Prodi)
                $prodi = OrgUnit::where('type', 'Prodi')->first();
                if ($prodi) {
                    Personil::create([
                        'org_unit_id' => $prodi->orgunit_id,
                        'nama'        => 'Dosen Sertifikasi (User 1)',
                        'email'       => $user1Email,
                        'jenis'       => 'Dosen',
                        'user_id'     => $user1->id,
                    ]);
                    $this->command->info("Created Personil for Golden Path: $user1Email");
                }
            } else {
                // Update existing to link user_id if missing
                if (! $existingPersonil->user_id) {
                    $existingPersonil->update(['user_id' => $user1->id]);
                    $this->command->info("Linked Personil to User: $user1Email");
                }
            }
        }

        // ==========================================
        // APPROVAL TESTING USERS (Linked to positions)
        // ==========================================
        $this->command->info('Linking Personils to Users for Approval Testing...');

        $assignments = [
            'Direktur' => ['user_email' => 'user2@contoh-lab.ac.id', 'org_type' => 'Institusi', 'position' => 'Pimpinan'], // Direktur
            'Wadir 1'  => ['user_email' => 'user3@contoh-lab.ac.id', 'org_type' => 'Direktorat', 'seq' => 1],              // Wadir 1
            'Wadir 2'  => ['user_email' => 'user4@contoh-lab.ac.id', 'org_type' => 'Direktorat', 'seq' => 2],              // Wadir 2
            'Kaprodi'  => ['user_email' => 'user5@contoh-lab.ac.id', 'org_type' => 'Prodi', 'seq' => 1],                   // Kaprodi TI (usually seq 1)
        ];

        foreach ($assignments as $role => $data) {
            $user = \App\Models\User::where('email', $data['user_email'])->first();
            if (! $user) {
                continue;
            }

            $personil = null;

            if ($data['org_type'] === 'Institusi') {
                $unit = OrgUnit::where('type', 'Institusi')->first();
                if ($unit) {
                    $personil = Personil::where('org_unit_id', $unit->orgunit_id)->where('jenis', 'Pimpinan')->first();
                }
            } elseif ($data['org_type'] === 'Direktorat') {
                $units = OrgUnit::where('type', 'Direktorat')->orderBy('seq')->get();
                $unit  = $units->get(($data['seq'] ?? 1) - 1);
                if ($unit) {
                    $personil = Personil::where('org_unit_id', $unit->orgunit_id)->where('jenis', 'Pimpinan')->first();
                }
            } elseif ($data['org_type'] === 'Prodi') {
                $unit = OrgUnit::where('type', 'Prodi')->orderBy('seq')->skip(($data['seq'] ?? 1) - 1)->first();
                if ($unit) {
                    $personil = Personil::where('org_unit_id', $unit->orgunit_id)->where('jenis', 'Pimpinan')->first();
                }
            }

            if ($personil) {
                $personil->update(['user_id' => $user->id]);
                $this->command->info("Mapped $role ({$personil->nama}) to {$data['user_email']}");
            } else {
                $this->command->warn("Could not find Personil for $role");
            }
        }
    }

    private function createPersonil($unit, $nama, $email, $jenis, $faker)
    {
        return Personil::create([
            'org_unit_id'     => $unit->orgunit_id,
            'nama'            => $nama,
            'email'           => $email,
            'jenis'           => $jenis,
            'user_id'         => null,
            'ttd_digital'     => null,
            'external_source' => null,
            'external_id'     => null,
        ]);
    }
}
