<?php

namespace Database\Seeders;

use App\Models\Pengumuman;
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
            'Pengumuman Seminar Teknologi'
        ];

        $beritaTitles = [
            'Inovasi Terbaru di Laboratorium JTI',
            'Kolaborasi dengan Perusahaan Teknologi',
            'Prestasi Mahasiswa dalam Kompetisi Nasional',
            'Pengembangan Laboratorium Baru',
            'Kunjungan Industri ke Perusahaan Teknologi',
            'Pelatihan AI dan Machine Learning',
            'Implementasi Teknologi IoT di Laboratorium',
            'Publikasi Penelitian Internasional'
        ];

        $contents = [
            'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
            'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
            'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.',
            'Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.'
        ];

        // Create 50 pengumuman
        for ($i = 1; $i <= 25; $i++) {
            $title = $pengumumanTitles[array_rand($pengumumanTitles)] . ' ' . $i;
            Pengumuman::create([
                'judul' => $title,
                'isi' => $contents[array_rand($contents)],
                'jenis' => 'pengumuman',
                'penulis_id' => $userIds[array_rand($userIds)],
                'is_published' => true,
                'published_at' => fake()->dateTimeBetween('-2 weeks', 'now')
            ]);
        }

        // Create 50 berita
        for ($i = 1; $i <= 25; $i++) {
            $title = $beritaTitles[array_rand($beritaTitles)] . ' ' . $i;
            Pengumuman::create([
                'judul' => $title,
                'isi' => $contents[array_rand($contents)],
                'jenis' => 'berita',
                'penulis_id' => $userIds[array_rand($userIds)],
                'is_published' => true,
                'published_at' => fake()->dateTimeBetween('-1 month', '-1 week')
            ]);
        }

        $this->command->info('Created 50 pengumuman and 50 berita records.');
    }
}