<?php

namespace Database\Seeders;

use App\Models\Pemutu\Indikator;
use App\Models\Pemutu\IndikatorOrgUnit;
use App\Models\Pemutu\OrgUnit;
use App\Models\Pemutu\PeriodeSpmi;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class Spmi20252026Seeder extends Seeder
{
    public function run()
    {
        $this->command->info('Membuat Data Ujian untuk Pelaksanaan Perbaikan (ED) dan Tinjauan Efektivitas (AMI)...');

        $units = OrgUnit::where('type', 'Prodi')->limit(1)->get();
        if ($units->count() < 1) {
            $this->command->error('Tidak ada Prodi ditemukan.');

            return;
        }
        $unit = $units->first();

        // Ensure periods exist
        PeriodeSpmi::firstOrCreate(['periode' => 2024, 'jenis_periode' => 'Akademik'], ['penetapan_awal' => Carbon::now()->subYears(2), 'ed_awal' => Carbon::now()->subYears(2), 'ami_awal' => Carbon::now()->subYears(2)]);
        PeriodeSpmi::firstOrCreate(['periode' => 2025, 'jenis_periode' => 'Akademik'], ['penetapan_awal' => Carbon::now()->subYears(1), 'ed_awal' => Carbon::now()->subYears(1), 'ami_awal' => Carbon::now()->subYears(1)]);
        PeriodeSpmi::firstOrCreate(['periode' => 2026, 'jenis_periode' => 'Akademik'], ['penetapan_awal' => Carbon::now(), 'ed_awal' => Carbon::now(), 'ami_awal' => Carbon::now()]);

        // ==========================================
        // Skenario 1: Test Pelaksanaan Perbaikan (Di ED 2025)
        // ==========================================
        $ind1_2024 = Indikator::create([
            'type' => 'standar', 'kelompok_indikator' => 'Akademik', 'no_indikator' => 'TEST-01-24',
            'indikator' => '[TEST 1] Indikator Uji Pelaksanaan Perbaikan (2024)', 'target' => '100%',
            'periode_mulai' => 2024, 'periode_selesai' => 2024, 'created_by' => 1,
        ]);
        $iou1_2024 = IndikatorOrgUnit::create([
            'indikator_id' => $ind1_2024->indikator_id, 'org_unit_id' => $unit->orgunit_id, 'target' => '100%',
            'ed_capaian' => '70%', 'ed_skala' => 2,
            'ami_hasil_akhir' => 0, 'ami_hasil_temuan' => 'Temuan 2024',
            'ami_rtp_isi' => 'Rencana Tindak Perbaikan 2024: Review dan update modul praktikum',
        ]);

        $ind1_2025 = Indikator::create([
            'type' => 'standar', 'kelompok_indikator' => 'Akademik', 'no_indikator' => 'TEST-01-25',
            'indikator' => '[TEST 1] Indikator Uji Pelaksanaan Perbaikan (2025)', 'target' => '100%',
            'periode_mulai' => 2025, 'periode_selesai' => 2025, 'created_by' => 1,
            'prev_indikator_id' => $ind1_2024->indikator_id,
        ]);
        IndikatorOrgUnit::create([
            'indikator_id' => $ind1_2025->indikator_id, 'org_unit_id' => $unit->orgunit_id, 'target' => '100%',
            'prev_indikorgunit_id' => $iou1_2024->indikorgunit_id,
        ]);

        // ==========================================
        // Skenario 2: Test Tinjauan Efektivitas (Di AMI 2026)
        // ==========================================
        $ind2_2025 = Indikator::create([
            'type' => 'standar', 'kelompok_indikator' => 'Akademik', 'no_indikator' => 'TEST-02-25',
            'indikator' => '[TEST 2] Indikator Uji Tinjauan Efektivitas (2025)', 'target' => '100%',
            'periode_mulai' => 2025, 'periode_selesai' => 2025, 'created_by' => 1,
        ]);
        $iou2_2025 = IndikatorOrgUnit::create([
            'indikator_id' => $ind2_2025->indikator_id, 'org_unit_id' => $unit->orgunit_id, 'target' => '100%',
            'ed_capaian' => '60%', 'ed_skala' => 1,
            'ami_hasil_akhir' => 0, 'ami_hasil_temuan' => 'Temuan Minor',
            'ami_rtp_isi' => 'RTP 2025: Harus ada pelatihan sistem',
        ]);

        $ind2_2026 = Indikator::create([
            'type' => 'standar', 'kelompok_indikator' => 'Akademik', 'no_indikator' => 'TEST-02-26',
            'indikator' => '[TEST 2] Indikator Uji Tinjauan Efektivitas (2026)', 'target' => '100%',
            'periode_mulai' => 2026, 'periode_selesai' => 2026, 'created_by' => 1,
            'prev_indikator_id' => $ind2_2025->indikator_id,
        ]);
        IndikatorOrgUnit::create([
            'indikator_id' => $ind2_2026->indikator_id, 'org_unit_id' => $unit->orgunit_id, 'target' => '100%',
            'prev_indikorgunit_id' => $iou2_2025->indikorgunit_id,
            'ed_capaian' => '90%', 'ed_skala' => 3,
            'ed_ptp_isi' => 'PTP 2026: Pelatihan sudah dilakukan kepada 20 staf pengajar.',
        ]);

        $this->command->info('Seeder selesai!');
        $this->command->info('- Untuk TEST 1: Masuk ke Evaluasi Diri 2025, cari [TEST 1], isikan form Pelaksanaan Perbaikan.');
        $this->command->info('- Untuk TEST 2: Masuk ke Evaluasi Diri 2026, lengkapi ED. Lalu masuk ke AMI 2026, cari [TEST 2], isikan form Tinjauan Efektivitas.');
    }
}
