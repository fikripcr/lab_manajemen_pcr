<?php

namespace Database\Seeders\Pemutu;

use App\Models\Pemutu\DokSub;
use App\Models\Pemutu\Dokumen;
use App\Models\Pemutu\Indikator;
use App\Models\Pemutu\Label;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder untuk Dokumen SPMI - Siklus Lengkap
 *
 * Hierarchy:
 * VISI (2 poin)
 *   ↓ mapped_by
 * MISI (5 poin) → mapped to VISI
 *   ↓ mapped_by
 * RJP (4 poin) → mapped to MISI
 *   ↓ mapped_by
 * RENSTRA (6 poin) → mapped to RJP
 *   ↓
 * RENOP (NO poin) → Direct indicators with 'renop' label
 */
class DokumenSpmiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $periode = 2024;
        $createdBy = 'Seeder';

        // ─────────────────────────────────────────────────────────
        // 1. CREATE LABEL FOR RENOP INDICATORS
        // ─────────────────────────────────────────────────────────
        // Check if label exists
        $existingLabel = DB::table('pemutu_label')->where('name', 'renop')->first();

        if ($existingLabel) {
            $renopLabelId = $existingLabel->label_id;
        } else {
            $renopLabelId = DB::table('pemutu_label')->insertGetId([
                'name' => 'renop',
                'color' => 'purple',
                'parent_id' => null,
                'created_by' => $createdBy,
            ]);
        }

        // ─────────────────────────────────────────────────────────
        // 2. CREATE VISI DOCUMENT (2 POIN)
        // ─────────────────────────────────────────────────────────
        $visi = Dokumen::create([
            'jenis' => 'visi',
            'periode' => $periode,
            'judul' => 'Visi Universitas 2024',
            'kode' => 'VISI-2024',
            'isi' => 'Menjadi perguruan tinggi vokasi yang unggul, inovatif, dan berdaya saing global pada tahun 2040.',
            'created_by' => $createdBy,
        ]);

        $visiPoin1 = DokSub::create([
            'dok_id' => $visi->dok_id,
            'jenis' => 'poin_visi',
            'seq' => 1,
            'judul' => 'Unggul dalam Pendidikan Vokasi',
            'kode' => 'VISI-2024.1',
            'isi' => 'Menyelenggarakan pendidikan vokasi yang berkualitas dengan standar nasional dan internasional.',
            'created_by' => $createdBy,
        ]);

        $visiPoin2 = DokSub::create([
            'dok_id' => $visi->dok_id,
            'jenis' => 'poin_visi',
            'seq' => 2,
            'judul' => 'Inovatif dan Berdaya Saing Global',
            'kode' => 'VISI-2024.2',
            'isi' => 'Mengembangkan inovasi dalam penelitian dan pengabdian masyarakat yang berdaya saing global.',
            'created_by' => $createdBy,
        ]);

        // ─────────────────────────────────────────────────────────
        // 3. CREATE MISI DOCUMENT (5 POIN) → Mapped to VISI
        // ─────────────────────────────────────────────────────────
        $misi = Dokumen::create([
            'jenis' => 'misi',
            'periode' => $periode,
            'judul' => 'Misi Universitas 2024',
            'kode' => 'MISI-2024',
            'isi' => 'Misi untuk mencapai visi universitas.',
            'created_by' => $createdBy,
        ]);

        $misiPoin1 = DokSub::create([
            'dok_id' => $misi->dok_id,
            'jenis' => 'poin_misi',
            'seq' => 1,
            'judul' => 'Penyelenggaraan Pendidikan Berkualitas',
            'kode' => 'MISI-2024.1',
            'isi' => 'Menyelenggarakan program studi yang relevan dengan kebutuhan industri.',
            'created_by' => $createdBy,
        ]);

        $misiPoin2 = DokSub::create([
            'dok_id' => $misi->dok_id,
            'jenis' => 'poin_misi',
            'seq' => 2,
            'judul' => 'Pengembangan Penelitian Terapan',
            'kode' => 'MISI-2024.2',
            'isi' => 'Mengembangkan penelitian terapan yang bermanfaat bagi masyarakat dan industri.',
            'created_by' => $createdBy,
        ]);

        $misiPoin3 = DokSub::create([
            'dok_id' => $misi->dok_id,
            'jenis' => 'poin_misi',
            'seq' => 3,
            'judul' => 'Pengabdian kepada Masyarakat',
            'kode' => 'MISI-2024.3',
            'isi' => 'Melaksanakan pengabdian kepada masyarakat untuk peningkatan kualitas hidup.',
            'created_by' => $createdBy,
        ]);

        $misiPoin4 = DokSub::create([
            'dok_id' => $misi->dok_id,
            'jenis' => 'poin_misi',
            'seq' => 4,
            'judul' => 'Kerjasama Nasional dan Internasional',
            'kode' => 'MISI-2024.4',
            'isi' => 'Mengembangkan kerjasama dengan institusi nasional dan internasional.',
            'created_by' => $createdBy,
        ]);

        $misiPoin5 = DokSub::create([
            'dok_id' => $misi->dok_id,
            'jenis' => 'poin_misi',
            'seq' => 5,
            'judul' => 'Tata Kelola yang Baik',
            'kode' => 'MISI-2024.5',
            'isi' => 'Menerapkan tata kelola universitas yang baik dan akuntabel.',
            'created_by' => $createdBy,
        ]);

        // Mapping MISI → VISI
        DB::table('pemutu_doksub_mapping')->insert([
            ['doksub_id' => $misiPoin1->doksub_id, 'mapped_doksub_id' => $visiPoin1->doksub_id, 'created_at' => now()],
            ['doksub_id' => $misiPoin2->doksub_id, 'mapped_doksub_id' => $visiPoin1->doksub_id, 'created_at' => now()],
            ['doksub_id' => $misiPoin2->doksub_id, 'mapped_doksub_id' => $visiPoin2->doksub_id, 'created_at' => now()],
            ['doksub_id' => $misiPoin3->doksub_id, 'mapped_doksub_id' => $visiPoin2->doksub_id, 'created_at' => now()],
            ['doksub_id' => $misiPoin4->doksub_id, 'mapped_doksub_id' => $visiPoin2->doksub_id, 'created_at' => now()],
            ['doksub_id' => $misiPoin5->doksub_id, 'mapped_doksub_id' => $visiPoin1->doksub_id, 'created_at' => now()],
        ]);

        // ─────────────────────────────────────────────────────────
        // 4. CREATE RJP DOCUMENT (4 POIN) → Mapped to MISI
        // ─────────────────────────────────────────────────────────
        $rjp = Dokumen::create([
            'jenis' => 'rjp',
            'periode' => $periode,
            'judul' => 'Rencana Pembangunan Jangka Panjang 2024-2044',
            'kode' => 'RJP-2024',
            'isi' => 'Rencana pembangunan jangka panjang universitas untuk 20 tahun.',
            'created_by' => $createdBy,
        ]);

        $rjpPoin1 = DokSub::create([
            'dok_id' => $rjp->dok_id,
            'jenis' => 'poin_rjp',
            'seq' => 1,
            'judul' => 'Pengembangan Infrastruktur Pendidikan',
            'kode' => 'RJP-2024.1',
            'isi' => 'Membangun infrastruktur pendidikan yang modern dan memadai.',
            'created_by' => $createdBy,
        ]);

        $rjpPoin2 = DokSub::create([
            'dok_id' => $rjp->dok_id,
            'jenis' => 'poin_rjp',
            'seq' => 2,
            'judul' => 'Peningkatan Kualitas SDM',
            'kode' => 'RJP-2024.2',
            'isi' => 'Meningkatkan kualifikasi dan kompetensi dosen dan tenaga kependidikan.',
            'created_by' => $createdBy,
        ]);

        $rjpPoin3 = DokSub::create([
            'dok_id' => $rjp->dok_id,
            'jenis' => 'poin_rjp',
            'seq' => 3,
            'judul' => 'Pengembangan Program Studi',
            'kode' => 'RJP-2024.3',
            'isi' => 'Membuka program studi baru yang sesuai dengan kebutuhan industri.',
            'created_by' => $createdBy,
        ]);

        $rjpPoin4 = DokSub::create([
            'dok_id' => $rjp->dok_id,
            'jenis' => 'poin_rjp',
            'seq' => 4,
            'judul' => 'Internasionalisasi Kampus',
            'kode' => 'RJP-2024.4',
            'isi' => 'Mengembangkan program internasional dan kerjasama dengan universitas luar negeri.',
            'created_by' => $createdBy,
        ]);

        // Mapping RJP → MISI
        DB::table('pemutu_doksub_mapping')->insert([
            ['doksub_id' => $rjpPoin1->doksub_id, 'mapped_doksub_id' => $misiPoin1->doksub_id, 'created_at' => now()],
            ['doksub_id' => $rjpPoin2->doksub_id, 'mapped_doksub_id' => $misiPoin2->doksub_id, 'created_at' => now()],
            ['doksub_id' => $rjpPoin3->doksub_id, 'mapped_doksub_id' => $misiPoin1->doksub_id, 'created_at' => now()],
            ['doksub_id' => $rjpPoin4->doksub_id, 'mapped_doksub_id' => $misiPoin4->doksub_id, 'created_at' => now()],
        ]);

        // ─────────────────────────────────────────────────────────
        // 5. CREATE RENSTRA DOCUMENT (6 POIN) → Mapped to RJP
        // ─────────────────────────────────────────────────────────
        $renstra = Dokumen::create([
            'jenis' => 'renstra',
            'periode' => $periode,
            'judul' => 'Rencana Strategis 2024-2028',
            'kode' => 'RENSTRA-2024',
            'isi' => 'Rencana strategis universitas untuk 5 tahun.',
            'created_by' => $createdBy,
        ]);

        $renstraPoin1 = DokSub::create([
            'dok_id' => $renstra->dok_id,
            'jenis' => 'poin_renstra',
            'seq' => 1,
            'judul' => 'Akreditasi Unggul untuk Semua Prodi',
            'kode' => 'RENSTRA-2024.1',
            'isi' => 'Mencapai akreditasi unggul untuk semua program studi.',
            'created_by' => $createdBy,
        ]);

        $renstraPoin2 = DokSub::create([
            'dok_id' => $renstra->dok_id,
            'jenis' => 'poin_renstra',
            'seq' => 2,
            'judul' => '80% Dosen Bergelar Doktor',
            'kode' => 'RENSTRA-2024.2',
            'isi' => 'Meningkatkan proporsi dosen bergelar doktor hingga 80%.',
            'created_by' => $createdBy,
        ]);

        $renstraPoin3 = DokSub::create([
            'dok_id' => $renstra->dok_id,
            'jenis' => 'poin_renstra',
            'seq' => 3,
            'judul' => '50 Publikasi Internasional per Tahun',
            'kode' => 'RENSTRA-2024.3',
            'isi' => 'Meningkatkan jumlah publikasi internasional menjadi 50 per tahun.',
            'created_by' => $createdBy,
        ]);

        $renstraPoin4 = DokSub::create([
            'dok_id' => $renstra->dok_id,
            'jenis' => 'poin_renstra',
            'seq' => 4,
            'judul' => '10 Kerjasama Internasional',
            'kode' => 'RENSTRA-2024.4',
            'isi' => 'Menjalin kerjasama dengan minimal 10 universitas internasional.',
            'created_by' => $createdBy,
        ]);

        $renstraPoin5 = DokSub::create([
            'dok_id' => $renstra->dok_id,
            'jenis' => 'poin_renstra',
            'seq' => 5,
            'judul' => 'Sertifikasi Internasional untuk Lulusan',
            'kode' => 'RENSTRA-2024.5',
            'isi' => '70% lulusan memiliki sertifikasi kompetensi internasional.',
            'created_by' => $createdBy,
        ]);

        $renstraPoin6 = DokSub::create([
            'dok_id' => $renstra->dok_id,
            'jenis' => 'poin_renstra',
            'seq' => 6,
            'judul' => 'Digitalisasi Kampus',
            'kode' => 'RENSTRA-2024.6',
            'isi' => 'Menerapkan sistem digital untuk seluruh proses bisnis universitas.',
            'created_by' => $createdBy,
        ]);

        // Mapping RENSTRA → RJP
        DB::table('pemutu_doksub_mapping')->insert([
            ['doksub_id' => $renstraPoin1->doksub_id, 'mapped_doksub_id' => $rjpPoin1->doksub_id, 'created_at' => now()],
            ['doksub_id' => $renstraPoin2->doksub_id, 'mapped_doksub_id' => $rjpPoin2->doksub_id, 'created_at' => now()],
            ['doksub_id' => $renstraPoin3->doksub_id, 'mapped_doksub_id' => $rjpPoin2->doksub_id, 'created_at' => now()],
            ['doksub_id' => $renstraPoin4->doksub_id, 'mapped_doksub_id' => $rjpPoin4->doksub_id, 'created_at' => now()],
            ['doksub_id' => $renstraPoin5->doksub_id, 'mapped_doksub_id' => $rjpPoin3->doksub_id, 'created_at' => now()],
            ['doksub_id' => $renstraPoin6->doksub_id, 'mapped_doksub_id' => $rjpPoin1->doksub_id, 'created_at' => now()],
        ]);

        // ─────────────────────────────────────────────────────────
        // 6. CREATE RENOP DOCUMENT (NO POIN!) → Direct Indicators
        // ─────────────────────────────────────────────────────────
        $renop = Dokumen::create([
            'jenis' => 'renop',
            'periode' => $periode,
            'judul' => 'Rencana Operasional 2024',
            'kode' => 'RENOP-2024',
            'isi' => 'Rencana operasional tahunan universitas.',
            'created_by' => $createdBy,
        ]);

        // RENOP TIDAK PUNYA POIN! Langsung create indicators dengan label 'renop'
        // Indicators will be linked directly to RENOP document, not through poin

        // ─────────────────────────────────────────────────────────
        // 7. CREATE RENOP INDICATORS (with 'renop' label)
        // ─────────────────────────────────────────────────────────

        // Indikator 1 - From Renop Poin 1
        $indikator1 = Indikator::create([
            'type' => 'renop',
            'no_indikator' => 'RENOP-2024.001',
            'indikator' => 'Persentase Prodi Terakreditasi Unggul',
            'target' => '25',
            'unit_ukuran' => '%',
            'jenis_data' => 'persentase',
            'seq' => 1,
            'created_by' => $createdBy,
        ]);
        DB::table('pemutu_indikator_label')->insert([
            ['indikator_id' => $indikator1->indikator_id, 'label_id' => $renopLabelId],
        ]);

        // Indikator 2 - From Renop Poin 1
        $indikator2 = Indikator::create([
            'type' => 'renop',
            'no_indikator' => 'RENOP-2024.002',
            'indikator' => 'IPK Lulusan Rata-rata',
            'target' => '3.50',
            'unit_ukuran' => 'IPK',
            'jenis_data' => 'angka',
            'seq' => 2,
            'created_by' => $createdBy,
        ]);
        DB::table('pemutu_indikator_label')->insert(['indikator_id' => $indikator2->indikator_id, 'label_id' => $renopLabelId]);

        // Indikator 3 - From Renop Poin 2
        $indikator3 = Indikator::create([
            'type' => 'renop',
            'no_indikator' => 'RENOP-2024.003',
            'indikator' => 'Persentase Dosen Bergelar Doktor',
            'target' => '45',
            'unit_ukuran' => '%',
            'jenis_data' => 'persentase',
            'seq' => 3,
            'created_by' => $createdBy,
        ]);
        DB::table('pemutu_indikator_label')->insert(['indikator_id' => $indikator3->indikator_id, 'label_id' => $renopLabelId]);

        // Indikator 4 - From Renop Poin 2
        $indikator4 = Indikator::create([
            'type' => 'renop',
            'no_indikator' => 'RENOP-2024.004',
            'indikator' => 'Jumlah Publikasi Internasional',
            'target' => '50',
            'unit_ukuran' => 'Paper',
            'jenis_data' => 'angka',
            'seq' => 4,
            'created_by' => $createdBy,
        ]);
        DB::table('pemutu_indikator_label')->insert(['indikator_id' => $indikator4->indikator_id, 'label_id' => $renopLabelId]);

        // Indikator 5 - From Renop Poin 3
        $indikator5 = Indikator::create([
            'type' => 'renop',
            'no_indikator' => 'RENOP-2024.005',
            'indikator' => 'Jumlah Kerjasama Internasional Baru',
            'target' => '3',
            'unit_ukuran' => 'MoU',
            'jenis_data' => 'angka',
            'seq' => 5,
            'created_by' => $createdBy,
        ]);
        DB::table('pemutu_indikator_label')->insert(['indikator_id' => $indikator5->indikator_id, 'label_id' => $renopLabelId]);

        // Indikator 6 - From Renop Poin 3
        $indikator6 = Indikator::create([
            'type' => 'renop',
            'no_indikator' => 'RENOP-2024.006',
            'indikator' => 'Persentase Lulusan dengan Sertifikasi Internasional',
            'target' => '30',
            'unit_ukuran' => '%',
            'jenis_data' => 'persentase',
            'seq' => 6,
            'created_by' => $createdBy,
        ]);
        DB::table('pemutu_indikator_label')->insert(['indikator_id' => $indikator6->indikator_id, 'label_id' => $renopLabelId]);
    }
}
