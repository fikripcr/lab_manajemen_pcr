<?php

namespace Database\Seeders\Hr;

use App\Models\Hr\Pegawai;
use App\Models\Hr\RiwayatDataDiri;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SyncPegawaiCommonColumnsSeeder extends Seeder
{
    /**
     * Run the seeder to populate common columns in hr_pegawai
     * from existing latestDataDiri records.
     */
    public function run(): void
    {
        $this->command->info('🔄 Syncing common columns from RiwayatDataDiri to hr_pegawai...');

        $pegawaiList = Pegawai::whereNotNull('latest_riwayatdatadiri_id')->get();

        if ($pegawaiList->isEmpty()) {
            $this->command->info('✅ No pegawai records to sync. Exiting.');
            return;
        }

        $total = $pegawaiList->count();
        $synced = 0;
        $skipped = 0;

        DB::transaction(function () use ($pegawaiList, &$synced, &$skipped, $total) {
            foreach ($pegawaiList as $index => $pegawai) {
                $riwayat = RiwayatDataDiri::find($pegawai->latest_riwayatdatadiri_id);

                if (!$riwayat) {
                    $skipped++;
                    $this->command->warn("⚠️  Pegawai ID {$pegawai->pegawai_id}: RiwayatDataDiri not found");
                    continue;
                }

                $pegawai->update([
                    'nama' => $riwayat->nama,
                    'nip' => $riwayat->nip,
                    'email' => $riwayat->email,
                    'inisial' => $riwayat->inisial,
                    'no_hp' => $riwayat->no_hp,
                    'jenis_kelamin' => $riwayat->jenis_kelamin,
                    'tempat_lahir' => $riwayat->tempat_lahir,
                    'tgl_lahir' => $riwayat->tgl_lahir,
                    'orgunit_posisi_id' => $riwayat->orgunit_posisi_id,
                    'orgunit_departemen_id' => $riwayat->orgunit_departemen_id,
                    'nidn' => $riwayat->nidn,
                    'no_ktp' => $riwayat->no_ktp,
                    'alamat' => $riwayat->alamat,
                    'status_nikah' => $riwayat->status_nikah,
                    'agama' => $riwayat->agama,
                    'gelar_depan' => $riwayat->gelar_depan,
                    'gelar_belakang' => $riwayat->gelar_belakang,
                    'bidang_ilmu' => $riwayat->bidang_ilmu,
                ]);

                $synced++;
                
                if ($synced % 50 === 0 || $synced === $total) {
                    $this->command->info("   Progress: {$synced}/{$total} synced");
                }
            }
        });

        $this->command->info("✅ Sync complete!");
        $this->command->info("   ✓ Synced: {$synced}");
        $this->command->info("   ✗ Skipped: {$skipped}");
    }
}
