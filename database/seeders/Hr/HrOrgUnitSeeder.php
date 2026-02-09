<?php
namespace Database\Seeders\Hr;

use App\Models\Hr\OrgUnit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HrOrgUnitSeeder extends Seeder
{
    public function run(): void
    {
        // Truncate first to refresh structure
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        OrgUnit::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::transaction(function () {
            // Level 1: Institusi / Root
            $pcr = OrgUnit::create([
                'name'       => 'Politeknik Chevron Riau',
                'type'       => 'Institusi',
                'level'      => 1,
                'sort_order' => 1,
                'code'       => 'PCR',
                'is_active'  => true,
            ]);

            // Level 2: Senat & SPM
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
            $this->createUnit($wadir1, 'Bagian Inovasi, Pengembangan Pembelajaran dan Perpustakaan', 'Bagian', 3);

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
            $this->createUnit($wadir4, 'Bagian Bisnis', 'Bagian', 4);

            // Level 2: Jurusans
            $seqJurusan = 10;
            $jti        = $this->createUnit($pcr, 'Jurusan Teknologi Industri', 'Jurusan', $seqJurusan++, 'JTIN');
            $this->createUnit($jti, 'D4 Teknologi Rekayasa Mekatronika', 'Prodi', 1);
            $this->createUnit($jti, 'D4 Teknik Mesin', 'Prodi', 2);
            $this->createUnit($jti, 'D4 Teknologi Rekayasa Jaringan Telekomunikasi', 'Prodi', 3);
            $this->createUnit($jti, 'D4 Teknik Listrik', 'Prodi', 4);
            $this->createUnit($jti, 'D4 Teknologi Rekayasa Sistem Elektronika', 'Prodi', 5);
            $this->createUnit($jti, 'D4 Teknik Elektronika (Telekomunikasi)', 'Prodi', 6);

            $jbk = $this->createUnit($pcr, 'Bisnis dan Komunikasi', 'Jurusan', $seqJurusan++, 'JBK');
            $this->createUnit($jbk, 'D4 Akuntansi Perpajakan', 'Prodi', 1);
            $this->createUnit($jbk, 'D4 Bisnis Digital', 'Prodi', 2);
            $this->createUnit($jbk, 'D4 Hubungan Masyarakat dan Komunikasi Digital', 'Prodi', 3);

            $jtik = $this->createUnit($pcr, 'Jurusan Teknologi Informasi', 'Jurusan', $seqJurusan++, 'JTI');
            $this->createUnit($jtik, 'D4 Teknik Informatika', 'Prodi', 1);
            $this->createUnit($jtik, 'D4 Sistem Informasi', 'Prodi', 2);
            $this->createUnit($jtik, 'D4 Teknologi Rekayasa Komputer', 'Prodi', 3);
            $this->createUnit($jtik, 'S2 Magister Terapan Teknik Komputer', 'Prodi', 4);

            // Level 2: Generic Positions (Direct under PCR or units)
            $posisiDir = $this->createUnit($pcr, 'Daftar Posisi Pegawai', 'posisi_header', 100, 'POS');
            $this->createUnit($posisiDir, 'Dosen', 'posisi', 1);
            $this->createUnit($posisiDir, 'Staff Administrasi', 'posisi', 2);
            $this->createUnit($posisiDir, 'Teknisi', 'posisi', 3);
            $this->createUnit($posisiDir, 'Kepala Laboratorium', 'posisi', 4);
            $this->createUnit($posisiDir, 'Asisten Laboratorium', 'posisi', 5);
            $this->createUnit($posisiDir, 'Welfare Officer', 'posisi', 6);

            // Level 2: Structural Positions (Example structure)
            $this->createUnit($pcr, 'Direktur', 'jabatan_struktural', 1);
            $this->createUnit($wadir1, 'Wakil Direktur', 'jabatan_struktural', 1);
            $this->createUnit($wadir2, 'Wakil Direktur', 'jabatan_struktural', 1);
            $this->createUnit($wadir3, 'Wakil Direktur', 'jabatan_struktural', 1);
            $this->createUnit($wadir4, 'Wakil Direktur', 'jabatan_struktural', 1);

            // Jurusan structural
            $this->createUnit($jti, 'Ketua Jurusan', 'jabatan_struktural', 1);
            $this->createUnit($jbk, 'Ketua Jurusan', 'jabatan_struktural', 1);
            $this->createUnit($jtik, 'Ketua Jurusan', 'jabatan_struktural', 1);
        });
    }

    private function createUnit($parent, $name, $type, $sort_order, $code = null)
    {
        return OrgUnit::create([
            'parent_id'  => $parent->org_unit_id,
            'name'       => $name,
            'type'       => $type,
            'level'      => $parent->level + 1,
            'sort_order' => $sort_order,
            'code'       => $code ?? $this->generateCode($name),
            'is_active'  => true,
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
