<?php
namespace Database\Seeders\Pemutu;

use App\Models\Pemutu\DokSub;
use App\Models\Pemutu\Dokumen;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DokumenSeeder extends Seeder
{
    public function run()
    {
        // Disable Foreign Key Constraints to allow Truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DokSub::truncate();
        Dokumen::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $periode = date('Y');

        // Level 1: Visi (1 Document)
        $visi = Dokumen::create([
            'judul'   => 'Visi Politeknik Caltex Riau 2030',
            'periode' => $periode,
            'jenis'   => 'Visi',
            'level'   => 1,
            'seq'     => 1,
            'kode'    => 'VISI-001',
        ]);

        // Add Content (DokSub) for Visi
        DokSub::create([
            'dok_id' => $visi->dok_id,
            'judul'  => 'Visi Utama',
            'isi'    => '<p>Diakui sebagai Politeknik Unggul yang mampu bersaing dalam bidang teknologi dan bisnis pada tingkat nasional maupun ASEAN pada tahun 2030.</p>',
            'seq'    => 1,
        ]);

        // Level 2: Misi (3 Documents as Children of Visi)
        $misiTitles = [
            'Misi Pendidikan',
            'Misi Penelitian',
            'Misi Pengabdian Masyarakat',
        ];

        foreach ($misiTitles as $idx => $mTitle) {
            $misi = Dokumen::create([
                'parent_id' => $visi->dok_id,
                'judul'     => $mTitle,
                'periode'   => $periode,
                'jenis'     => 'Misi',
                'level'     => 2,
                'seq'       => $idx + 1,
                'kode'      => 'MISI-00' . ($idx + 1),
            ]);

            DokSub::create([
                'dok_id' => $misi->dok_id,
                'judul'  => 'Poin Misi ' . ($idx + 1),
                'isi'    => '<p>Menyelenggarakan kegiatan ' . strtolower(str_replace('Misi ', '', $mTitle)) . ' yang berkualitas dan relevan dengan kebutuhan industri.</p>',
                'seq'    => 1,
            ]);

            // Level 3: RPJP (3 Children per Misi)
            // Example: RPJP Tahap 1, 2, 3
            for ($i = 1; $i <= 3; $i++) {
                $rpjp = Dokumen::create([
                    'parent_id' => $misi->dok_id,
                    'judul'     => "RPJP {$misi->judul} Tahap $i (2025-2030)",
                    'periode' => $periode,
                    'jenis'   => 'RJP',
                    'level'   => 3,
                    'seq'     => $i,
                    'kode'    => "RPJP-{$misi->dok_id}-$i",
                ]);

                DokSub::create([
                    'dok_id' => $rpjp->dok_id,
                    'judul'  => "Fokus RPJP Tahap $i",
                    'isi'    => "<p>Fokus pada penguatan fondasi dan akselerasi pertumbuhan pada tahap $i.</p>",
                    'seq'    => 1,
                ]);

                // Level 4: Renstra (3 Children per RPJP)
                // Example: Renstra Tahun 1, 2, 3 (Conceptually)
                for ($j = 1; $j <= 3; $j++) {
                    $renstra = Dokumen::create([
                        'parent_id' => $rpjp->dok_id,
                        'judul'     => "Renstra Bidang " . ["Akademik", "Keuangan", "Kemahasiswaan"][$j - 1] . " ($periode)",
                        'periode'   => $periode,
                        'jenis'     => 'Renstra',
                        'level'     => 4,
                        'seq'       => $j,
                        'kode'      => "RSTR-{$rpjp->dok_id}-$j",
                    ]);

                    DokSub::create([
                        'dok_id' => $renstra->dok_id,
                        'judul'  => "Sasaran Strategis $j",
                        'isi'    => "<p>Meningkatkan indikator kinerja utama sebesar 20% dalam periode ini.</p>",
                        'seq'    => 1,
                    ]);

                    // Level 5: Renop (3 Children per Renstra)
                    for ($k = 1; $k <= 3; $k++) {
                        $renop = Dokumen::create([
                            'parent_id' => $renstra->dok_id,
                            'judul'     => "Renop Program " . ["Unggulan", "Reguler", "Khusus"][$k - 1] . " $k",
                            'periode'   => $periode,
                            'jenis'     => 'Renop',
                            'level'     => 5,
                            'seq'       => $k,
                            'kode'      => "ROP-{$renstra->dok_id}-$k",
                        ]);

                        // Add 3 DokSubs (Points) to Renop as these are the ones selected in Indikator
                        for ($l = 1; $l <= 3; $l++) {
                            DokSub::create([
                                'dok_id' => $renop->dok_id,
                                'judul'  => "Kegiatan Operasional $l",
                                'isi'    => "<p>Melaksanakan kegiatan teknis pendukung program kerja dengan output terukur.</p>",
                                'seq'    => $l,
                            ]);
                        }
                    }
                }
            }
        }
    }
}
