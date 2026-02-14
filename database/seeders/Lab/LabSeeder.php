<?php
namespace Database\Seeders\Lab;

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
use App\Models\Lab\Personil;
use App\Models\Lab\Semester;
use App\Models\Lab\SuratBebasLab;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LabSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Run Basic Master Data Seeders
        $this->call([
            AcademicDataSeeder::class, // Seeds Labs, Semesters, MK, Jadwal
            InventorySeeder::class,    // Seeds Inventaris
            PengumumanSeeder::class,   // Seeds Pengumuman & Berita
        ]);

        $this->command->info('Master Data Lab seeded.');

        $labs        = Lab::all();
        $users       = User::all();
        $inventories = Inventaris::all();

        if ($labs->isEmpty() || $users->isEmpty()) {
            $this->command->warn('No labs or users found. Skipping dependent seeds.');
            return;
        }

        // 1b. Seed Mahasiswa & Personil Profiles
        $this->command->info('Seeding Student and Personnel Profiles...');
        foreach ($users as $user) {
            $roleNames = $user->getRoleNames();
            if ($roleNames->contains('mahasiswa')) {
                Mahasiswa::create([
                    'user_id'       => $user->id,
                    'nim'           => fake()->unique()->numerify('10##########'),
                    'nama'          => $user->name,
                    'email'         => $user->email,
                    'program_studi' => fake()->randomElement(['Teknik Informatika', 'Sistem Informasi', 'Teknik Komputer', 'Digital Bisnis']),
                    'created_by'    => $users->first()->id,
                ]);
            } else {
                Personil::create([
                    'user_id'    => $user->id,
                    'nama'       => $user->name,
                    'email'      => $user->email,
                    'nip'        => fake()->unique()->numerify('19##########'),
                    'jabatan'    => $roleNames->first() ?? 'Staff',
                    'created_by' => $users->first()->id,
                ]);
            }
        }

        // 2. Seed Lab Teams
        $this->command->info('Seeding Lab Teams...');
        foreach ($labs as $lab) {
            // Pick 4 distinct users for this lab
            if ($users->count() >= 4) {
                $teamUsers = $users->random(4);

                // Assign 1 Kepala Lab, 1 Teknisi, 2 Asisten per lab
                $this->createLabTeamMember($lab->lab_id, 'Kepala Lab', $teamUsers[0]);
                $this->createLabTeamMember($lab->lab_id, 'Teknisi', $teamUsers[1]);
                $this->createLabTeamMember($lab->lab_id, 'Asisten', $teamUsers[2]);
                $this->createLabTeamMember($lab->lab_id, 'Asisten', $teamUsers[3]);
            }
        }

        // 3. Seed Lab Inventory Placement (LabInventaris)
        $this->command->info('Seeding Lab Inventory Placements...');
        if ($inventories->isNotEmpty() && $labs->isNotEmpty()) {
            // Shuffle inventories to randomize order
            $shuffledInventories = $inventories->shuffle();

            // Calculate items per lab (distribute all items across labs)
            // or just take extensive chunks.
            // Better: loop through shuffled items and assign to random lab?
            // Or assign chunks to labs.

            $chunks = $shuffledInventories->split($labs->count());

            foreach ($labs as $index => $lab) {
                if (isset($chunks[$index])) {
                    foreach ($chunks[$index] as $item) {
                        LabInventaris::create([
                            'lab_id'             => $lab->lab_id,
                            'inventaris_id'      => $item->inventaris_id,
                            'kode_inventaris'    => LabInventaris::generateKodeInventaris($lab->lab_id, $item->inventaris_id),
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
                'file_path'   => null, // or fake path
                'remarks'     => fake()->sentence(),
                'approved_by' => fake()->boolean(70) ? $users->random()->id : null,
                'approved_at' => fake()->boolean(70) ? fake()->dateTimeBetween('-1 month', 'now') : null,
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
                    'inventaris_id'       => $item->inventaris_id, // Note: Model uses Inventaris ID, presumably refers to master or placement? Checking model: belongsTo Inventaris.
                                                                   // Ideally report should link to LabInventaris for specific item instance, but standard usually links to master item + description of location/code.
                                                                   // Given LaporanKerusakan model has inventaris_id, we use that.
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

            // Seed Log Penggunaan Lab for completed/approved events
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
    }

    private function createLabTeamMember($labId, $jabatan, $user)
    {
        LabTeam::create([
            'lab_id'        => $labId,
            'user_id'       => $user->id,
            'jabatan'       => $jabatan,
            'is_active'     => true,
            'tanggal_mulai' => now()->subMonths(rand(1, 12)),
            'created_by'    => $user->id, // Assuming user creator is themselves or first admin? Let's use user->id for simplicity or pass creator.
        ]);
    }
}
