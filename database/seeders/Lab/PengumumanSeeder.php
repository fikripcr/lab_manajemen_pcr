<?php
namespace Database\Seeders\Lab;

use App\Models\Lab\Pengumuman;
use App\Models\User;
use Illuminate\Database\Seeder;

class PengumumanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some user IDs for authors
        $userIds = User::pluck('id')->toArray();

        if (empty($userIds)) {
            $this->command->info('No users found. Creating sample users first...');
            $this->call(UserSeeder::class);
            $userIds = User::pluck('id')->toArray();
        }

        $pengumumanTitles = [
            'Pengumuman Jadwal Praktikum Minggu Ini',
            'Pemberitahuan Perawatan Laboratorium',
            'Jadwal Maintenance Server',
            'Pengumuman Penutupan Sementara Lab',
            'Pengumuman Libur Nasional',
            'Pengumuman Workshop Teknologi',
            'Pengumuman Pelatihan Jaringan',
            'Pengumuman Seminar Teknologi',
        ];

        $beritaTitles = [
            'Inovasi Terbaru di Laboratorium JTI',
            'Kolaborasi dengan Perusahaan Teknologi',
            'Prestasi Mahasiswa dalam Kompetisi Nasional',
            'Pengembangan Laboratorium Baru',
            'Kunjungan Industri ke Perusahaan Teknologi',
            'Pelatihan AI dan Machine Learning',
            'Implementasi Teknologi IoT di Laboratorium',
            'Publikasi Penelitian Internasional',
        ];

        $contents = [
            'Laboratorium akan ditutup sementara untuk perawatan rutin pada tanggal 15-20 bulan ini. Mahasiswa diharapkan mengatur jadwal praktikum mereka sesuai dengan jadwal penutupan tersebut.',
            'Kami mengumumkan bahwa laboratorium baru untuk pemrograman dan pengembangan perangkat lunak akan segera selesai dibangun. Perkiraan selesai pada akhir bulan ini.',
            'Diberitahukan bahwa pelatihan peningkatan kapasitas untuk teknisi laboratorium akan diselenggarakan pada tanggal 25 Februari 2024. Wajib bagi seluruh teknisi untuk mengikuti pelatihan ini.',
            'Peminjaman peralatan laboratorium hanya dapat dilakukan oleh mahasiswa yang telah menyelesaikan pelatihan keselamatan dan telah mendapatkan izin dari dosen pembimbing praktikum.',
        ];

        // Create 500 pengumuman
        for ($i = 1; $i <= 500; $i++) {
            $faker = \Faker\Factory::create('id_ID'); // Use Indonesian locale

            $title = $faker->sentence();
            Pengumuman::create([
                'judul'        => $title,
                'isi'          => $faker->paragraphs(5, true),
                'jenis'        => 'pengumuman',
                'penulis_id'   => $userIds[array_rand($userIds)],
                'is_published' => $faker->boolean(80), // 80% chance of being published
                'published_at' => $faker->dateTimeBetween('-1 year', 'now'),
            ]);
        }

        // Create 500 berita
        for ($i = 1; $i <= 500; $i++) {
            $faker = \Faker\Factory::create('id_ID'); // Use Indonesian locale

            $title = $faker->sentence();
            Pengumuman::create([
                'judul'        => $title,
                'isi'          => $faker->paragraphs(5, true),
                'jenis'        => 'artikel_berita', // Using the type from our migration
                'penulis_id'   => $userIds[array_rand($userIds)],
                'is_published' => $faker->boolean(80), // 80% chance of being published
                'published_at' => $faker->dateTimeBetween('-1 year', 'now'),
            ]);
        }

        $this->command->info('Berhasil membuat 500 pengumuman dan 500 artikel berita.');
    }
}
