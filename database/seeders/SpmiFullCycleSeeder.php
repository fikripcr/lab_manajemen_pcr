<?php
namespace Database\Seeders;

use App\Models\Pemutu\Indikator;
use App\Models\Pemutu\IndikatorOrgUnit;
use App\Models\Pemutu\OrgUnit;
use App\Models\Pemutu\PeriodeSpmi;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SpmiFullCycleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('SpmiFullCycleSeeder started...');

        // 1. Dapatkan 3 Unit (Prodi)
        $units = OrgUnit::where('type', 'Prodi')->limit(3)->get();
        if ($units->count() < 3) {
            $this->command->error('Kurang dari 3 Prodi ditemukan di tabel org_units. Harap jalankan seeder struktur organisasi terlebih dahulu.');
            return;
        }

        $this->command->info('Unit yang digunakan: ' . $units->pluck('name')->implode(', '));

        $now = now();

        // 2. Buat / Dapatkan Periode SPMI (Tahun 1 & Tahun 2)
        $periode1 = PeriodeSpmi::firstOrCreate(['periode' => 2024, 'jenis_periode' => 'Akademik'], [
            'penetapan_awal'     => Carbon::parse('2024-01-01'),
            'penetapan_akhir'    => Carbon::parse('2024-02-28'),
            'ed_awal'            => Carbon::parse('2024-03-01'),
            'ed_akhir'           => Carbon::parse('2024-04-30'),
            'ami_awal'           => Carbon::parse('2024-05-01'),
            'ami_akhir'          => Carbon::parse('2024-06-30'),
            'pengendalian_awal'  => Carbon::parse('2024-07-01'),
            'pengendalian_akhir' => Carbon::parse('2024-08-31'),
            'peningkatan_awal'   => Carbon::parse('2024-09-01'),
            'peningkatan_akhir'  => Carbon::parse('2024-10-31'),
        ]);

        $periode2 = PeriodeSpmi::firstOrCreate(['periode' => 2025, 'jenis_periode' => 'Akademik'], [
            'penetapan_awal'     => Carbon::parse('2025-01-01'),
            'penetapan_akhir'    => Carbon::parse('2025-02-28'),
            'ed_awal'            => Carbon::parse('2025-03-01'),
            'ed_akhir'           => Carbon::parse('2025-04-30'),
            'ami_awal'           => Carbon::parse('2025-05-01'),
            'ami_akhir'          => Carbon::parse('2025-06-30'),
            'pengendalian_awal'  => Carbon::parse('2025-07-01'),
            'pengendalian_akhir' => Carbon::parse('2025-08-31'),
            'peningkatan_awal'   => Carbon::parse('2025-09-01'),
            'peningkatan_akhir'  => Carbon::parse('2025-10-31'),
        ]);

        // Daftar 10 Standar Indikator
        $standarList = [
            'Standar Kompetensi Lulusan',
            'Standar Isi Pembelajaran',
            'Standar Proses Pembelajaran',
            'Standar Penilaian Pembelajaran',
            'Standar Dosen dan Tenaga Kependidikan',
            'Standar Sarana Prasarana',
            'Standar Pengelolaan Pembelajaran',
            'Standar Pembiayaan Pembelajaran',
            'Standar Penelitian',
            'Standar Pengabdian kepada Masyarakat',
        ];

        // TAHUN 1 (2024)
        $this->command->info('Membangun Siklus SPMI Tahun 1 (2024) - Siklus Penuh Selesai');
        $indikatorTahun1        = [];
        $indikatorOrgUnitTahun1 = []; // Maps IndikatorID -> OrgUnitID -> IndikatorOrgUnit record

        foreach ($standarList as $idx => $namaStandar) {
            $ind = Indikator::create([
                'type'               => 'standar',
                'kelompok_indikator' => 'Akademik',
                'no_indikator'       => '24' . str_pad($idx + 1, 4, '0', STR_PAD_LEFT),
                'indikator'          => "Ketercapaian IKU pada $namaStandar",
                'target'             => rand(80, 100) . '%',
                'jenis_indikator'    => 'Utama',
                'periode_mulai'      => 2024,
                'periode_selesai'    => 2024,
                'created_by'         => 1,
            ]);
            $indikatorTahun1[$idx] = $ind;

            // Mapping ke 3 Unit
            foreach ($units as $unit) {
                                                               // Semua fase diselesaikan di Tahun 1
                $isTercapai    = rand(0, 1) == 1;              // 50% chance Tercapai
                $amiHasilAkhir = $isTercapai ? rand(1, 2) : 0; // 0=KTS, 1=Terpenuhi, 2=Terlampaui

                $iou = IndikatorOrgUnit::create([
                    'indikator_id' => $ind->indikator_id,
                    'org_unit_id'  => $unit->orgunit_id,
                    'target'       => $ind->target,
                    // Evaluasi Diri
                    'ed_capaian'   => ($isTercapai ? rand(80, 100) : rand(50, 79)) . '%',
                    'ed_skala'     => $isTercapai ? rand(3, 4) : rand(1, 2),
                    'ed_analisis'  => "Analisis evaluasi diri untuk $namaStandar tahun 2024 di unit {$unit->code} telah diselesaikan.",
                    // AMI
                    'ami_hasil_akhir'         => $amiHasilAkhir,
                    'ami_hasil_temuan'        => $amiHasilAkhir == 0 ? 'Ditemukan ketidaksesuaian operasional terkait SOP pelaksanaan.' : null,
                    'ami_hasil_temuan_sebab'  => $amiHasilAkhir == 0 ? 'Kendala koordinasi dan sumber daya.' : null,
                    'ami_hasil_temuan_akibat' => $amiHasilAkhir == 0 ? 'Beberapa target teknis tertunda.' : null,
                    'ami_hasil_temuan_rekom'  => $amiHasilAkhir == 0 ? 'Perlu dibuatkan SOP yang lebih jelas dan training berkala.' : null,
                    // RTP / Tindak Lanjut
                    'ami_rtp_isi'             => 'Melaksanakan perencanaan perbaikan sistem secara menyeluruh pada triwulan 3.',
                    'ami_rtp_tgl_pelaksanaan' => Carbon::parse('2024-07-15'),
                    // PTP / Pelaksanaan Tindak Lanjut
                    'ed_ptp_isi'              => 'Perbaikan telah diimplementasikan sesuai dengan rekomendasi auditor AMI.',
                    // Tinjauan / TE
                    'ami_te_isi'              => 'Hasil tinjauan menunjukkan bahwa langkah perbaikan efektif meningkatkan capaian indikator secara signifikan.',
                    // Pengendalian
                    'pengend_status'          => 'Selesai',
                    'pengend_analisis'        => 'Proses telah berjalan baik. ' . ($isTercapai ? 'Standar siap untuk ditingkatkan tahun depan.' : 'Fokuskan pada penutupan GAP yang masih ada.'),
                    'pengend_penyesuaian'     => $isTercapai ? 'Target dinaikkan 5%' : 'Penyesuaian metode kerja di tingkat jurusan',

                    'created_at'              => Carbon::parse('2024-03-01'),
                    'updated_at'              => Carbon::parse('2024-08-01'),
                ]);

                $indikatorOrgUnitTahun1[$ind->indikator_id][$unit->orgunit_id] = $iou;
            }
        }

        // TAHUN 2 (2025)
        $this->command->info('Membangun Siklus SPMI Tahun 2 (2025) - Terhubung cross-year (On Progress)');

        foreach ($standarList as $idx => $namaStandar) {
            $prevInd = $indikatorTahun1[$idx];

            $ind = Indikator::create([
                'type'               => 'standar',
                'kelompok_indikator' => 'Akademik',
                'no_indikator'       => '25' . str_pad($idx + 1, 4, '0', STR_PAD_LEFT),
                'indikator'          => "Ketercapaian IKU pada $namaStandar",
                'target'             => rand(85, 100) . '%', // Sedikit peningkatan target
                'jenis_indikator'    => 'Utama',
                'periode_mulai'      => 2025,
                'periode_selesai'    => 2025,
                'prev_indikator_id'  => $prevInd->indikator_id, // Terhubung ke tahun sebelumnya
                'created_by'         => 1,
            ]);

            // Mapping ke 3 Unit yang sama
            foreach ($units as $unit) {
                $prevIou = $indikatorOrgUnitTahun1[$prevInd->indikator_id][$unit->orgunit_id];

                                                   // Di tahun 2, siklus mungkin baru sampai AMI atau ED
                $isSelesaiEd    = rand(0, 1) == 1; // Randomize progress
                $isTercapai2    = true;
                $amiHasilAkhir2 = null;

                if ($isSelesaiEd) {
                    $isTercapai2    = rand(0, 1) == 1;
                    $amiHasilAkhir2 = $isTercapai2 ? rand(1, 2) : 0;
                }

                IndikatorOrgUnit::create([
                    'indikator_id'         => $ind->indikator_id,
                    'org_unit_id'          => $unit->orgunit_id,
                    'prev_indikorgunit_id' => $prevIou->indikorgunit_id, // Terhubung ke pemetaan prodi tahun sebelumnya
                    'target'               => $ind->target,
                    // Evaluasi Diri
                    'ed_capaian'           => $isSelesaiEd ? (($isTercapai2 ? rand(85, 100) : rand(60, 84)) . '%') : null,
                    'ed_skala'             => $isSelesaiEd ? ($isTercapai2 ? rand(3, 4) : rand(2, 3)) : null,
                    'ed_analisis'          => $isSelesaiEd ? "Analisis tahun 2025 berjalan baik. Upaya peningkatan berdasar tinjauan efektivitas tahun lalu direalisasikan." : null,
                    // AMI (sebagian sedang dinilai)
                    'ami_hasil_akhir'      => $isSelesaiEd ? $amiHasilAkhir2 : null,
                    'ami_hasil_temuan'     => ($isSelesaiEd && $amiHasilAkhir2 === 0) ? 'Dokumentasi pendukung belum lengkap diunggah ke sistem.' : null,
                    // Sisa field dikosongkan untuk memberi kesan bahwa tahun berjalan belum sampai RTP/PTP

                    'created_at'           => Carbon::parse('2025-01-05'),
                    'updated_at'           => $isSelesaiEd ? Carbon::parse('2025-05-15') : Carbon::parse('2025-02-10'),
                ]);
            }
        }

        $this->command->info('SpmiFullCycleSeeder completed successfully. 10 Indikator dipetakan ke 3 Unit melintasi 2 Tahun.');
    }
}
