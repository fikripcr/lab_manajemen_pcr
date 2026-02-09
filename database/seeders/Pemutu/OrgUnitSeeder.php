<?php
namespace Database\Seeders;

use App\Models\Pemutu\OrgUnit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrgUnitSeeder extends Seeder
{
    public function run()
    {
        // Truncate first to refresh structure
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        OrgUnit::truncate();
        DB::table('indikator_orgunit')->truncate(); // Clear pivot strictly related to units
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Level 1: Institusi / Root
        $pcr = OrgUnit::create([
            'name'  => 'Politeknik Chevron Riau',
            'type'  => 'Institusi',
            'level' => 1,
            'seq'   => 1,
            'code'  => 'PCR',
        ]);

        // Level 2: Senat & SPM (Direct under Direktur/PCR)
        $this->createUnit($pcr, 'Senat', 'Senat', 1);
        $this->createUnit($pcr, 'Satuan Penjaminan Mutu', 'Unit', 2);

        // Level 2: Wadirs
        $wadir1 = $this->createUnit($pcr, 'Wakil Direktur Bidang Akademik dan Inovasi Pembelajaran', 'Direktorat', 3, 'WADIR 1');
        $wadir2 = $this->createUnit($pcr, 'Wakil Direktur Bidang Sumber Daya', 'Direktorat', 4, 'WADIR 2');
        $wadir3 = $this->createUnit($pcr, 'Wakil Direktur Bidang Keuangan, Perencanaan dan Kelembagaan', 'Direktorat', 5, 'WADIR 3');
        $wadir4 = $this->createUnit($pcr, 'Wakil Direktur Bidang Kemahasiswaan, Pemasaran, dan Kemitraan', 'Direktorat', 6, 'WADIR 4');

        // Level 3: Bagian under Wadir 1
        $this->createUnit($wadir1, 'Bagian Administrasi Akademik', 'Bagian', 1);
        $this->createUnit($wadir1, 'Bagian Penelitian dan Pengabdian Kepada Masyarakat', 'Bagian', 2);
        $this->createUnit($wadir1, 'Bagian Inovasi, Pengembangan Pembelajaran dan, Perpustakaan', 'Bagian', 3);

        // Level 3: Bagian under Wadir 2
        $this->createUnit($wadir2, 'Bagian Sumber Daya Manusia', 'Bagian', 1);
        $this->createUnit($wadir2, 'Bagian Manajemen Aset dan Sarana Prasarana', 'Bagian', 2);
        $this->createUnit($wadir2, 'Bagian Sistem dan Teknologi Informasi', 'Bagian', 3);

        // Level 3: Bagian under Wadir 3
        $this->createUnit($wadir3, 'Bagian Keuangan', 'Bagian', 1);
        $this->createUnit($wadir3, 'Bagian Perencanaan dan Pengembangan', 'Bagian', 2);
        $this->createUnit($wadir3, 'Bagian Kelembagaan', 'Bagian', 3);

        // Level 3: Bagian under Wadir 4
        $this->createUnit($wadir4, 'Bagian Pemasaran, Komunikasi dan PMB', 'Bagian', 1);
        $this->createUnit($wadir4, 'Bagian Kemitraan dan Urusan Internasional', 'Bagian', 2);
        $this->createUnit($wadir4, 'Bagian Kemahasiswaan, Pusat Karir dan Alumni', 'Bagian', 3);
        $this->createUnit($wadir4, 'Bagian Bisnis', 'Bagian', 4); // Based on chart

        // Level 2: Jurusans (Direct under PCR/Direktur in chart line, distinct from Wadirs)
        $seqJurusan = 10;

        // Jurusan TI
        $jti = $this->createUnit($pcr, 'Jurusan Teknologi Industri', 'Jurusan', $seqJurusan++, 'JTIN');
        $this->createUnit($jti, 'D4 Teknologi Rekayasa Mekatronika', 'Prodi', 1);
        $this->createUnit($jti, 'D4 Teknik Mesin', 'Prodi', 2);
        $this->createUnit($jti, 'D4 Teknologi Rekayasa Jaringan Telekomunikasi', 'Prodi', 3);
        $this->createUnit($jti, 'D4 Teknik Listrik', 'Prodi', 4);
        $this->createUnit($jti, 'D4 Teknologi Rekayasa Sistem Elektronika', 'Prodi', 5);
        $this->createUnit($jti, 'D4 Teknik Elektronika (Telekomunikasi)', 'Prodi', 6);
        $this->createUnit($jti, 'Laboratorium JTIN', 'Laboratorium', 99);

        // Jurusan Bisnis
        $jbk = $this->createUnit($pcr, 'Jurusan Bisnis dan Komunikasi', 'Jurusan', $seqJurusan++, 'JBK');
        $this->createUnit($jbk, 'D4 Akuntansi Perpajakan', 'Prodi', 1);
        $this->createUnit($jbk, 'D4 Bisnis Digital', 'Prodi', 2);
        $this->createUnit($jbk, 'D4 Hubungan Masyarakat dan Komunikasi Digital', 'Prodi', 3);
        $this->createUnit($jbk, 'Laboratorium JBK', 'Laboratorium', 99);

        // Jurusan TIK
        $jtik = $this->createUnit($pcr, 'Jurusan Teknologi Informasi', 'Jurusan', $seqJurusan++, 'JTI');
        $this->createUnit($jtik, 'D4 Teknik Informatika', 'Prodi', 1);
        $this->createUnit($jtik, 'D4 Sistem Informasi', 'Prodi', 2);
        $this->createUnit($jtik, 'D4 Teknologi Rekayasa Komputer', 'Prodi', 3);
        $this->createUnit($jtik, 'S2 Magister Terapan Teknik Komputer', 'Prodi', 4);
        $this->createUnit($jtik, 'Laboratorium JTI', 'Laboratorium', 99);
        $this->createUnit($jtik, 'Sekretaris Program Studi', 'Sekretariat', 98); // Chart piece

    }

    private function createUnit($parent, $name, $type, $seq, $code = null)
    {
        return OrgUnit::create([
            'parent_id' => $parent->orgunit_id,
            'name'      => $name,
            'type'      => $type,
            'level'     => $parent->level + 1,
            'seq'       => $seq,
            'code'      => $code ?? $this->generateCode($name),
        ]);
    }

    private function generateCode($name)
    {
        $words = explode(' ', $name);
        $code  = '';
        foreach ($words as $word) {
            if (ctype_alnum($word)) {
                $code .= strtoupper(substr($word, 0, 1));
            }
        }
        return substr($code, 0, 10);
    }
}
