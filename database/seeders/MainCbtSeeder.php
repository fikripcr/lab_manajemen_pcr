<?php
namespace Database\Seeders;

use App\Models\Cbt\KomposisiPaket;
use App\Models\Cbt\MataUji;
use App\Models\Cbt\OpsiJawaban;
use App\Models\Cbt\PaketUjian;
use App\Models\Cbt\Soal;
use Illuminate\Database\Seeder;

class MainCbtSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Mata Uji
        // 1. Mata Uji
        $muMatika  = MataUji::create(['nama_mata_uji' => 'Matematika Dasar', 'deskripsi' => 'Logika dan Aritmatika', 'tipe' => 'PMB']);
        $muIndo    = MataUji::create(['nama_mata_uji' => 'Bahasa Indonesia', 'deskripsi' => 'Pemahaman Bacaan', 'tipe' => 'PMB']);
        $muInggris = MataUji::create(['nama_mata_uji' => 'Bahasa Inggris', 'deskripsi' => 'Grammar & Vocabulary', 'tipe' => 'PMB']);

        // 2. SOAL GENERATION (300 Questions)
        $this->command->info('Generating 300 CBT Questions...');

        $subjects = [
            [
                'id'        => $muMatika->id,
                'name'      => 'Matematika',
                'templates' => [
                    'Hitunglah hasil dari {num1} + {num2} * {num3}',
                    'Jika x = {num1} dan y = {num2}, maka nilai 2x - y adalah...',
                    'Sebuah segitiga memiliki alas {num1} cm dan tinggi {num2} cm. Luasnya adalah...',
                    'Berapakah {num1}% dari {num2}000?',
                    'Akar kuadrat dari {sqr} adalah...',
                ],
            ],
            [
                'id'        => $muIndo->id,
                'name'      => 'Bahasa Indonesia',
                'templates' => [
                    'Manakah kata baku yang benar dari "{word}"?',
                    'Sinonim dari kata "{word}" adalah...',
                    'Ide pokok paragraf di atas adalah tentang...',
                    'Kalimat berikut yang merupakan fakta adalah...',
                    'Penulisan gelar yang benar adalah...',
                ],
            ],
            [
                'id'        => $muInggris->id,
                'name'      => 'Bahasa Inggris',
                'templates' => [
                    'What is the synonym of "{word}"?',
                    'Choose the correct verb form: She {verb} to the market yesterday.',
                    'The main idea of the passage is...',
                    'Antonym of "{word}" is...',
                    'Which sentence is grammatically correct?',
                ],
            ],
        ];

        foreach ($subjects as $subject) {
            for ($i = 0; $i < 100; $i++) {
                $template = $subject['templates'][array_rand($subject['templates'])];

                // Simple placeholder replacement logic based on subject
                $questionText = $template;
                if ($subject['name'] == 'Matematika') {
                    $questionText = str_replace('{num1}', rand(1, 50), $questionText);
                    $questionText = str_replace('{num2}', rand(1, 20), $questionText);
                    $questionText = str_replace('{num3}', rand(1, 10), $questionText);
                    $questionText = str_replace('{sqr}', rand(1, 20) ** 2, $questionText);
                } elseif ($subject['name'] == 'Bahasa Indonesia') {
                    $words        = ['kualitas', 'praktik', 'nasihat', 'apotek', 'analisis'];
                    $questionText = str_replace('{word}', $words[array_rand($words)], $questionText);
                    // Add some dummy context if needed for "di atas" questions
                    if (str_contains($questionText, 'di atas')) {
                        $questionText = "<p>Lorem ipsum dolor sit amet...</p><p>$questionText</p>";
                    } else {
                        $questionText = "<p>$questionText</p>";
                    }
                } else {
                    $words        = ['happy', 'fast', 'smart', 'allow', 'begin'];
                    $verbs        = ['went', 'go', 'gone', 'going'];
                    $questionText = str_replace('{word}', $words[array_rand($words)], $questionText);
                    $questionText = str_replace('{verb}', '_____', $questionText);
                    $questionText = "<p>$questionText</p>";
                }

                $soal = Soal::create([
                    'mata_uji_id'       => $subject['id'],
                    'konten_pertanyaan' => $questionText,
                    'tipe_soal'         => 'Pilihan_Ganda',
                    // 'bobot' removed as it is not in schema
                    'tingkat_kesulitan' => ['Mudah', 'Sedang', 'Sulit'][rand(0, 2)],
                    'dibuat_oleh'       => 1, // Admin
                    'is_aktif'          => true,
                ]);

                // Options
                $correctIndex = rand(0, 4);
                $options      = ['A', 'B', 'C', 'D', 'E'];
                foreach ($options as $idx => $label) {
                    $isCorrect   = ($idx == $correctIndex);
                    $dummyAnswer = 'Jawaban ' . $label . ' untuk soal ' . ($i + 1);

                    if ($subject['name'] == 'Matematika') {
                        $dummyAnswer = rand(10, 1000);
                    }

                    OpsiJawaban::create([
                        'soal_id'          => $soal->id,
                        'label'            => $label,
                        'teks_jawaban'     => $dummyAnswer,
                        'is_kunci_jawaban' => $isCorrect,
                        'bobot_nilai'      => $isCorrect ? 5 : 0,
                    ]);
                }
            }
        }

        // 3. Paket Ujian (Already verified in previous implementation but keeping it)
        $paket = PaketUjian::create([
            'nama_paket'         => 'Paket Seleksi 2024 - Gelombang 1',
            'tipe_paket'         => 'PMB',
            'total_soal'         => 100, // Just a sample
            'total_durasi_menit' => 90,
            'is_acak_soal'       => true,
            'dibuat_oleh'        => 1, // Admin
        ]);

        // Link random 100 questions to Paket
        $allSoalIds = Soal::pluck('id')->toArray();
        shuffle($allSoalIds);
        $selectedSoalIds = array_slice($allSoalIds, 0, 100);

        foreach ($selectedSoalIds as $index => $soalId) {
            KomposisiPaket::create([
                'paket_id'      => $paket->id,
                'soal_id'       => $soalId,
                'urutan_tampil' => $index + 1,
            ]);
        }
    }
}
