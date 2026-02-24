<?php
namespace Database\Seeders;

use App\Models\Lab\Inventaris;
use App\Models\Lab\JadwalKuliah;
use App\Models\Lab\Kegiatan;
use App\Models\Lab\Lab;
use App\Models\Lab\LabInventaris;
use App\Models\Lab\LabTeam;
use App\Models\Lab\LaporanKerusakan;
use App\Models\Lab\LogPenggunaanLab;
use App\Models\Lab\Mahasiswa;
use App\Models\Lab\MataKuliah;
use App\Models\Lab\Pengumuman;
use App\Models\Lab\Personil;
use App\Models\Lab\Semester;
use App\Models\Lab\SuratBebasLab;
use App\Models\Shared\StrukturOrganisasi as OrgUnit;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MainLabSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('MainLabSeeder started...');

        // Truncate dependent and master tables
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Lab::truncate();
        Semester::truncate();
        MataKuliah::truncate();
        JadwalKuliah::truncate();
        Inventaris::truncate();
        LabTeam::truncate();
        LabInventaris::truncate();
        SuratBebasLab::truncate();
        LaporanKerusakan::truncate();
        Kegiatan::truncate();
        LogPenggunaanLab::truncate();
        Mahasiswa::truncate();
        Personil::truncate();
        Pengumuman::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Run Basic Master Data Seeders
        $this->seedAcademicData();
        $this->seedInventory();
        $this->seedPengumuman();

        $this->command->info('Master Data Lab seeded.');

        $labs        = Lab::all();
        $users       = User::all();
        $inventories = Inventaris::all();

        if ($labs->isEmpty() || $users->isEmpty()) {
            $this->command->warn('No labs or users found. Skipping dependent seeds.');
            return;
        }

        // 1b. Seed Mahasiswa & Personil Profiles with user_id relationship
        $this->command->info('Seeding Student and Personnel Profiles...');
        foreach ($users as $user) {
            $roleNames = $user->getRoleNames();
            
            // Check if already has profile
            $existingMahasiswa = Mahasiswa::where('user_id', $user->id)->first();
            $existingPersonil = Personil::where('user_id', $user->id)->first();
            
            if ($roleNames->contains('mahasiswa') && !$existingMahasiswa) {
                // Get valid Prodi OrgUnits
                $prodiIds = OrgUnit::where('type', 'Prodi')->pluck('orgunit_id')->toArray();

                Mahasiswa::create([
                    'user_id'    => $user->id,
                    'nim'        => fake()->unique()->numerify('10##########'),
                    'nama'       => $user->name,
                    'email'      => $user->email,
                    'orgunit_id' => ! empty($prodiIds) ? $prodiIds[array_rand($prodiIds)] : null,
                    'created_by' => $users->first()->id,
                ]);
                
                $this->command->info("    ✓ Created Mahasiswa: {$user->name}");
            } elseif (!$roleNames->contains('mahasiswa') && !$existingPersonil) {
                Personil::create([
                    'user_id'    => $user->id,
                    'nama'       => $user->name,
                    'nip'        => fake()->unique()->numerify('19##########'),
                    'posisi'     => $roleNames->first() ?? 'Staff',
                    'created_by' => $users->first()->id,
                ]);
                
                $this->command->info("    ✓ Created Personil: {$user->name}");
            }
        }

        // 2. Seed Lab Teams
        $this->command->info('Seeding Lab Teams...');
        foreach ($labs as $lab) {
            if ($users->count() >= 4) {
                $teamUsers = $users->random(4);
                $this->createLabTeamMember($lab->lab_id, 'Kepala Lab', $teamUsers[0]);
                $this->createLabTeamMember($lab->lab_id, 'Teknisi', $teamUsers[1]);
                $this->createLabTeamMember($lab->lab_id, 'Asisten', $teamUsers[2]);
                $this->createLabTeamMember($lab->lab_id, 'Asisten', $teamUsers[3]);
            }
        }

        // 3. Seed Lab Inventory Placement (LabInventaris)
        $this->command->info('Seeding Lab Inventory Placements...');
        if ($inventories->isNotEmpty() && $labs->isNotEmpty()) {
            $shuffledInventories = $inventories->shuffle();
            $chunks              = $shuffledInventories->split($labs->count());

            foreach ($labs as $index => $lab) {
                if (isset($chunks[$index])) {
                    foreach ($chunks[$index] as $item) {
                        LabInventaris::create([
                            'lab_id'             => $lab->lab_id,
                            'inventaris_id'      => $item->inventaris_id,
                            'kode_inventaris'    => 'LAB-' . strtoupper(fake()->lexify('???')) . '-' . fake()->unique()->numerify('#####'),
                            'no_series'          => fake()->bothify('SN-#####-?????'),
                            'tanggal_penempatan' => fake()->dateTimeBetween('-2 years', 'now'),
                            'status'             => 'active',
                            'keterangan'         => 'Penempatan awal',
                            'created_by'         => $users->random()->id,
                        ]);
                    }
                }
            }
        }

        // 4. Seed Surat Bebas Lab
        $this->command->info('Seeding Surat Bebas Lab...');
        for ($i = 0; $i < 50; $i++) {
            SuratBebasLab::create([
                'student_id'  => $users->random()->id,
                'status'      => fake()->randomElement(['pending', 'approved', 'rejected']),
                'file_path'   => null,
                'created_by'  => $users->random()->id,
            ]);
        }

        // 5. Seed Laporan Kerusakan
        $this->command->info('Seeding Laporan Kerusakan...');
        $placedInventories = LabInventaris::all();
        if ($placedInventories->isNotEmpty()) {
            for ($i = 0; $i < 50; $i++) {
                $item = $placedInventories->random();
                LaporanKerusakan::create([
                    'inventaris_id'       => $item->inventaris_id,
                    'teknisi_id'          => $users->random()->id,
                    'deskripsi_kerusakan' => fake()->paragraph(),
                    'status'              => fake()->randomElement(['pending', 'in_progress', 'completed', 'cannot_repair']),
                    'catatan_perbaikan'   => fake()->optional()->sentence(),
                    'created_by'          => $users->random()->id,
                ]);
            }
        }

        // 6. Seed Kegiatan (Events/Bookings)
        $this->command->info('Seeding Kegiatan Lab...');
        for ($i = 0; $i < 30; $i++) {
            $lab      = $labs->random();
            $kegiatan = Kegiatan::create([
                'lab_id'           => $lab->lab_id,
                'penyelenggara_id' => $users->random()->id,
                'nama_kegiatan'    => fake()->sentence(3),
                'deskripsi'        => fake()->paragraph(),
                'tanggal'          => fake()->dateTimeBetween('-1 month', '+1 month'),
                'jam_mulai'        => '08:00:00',
                'jam_selesai'      => '12:00:00',
                'status'           => fake()->randomElement(['pending', 'approved', 'rejected', 'completed']),
                'created_by'       => $users->random()->id,
            ]);

            if (in_array($kegiatan->status, ['approved', 'completed'])) {
                for ($j = 0; $j < rand(5, 15); $j++) {
                    LogPenggunaanLab::create([
                        'kegiatan_id'   => $kegiatan->kegiatan_id,
                        'lab_id'        => $lab->lab_id,
                        'nama_peserta'  => fake()->name(),
                        'email_peserta' => fake()->safeEmail(),
                        'npm_peserta'   => fake()->numerify('##########'),
                        'nomor_pc'      => rand(1, 30),
                        'kondisi'       => 'Baik',
                        'waktu_isi'     => $kegiatan->tanggal->format('Y-m-d') . ' ' . fake()->time(),
                        'created_by'    => $users->random()->id,
                    ]);
                }
            }
        }
        $this->command->info('MainLabSeeder completed.');
    }

    private function createLabTeamMember($labId, $jabatan, $user)
    {
        LabTeam::create([
            'lab_id'        => $labId,
            'user_id'       => $user->id,
            'jabatan'       => $jabatan,
            'is_active'     => true,
            'tanggal_mulai' => now()->subMonths(rand(1, 12)),
            'created_by'    => $user->id,
        ]);
    }

    private function seedAcademicData()
    {
        $this->command->info('Seeding Academic Data...');
        $tahunSekarang = date('Y');
        for ($tahun = $tahunSekarang - 2; $tahun <= $tahunSekarang + 2; $tahun++) {
            Semester::create(['tahun_ajaran' => $tahun . '/' . ($tahun + 1), 'semester' => 'Ganjil', 'start_date' => $tahun . '-08-01', 'end_date' => $tahun . '-12-31', 'is_active' => ($tahun == $tahunSekarang)]);
            Semester::create(['tahun_ajaran' => $tahun . '/' . ($tahun + 1), 'semester' => 'Genap', 'start_date' => ($tahun + 1) . '-01-01', 'end_date' => ($tahun + 1) . '-06-30', 'is_active' => false]);
        }

        for ($i = 1; $i <= 50; $i++) {
            MataKuliah::create(['kode_mk' => 'MK' . str_pad($i, 3, '0', STR_PAD_LEFT), 'nama_mk' => fake()->sentence(3, true), 'sks' => fake()->numberBetween(2, 4)]);
        }

        $faker = \Faker\Factory::create('id_ID');
        for ($i = 1; $i <= 20; $i++) {
            Lab::create(['name' => 'Laboratorium ' . $faker->colorName . ' ' . $i, 'location' => $faker->address(), 'capacity' => $faker->numberBetween(10, 50), 'description' => $faker->paragraph()]);
        }

        $hariOptions   = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $dosenUsers    = User::role('dosen')->get();
        $semesterIds   = Semester::pluck('semester_id')->toArray();
        $mataKuliahIds = MataKuliah::pluck('mata_kuliah_id')->toArray();
        $labIds        = Lab::pluck('lab_id')->toArray();

        if ($dosenUsers->isNotEmpty() && ! empty($semesterIds) && ! empty($mataKuliahIds) && ! empty($labIds)) {
            for ($i = 1; $i <= 50; $i++) {
                JadwalKuliah::create([
                    'semester_id'    => $semesterIds[array_rand($semesterIds)],
                    'mata_kuliah_id' => $mataKuliahIds[array_rand($mataKuliahIds)],
                    'dosen_id'       => $dosenUsers->random()->id,
                    'hari'           => $hariOptions[array_rand($hariOptions)],
                    'jam_mulai'      => fake()->time('H:i', '07:00:00'),
                    'jam_selesai'    => fake()->time('H:i', '17:00:00'),
                    'lab_id'         => $labIds[array_rand($labIds)],
                ]);
            }
        }
    }

    private function seedInventory()
    {
        $this->command->info('Seeding Inventory...');
        $conditions = ['Baik', 'Rusak Ringan', 'Rusak Berat', 'Tidak Dapat Digunakan'];
        for ($i = 1; $i <= 500; $i++) {
            $faker = fake();
            Inventaris::create([
                'nama_alat'          => $faker->company() . ' ' . $faker->words(rand(1, 3), true) . ' ' . $i,
                'jenis_alat'         => $faker->randomElement(['Elektronik', 'Furniture', 'Alat Laboratorium']) . ' ' . $faker->randomElement(['Dasar', 'Standar']),
                'kondisi_terakhir'   => $conditions[array_rand($conditions)],
                'tanggal_pengecekan' => $faker->dateTimeBetween('-6 months', '+1 month'),
            ]);
        }
    }

    private function seedPengumuman()
    {
        $this->command->info('Seeding Pengumuman...');
        $userIds = User::pluck('id')->toArray();
        if (empty($userIds)) {
            return;
        }

        $faker = \Faker\Factory::create('id_ID');

        for ($i = 1; $i <= 200; $i++) {
            Pengumuman::create(['judul' => $faker->sentence(), 'isi' => $faker->paragraphs(3, true), 'jenis' => 'pengumuman', 'penulis_id' => $userIds[array_rand($userIds)], 'is_published' => $faker->boolean(80), 'published_at' => $faker->dateTimeBetween('-1 year', 'now')]);
            Pengumuman::create(['judul' => $faker->sentence(), 'isi' => $faker->paragraphs(3, true), 'jenis' => 'artikel_berita', 'penulis_id' => $userIds[array_rand($userIds)], 'is_published' => $faker->boolean(80), 'published_at' => $faker->dateTimeBetween('-1 year', 'now')]);
        }
    }
}
