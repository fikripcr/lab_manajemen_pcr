<?php
namespace Database\Seeders\Pemutu;

use App\Models\Pemutu\DokSub;
use App\Models\Pemutu\Indikator;
use App\Models\Pemutu\Label;
use App\Models\Pemutu\OrgUnit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IndikatorSeeder extends Seeder
{
    /**
     * Seed 30 realistic Indonesian education indicators.
     */
    public function run(): void
    {
        // Truncate first
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('indikator_label')->truncate();
        DB::table('indikator_orgunit')->truncate();
        Indikator::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Get first 30 DokSubs to attach indicators
        $dokSubs = DokSub::limit(30)->get();
        if ($dokSubs->isEmpty()) {
            $this->command->error('No DokSub data found. Please run DokumenSeeder first.');
            return;
        }

        // Get labels and org units
        $labels   = Label::all();
        $orgUnits = OrgUnit::whereIn('type', ['Prodi', 'Bagian', 'Unit', 'Jurusan'])->get();

        // Realistic education indicators in Indonesian
        $indicators = [
            ['indikator' => 'Persentase lulusan yang bekerja sesuai bidang dalam 6 bulan setelah wisuda', 'target' => '≥ 80%'],
            ['indikator' => 'Rata-rata IPK lulusan program studi', 'target' => '≥ 3.25'],
            ['indikator' => 'Persentase dosen dengan kualifikasi S3 atau Profesor', 'target' => '≥ 30%'],
            ['indikator' => 'Rasio jumlah mahasiswa terhadap dosen tetap', 'target' => '1:25'],
            ['indikator' => 'Jumlah publikasi ilmiah dosen di jurnal terakreditasi nasional', 'target' => '≥ 20 artikel/tahun'],
            ['indikator' => 'Jumlah publikasi ilmiah dosen di jurnal internasional bereputasi', 'target' => '≥ 5 artikel/tahun'],
            ['indikator' => 'Persentase kehadiran mahasiswa dalam perkuliahan', 'target' => '≥ 85%'],
            ['indikator' => 'Tingkat kepuasan mahasiswa terhadap proses pembelajaran', 'target' => '≥ 3.5 (skala 4)'],
            ['indikator' => 'Persentase mata kuliah yang menggunakan e-learning', 'target' => '≥ 70%'],
            ['indikator' => 'Jumlah kerjasama dengan industri untuk program magang', 'target' => '≥ 30 MoU aktif'],
            ['indikator' => 'Persentase lulusan yang melanjutkan studi ke jenjang lebih tinggi', 'target' => '≥ 10%'],
            ['indikator' => 'Rata-rata masa tunggu lulusan mendapatkan pekerjaan pertama', 'target' => '≤ 3 bulan'],
            ['indikator' => 'Jumlah hibah penelitian yang diperoleh dosen', 'target' => '≥ 15 hibah/tahun'],
            ['indikator' => 'Jumlah kegiatan pengabdian kepada masyarakat per program studi', 'target' => '≥ 5 kegiatan/tahun'],
            ['indikator' => 'Persentase laboratorium yang terakreditasi atau tersertifikasi', 'target' => '100%'],
            ['indikator' => 'Tingkat kepuasan pengguna lulusan terhadap kompetensi alumni', 'target' => '≥ 3.5 (skala 4)'],
            ['indikator' => 'Jumlah HAKI yang didaftarkan oleh civitas akademika', 'target' => '≥ 10 HAKI/tahun'],
            ['indikator' => 'Persentase dosen yang memiliki sertifikat pendidik', 'target' => '≥ 90%'],
            ['indikator' => 'Rasio ketersediaan buku perpustakaan terhadap jumlah mahasiswa', 'target' => '1:10'],
            ['indikator' => 'Persentase ruang kelas yang dilengkapi fasilitas multimedia', 'target' => '100%'],
            ['indikator' => 'Jumlah mahasiswa yang mengikuti kompetisi tingkat nasional', 'target' => '≥ 50 mahasiswa/tahun'],
            ['indikator' => 'Persentase mahasiswa penerima beasiswa', 'target' => '≥ 25%'],
            ['indikator' => 'Jumlah kunjungan industri atau studi lapangan per semester', 'target' => '≥ 3 kunjungan/prodi'],
            ['indikator' => 'Persentase kurikulum yang direview sesuai kebutuhan industri', 'target' => '100% setiap 4 tahun'],
            ['indikator' => 'Tingkat kelulusan tepat waktu mahasiswa', 'target' => '≥ 60%'],
            ['indikator' => 'Jumlah prestasi mahasiswa di tingkat internasional', 'target' => '≥ 5 prestasi/tahun'],
            ['indikator' => 'Persentase alumni yang terdaftar di asosiasi profesi', 'target' => '≥ 50%'],
            ['indikator' => 'Jumlah dosen yang mengikuti pelatihan pengembangan kompetensi', 'target' => '≥ 80% dosen/tahun'],
            ['indikator' => 'Indeks efektivitas sistem informasi akademik', 'target' => '≥ 3.5 (skala 4)'],
            ['indikator' => 'Persentase ketersediaan dokumen mutu yang up-to-date', 'target' => '100%'],
        ];

        $seq = 1;
        foreach ($indicators as $index => $data) {
            // Cycle through available dokSubs
            $dokSub = $dokSubs[$index % $dokSubs->count()];

            $indikator = Indikator::create([
                'doksub_id'       => $dokSub->doksub_id,
                'no_indikator'    => 'IND-' . str_pad($seq, 3, '0', STR_PAD_LEFT),
                'indikator'       => $data['indikator'],
                'target'          => $data['target'],
                'jenis_indikator' => ['IKU', 'IKT', 'Mandiri'][rand(0, 2)],
                'jenis_data'      => rand(0, 1) ? 'Kuantitatif' : 'Kualitatif',
                'periode_jenis'   => ['Tahunan', 'Semester', 'Triwulan'][rand(0, 2)],
                'keterangan'      => null,
                'seq'             => $seq,
                'level_risk'      => ['Low', 'Medium', 'High'][rand(0, 2)],
                'origin_from'     => ['Renstra', 'Renop', 'Audit'][rand(0, 2)],
            ]);

            // Attach 1-3 random labels
            if ($labels->count() > 0) {
                $randomLabels = $labels->random(min(rand(1, 3), $labels->count()))->pluck('label_id');
                $indikator->labels()->attach($randomLabels);
            }

            // Attach 1-3 random org units with targets
            if ($orgUnits->count() > 0) {
                $randomUnits = $orgUnits->random(min(rand(1, 3), $orgUnits->count()));
                foreach ($randomUnits as $unit) {
                    $indikator->orgUnits()->attach($unit->orgunit_id, [
                        'target' => $data['target'],
                    ]);
                }
            }

            $seq++;
        }

        $this->command->info('IndikatorSeeder completed! Total: ' . Indikator::count() . ' indicators created.');
    }
}
