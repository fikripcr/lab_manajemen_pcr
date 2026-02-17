<?php
namespace Database\Seeders;

use App\Models\Cbt\JadwalUjian;
use App\Models\Cbt\KomposisiPaket;
use App\Models\Cbt\LogPelanggaran;
use App\Models\Cbt\MataUji;
use App\Models\Cbt\OpsiJawaban;
use App\Models\Cbt\PaketUjian;
use App\Models\Cbt\RiwayatUjianSiswa;
use App\Models\Cbt\Soal;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MainCbtSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Clean existing CBT data to avoid duplicates if re-running
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        KomposisiPaket::truncate();
        OpsiJawaban::truncate();
        Soal::truncate();
        PaketUjian::truncate();
        MataUji::truncate();
        RiwayatUjianSiswa::truncate();
        LogPelanggaran::truncate();
        JadwalUjian::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Mata Uji (Subjects)
        $subjectsData = [
            ['nama' => 'Matematika Dasar', 'deskripsi' => 'Logika angka dan aritmatika', 'tipe' => 'PMB'],
            ['nama' => 'Bahasa Indonesia', 'deskripsi' => 'Kemampuan berbahasa dan literasi', 'tipe' => 'PMB'],
            ['nama' => 'Bahasa Inggris', 'deskripsi' => 'Grammar, vocabulary, and reading', 'tipe' => 'PMB'],
            ['nama' => 'Penalaran Umum', 'deskripsi' => 'Logika dan pola kognitif', 'tipe' => 'PMB'],
            ['nama' => 'Pengetahuan Umum', 'deskripsi' => 'Wawasan luas dan isu terkini', 'tipe' => 'PMB'],
        ];

        $mataUjiModels = [];
        foreach ($subjectsData as $s) {
            $mataUjiModels[] = MataUji::create([
                'nama_mata_uji' => $s['nama'],
                'deskripsi'     => $s['deskripsi'],
                'tipe'          => $s['tipe'],
            ]);
        }

        // 3. SOAL GENERATION (500 Questions)
        $this->command->info('Generating 500 high-variety CBT Questions...');

        $templates = [
            'Matematika Dasar' => [
                'Hitunglah hasil dari {num1} + {num2} * {num3}',
                'Jika x = {num1} dan y = {num2}, maka nilai {num3}x - y adalah...',
                'Sebuah bidang memiliki panjang {num1} cm dan lebar {num2} cm. Kelilingnya adalah...',
                'Berapakah {num1}% dari {num2}00?',
                'Akar kuadrat dari {sqr} dikalikan {num1} adalah...',
            ],
            'Bahasa Indonesia' => [
                'Manakah kata baku yang benar dari "{word}"?',
                'Sinonim dari kata "{word}" adalah...',
                'Antonim yang paling tepat untuk "{word}" adalah...',
                'Ide pokok paragraf yang membahas tentang "{word}" adalah...',
                'Penulisan kalimat efektif yang mengandung kata "{word}" adalah...',
            ],
            'Bahasa Inggris'   => [
                'What is the closest meaning of "{word}"?',
                'Select the correct verb for: "They {verb} to the camp last week."',
                'The opposite of the word "{word}" in this context is...',
                'Choose the best preposition: "I am interested {prep} learning coding."',
                'Which of these sentences uses "{word}" correctly?',
            ],
            'Penalaran Umum'   => [
                'Jika SEMUA {word} adalah {word2}, dan SEBAGIAN {word} adalah {word3}, maka...',
                'Pola deret angka: {num1}, {num2}, {num3}, ... Angka selanjutnya adalah...',
                'Lengkapi analogi berikut: {word} : {word2} = {word3} : ...',
                'Manakah pernyataan yang paling logis berdasarkan premis "{word}"?',
                'Jika {num1} > {num2} dan {num2} < {num3}, maka perbandingan {num1} dan {num3} adalah...',
            ],
            'Pengetahuan Umum' => [
                'Siapakah tokoh yang dikenal sebagai Bapak {word}?',
                'Negara manakah yang memiliki ibukota di {word}?',
                'Peristiwa {word} terjadi pada tahun...',
                'Planet ke-{num1} dari matahari dalam tata surya adalah...',
                'Organisasi internasional {word} berfokus pada bidang...',
            ],
        ];

        $words_id_list = ['Kualitas', 'Apotek', 'Analisis', 'Objektif', 'Hierarki', 'Risiko', 'Sekadar', 'Zaman', 'Izin', 'Ekstrem'];
        $words_en_list = ['Resilient', 'Ambiguous', 'Prolific', 'Benevolent', 'Candid', 'Diligent', 'Eloquent', 'Frugal', 'Gullible', 'Hostile'];
        $logic_words   = ['Mamalia', 'Hewan', 'Karnivora', 'Tumbuhan', 'Bunga', 'Mawar', 'Kendaraan', 'Mobil', 'Roda', 'Mesin'];

        for ($i = 1; $i <= 500; $i++) {
            $mu           = $mataUjiModels[array_rand($mataUjiModels)];
            $templateList = $templates[$mu->nama_mata_uji];
            $template     = $templateList[array_rand($templateList)];

            // Fill placeholders
            $qText = $template;
            $qText = str_replace('{num1}', rand(2, 60), $qText);
            $qText = str_replace('{num2}', rand(2, 30), $qText);
            $qText = str_replace('{num3}', rand(2, 12), $qText);
            $qText = str_replace('{sqr}', rand(4, 25) ** 2, $qText);
            $qText = str_replace('{word}', $words_id_list[array_rand($words_id_list)], $qText);
            $qText = str_replace('{word2}', $logic_words[array_rand($logic_words)], $qText);
            $qText = str_replace('{word3}', $words_en_list[array_rand($words_en_list)], $qText);
            $qText = str_replace('{prep}', ['in', 'at', 'on', 'with'][rand(0, 3)], $qText);
            $qText = str_replace('{verb}', ['went', 'gone', 'going', 'go'][rand(0, 3)], $qText);

            $soal = Soal::create([
                'mata_uji_id'       => $mu->id,
                'konten_pertanyaan' => "<p>$qText</p>",
                'tipe_soal'         => 'Pilihan_Ganda',
                'tingkat_kesulitan' => ['Mudah', 'Sedang', 'Sulit'][rand(0, 2)],
                'dibuat_oleh'       => 1,
                'is_aktif'          => true,
            ]);

            // Create 5 options
            $correctIdx = rand(0, 4);
            $labels     = ['A', 'B', 'C', 'D', 'E'];
            foreach ($labels as $idx => $label) {
                $isCorrect = ($idx === $correctIdx);
                $ansText   = "Jawaban pilihan " . $label;

                if ($mu->nama_mata_uji === 'Matematika Dasar') {
                    $ansText = rand(10, 500);
                }

                OpsiJawaban::create([
                    'soal_id'          => $soal->id,
                    'label'            => $label,
                    'teks_jawaban'     => $ansText,
                    'is_kunci_jawaban' => $isCorrect,
                    'bobot_nilai'      => $isCorrect ? 1 : 0,
                ]);
            }
        }

        // 4. Create 10 Exam Packages
        $this->command->info('Creating 10 Varied Exam Packages...');
        $allSoalIds = Soal::pluck('id')->toArray();

        for ($p = 1; $p <= 10; $p++) {
            $paket = PaketUjian::create([
                'nama_paket'         => "Paket Soal Standar v.$p.0",
                'tipe_paket'         => 'PMB',
                'total_soal'         => 50,
                'total_durasi_menit' => 60,
                'is_acak_soal'       => (bool) rand(0, 1),
                'dibuat_oleh'        => 1,
            ]);

            // Assign 50 random questions to each package
            shuffle($allSoalIds);
            $selectedIds = array_slice($allSoalIds, 0, 50);

            foreach ($selectedIds as $index => $sid) {
                KomposisiPaket::create([
                    'paket_id'      => $paket->id,
                    'soal_id'       => $sid,
                    'urutan_tampil' => $index + 1,
                ]);
            }

            // 5. Create Jadwal Ujian for each package
            $now = now();
            JadwalUjian::create([
                'paket_id'       => $paket->id,
                'nama_kegiatan'  => "Ujian Saringan Masuk - " . $paket->nama_paket,
                'waktu_mulai'    => $now->copy()->subHours(rand(1, 24)),
                'waktu_selesai'  => $now->copy()->addDays(rand(1, 7)),
                'token_ujian'    => strtoupper(substr(md5(uniqid()), 0, 6)),
                'is_token_aktif' => true,
            ]);
        }

        $this->command->info('CBT Seeding completed successfully!');
    }
}
