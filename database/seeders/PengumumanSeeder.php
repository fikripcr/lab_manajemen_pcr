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

        // Create 500 pengumuman
        for ($i = 1; $i <= 500; $i++) {
            $title = fake()->sentence();
            Pengumuman::create([
                'judul' => $title,
                'isi' => fake()->paragraphs(5, true),
                'jenis' => 'pengumuman',
                'penulis_id' => $userIds[array_rand($userIds)],
                'is_published' => fake()->boolean(80), // 80% chance of being published
                'published_at' => fake()->dateTimeBetween('-1 year', 'now')
            ]);
        }

        // Create 500 berita
        for ($i = 1; $i <= 500; $i++) {
            $title = fake()->sentence();
            Pengumuman::create([
                'judul' => $title,
                'isi' => fake()->paragraphs(5, true),
                'jenis' => 'artikel_berita', // Using the type from our migration
                'penulis_id' => $userIds[array_rand($userIds)],
                'is_published' => fake()->boolean(80), // 80% chance of being published
                'published_at' => fake()->dateTimeBetween('-1 year', 'now')
            ]);
        }

        $this->command->info('Created 500 pengumuman and 500 artikel_berita records.');
    }
}