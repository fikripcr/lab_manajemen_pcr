<?php
namespace Database\Seeders;

use App\Models\Survei\Halaman;
use App\Models\Survei\Jawaban;
use App\Models\Survei\Opsi;
use App\Models\Survei\Pengisian;
use App\Models\Survei\Pertanyaan;
use App\Models\Survei\Survei;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MainSurveiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('MainSurveiSeeder started...');

        // Truncate tables for fresh seed
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Jawaban::truncate();
        Pengisian::truncate();
        Opsi::truncate();
        Pertanyaan::truncate();
        Halaman::truncate();
        Survei::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $faker = Faker::create('id_ID');

        // 1. Create 10 Surveys
        $surveys      = [];
        $surveyTitles = [
            'Survei Kepuasan Mahasiswa terhadap Layanan Akademik',
            'Evaluasi Kinerja Dosen Semester Ganjil 2024/2025',
            'Survei Kepuasan Pengguna Lulusan',
            'Survei Fasilitas Laboratorium Komputer',
            'Survei Kesehatan Mental Mahasiswa',
            'Survei Minat Bakat Mahasiswa Baru',
            'Evaluasi Layanan Kantin dan Koperasi',
            'Tracer Study Alumni 2023',
            'Survei Kepuasan Tenaga Kependidikan',
            'Evaluasi Website dan Portal Akademik',
        ];

        foreach ($surveyTitles as $index => $title) {
            $tglMulai   = Carbon::now()->subMonths(rand(1, 6));
            $tglSelesai = Carbon::now()->addMonths(rand(1, 6));

            $survei = Survei::create([
                'judul'           => $title,
                'deskripsi'       => $faker->paragraph(),
                'slug'            => Str::slug($title) . '-' . Str::random(5),
                'target_role'     => $faker->randomElement(['Mahasiswa', 'Dosen', 'Tendik', 'Alumni', 'Umum']),
                'is_aktif'        => true,
                'wajib_login'     => $faker->boolean(70),
                'bisa_isi_ulang'  => false,
                'tanggal_mulai'   => $tglMulai,
                'tanggal_selesai' => $tglSelesai,
            ]);
            $surveys[] = $survei;

            // Create 1-2 Halaman per Survey
            $halaman1 = Halaman::create([
                'survei_id'         => $survei->id,
                'judul_halaman'     => 'Bagian Utama',
                'urutan'            => 1,
                'deskripsi_halaman' => 'Silakan isi pertanyaan di bawah ini dengan sejujur-jujurnya.',
            ]);

            // 3. Create Questions for each Survey
            $this->createQuestions($survei, $halaman1);
        }

        // 4. Generate 2000 Respondents
        $this->command->info('Generating 2000 respondents (Pengisian & Jawaban)...');

        $users = User::limit(100)->get();

        $totalRespondents = 2000;

        // Prepare IDs for faster access
        $surveyData = [];
        foreach ($surveys as $s) {
            $sid          = $s->id;
            $questions    = Pertanyaan::where('survei_id', $sid)->with('opsi')->get();
            $surveyData[] = [
                'id'          => $sid,
                'questions'   => $questions,
                'tgl_mulai'   => $s->tanggal_mulai,
                'tgl_selesai' => $s->tanggal_selesai,
                'wajib_login' => $s->wajib_login,
            ];
        }

        for ($i = 0; $i < $totalRespondents; $i++) {
            $sData = $surveyData[array_rand($surveyData)];

            // Create Pengisian (Respondent Entry)
            $waktuMulai   = $faker->dateTimeBetween($sData['tgl_mulai'], 'now');
            $waktuSelesai = (clone $waktuMulai)->modify('+' . rand(5, 15) . ' minutes');

            $pengisian = Pengisian::create([
                'survei_id'     => $sData['id'],
                'user_id'       => $sData['wajib_login'] ? ($users->random()->id ?? null) : null,
                'status'        => 'Selesai',
                'waktu_mulai'   => $waktuMulai,
                'waktu_selesai' => $waktuSelesai,
                'ip_address'    => $faker->ipv4,
            ]);

            // Fill Answers
            foreach ($sData['questions'] as $q) {
                $nilaiTeks  = null;
                $nilaiAngka = null;
                $opsiId     = null;

                if ($q->tipe == 'Skala_Linear') {
                    $nilaiAngka = rand(1, 5);
                } elseif ($q->tipe == 'Teks_Singkat' || $q->tipe == 'Esai') {
                    $nilaiTeks = $faker->sentence();
                } elseif ($q->tipe == 'Pilihan_Ganda' || $q->tipe == 'Dropdown') {
                    $opts = $q->opsi;
                    if ($opts->isNotEmpty()) {
                        $opt       = $opts->random();
                        $opsiId    = $opt->id;
                        $nilaiTeks = $opt->label;
                    }
                }

                Jawaban::create([
                    'pengisian_id'  => $pengisian->id,
                    'pertanyaan_id' => $q->id,
                    'nilai_teks'    => $nilaiTeks,
                    'nilai_angka'   => $nilaiAngka,
                    'opsi_id'       => $opsiId,
                ]);
            }

            if (($i + 1) % 500 == 0) {
                $this->command->info("Generated " . ($i + 1) . " respondents...");
            }
        }

        $this->command->info('MainSurveiSeeder completed.');
    }

    private function createQuestions($survei, $halaman)
    {
        $sid = $survei->id;
        $hid = $halaman->id;

        // 1. Skala Questions (Likert) - 3 questions
        for ($i = 1; $i <= 3; $i++) {
            Pertanyaan::create([
                'survei_id'       => $sid,
                'halaman_id'      => $hid,
                'teks_pertanyaan' => "Seberapa puas Anda dengan aspek ke-$i dari " . $survei->judul . "?",
                'tipe'            => 'Skala_Linear',
                'wajib_diisi'     => true,
                'urutan'          => $i,
                'config_json'     => ['min' => 1, 'max' => 5],
            ]);
        }

        // 2. Choice Question - 1 question with options
        $qChoice = Pertanyaan::create([
            'survei_id'       => $sid,
            'halaman_id'      => $hid,
            'teks_pertanyaan' => "Melalui media apa Anda mengetahui informasi ini?",
            'tipe'            => 'Pilihan_Ganda',
            'wajib_diisi'     => true,
            'urutan'          => 4,
        ]);

        $options = ['Website', 'Instagram', 'WhatsApp Group', 'E-mail', 'Lainnya'];
        foreach ($options as $idx => $label) {
            Opsi::create([
                'pertanyaan_id'   => $qChoice->id,
                'label'           => $label,
                'nilai_tersimpan' => Str::slug($label),
                'urutan'          => $idx + 1,
            ]);
        }

        // 3. Text Questions - 1 question
        Pertanyaan::create([
            'survei_id'       => $sid,
            'halaman_id'      => $hid,
            'teks_pertanyaan' => "Berikan masukan atau saran Anda:",
            'tipe'            => 'Esai',
            'wajib_diisi'     => false,
            'urutan'          => 5,
        ]);
    }
}
