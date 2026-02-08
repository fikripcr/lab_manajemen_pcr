<?php
namespace Database\Seeders\Hr;

use App\Models\Hr\OrgUnit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HrOrgStructureSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data
        OrgUnit::truncate();

        DB::transaction(function () {
            // Level 1: Yayasan
            $yayasan = OrgUnit::create([
                'name'      => 'Yayasan Politeknik Chevron Riau',
                'type'      => 'unit',
                'code'      => 'YPCR',
                'level'     => 1,
                'is_active' => true,
            ]);

            // Level 2: Under Yayasan
            $senat = OrgUnit::create([
                'name'      => 'Senat',
                'type'      => 'unit',
                'code'      => 'SENAT',
                'parent_id' => $yayasan->org_unit_id,
                'level'     => 2,
                'is_active' => true,
            ]);

            $direktur = OrgUnit::create([
                'name'      => 'Direktur',
                'type'      => 'jabatan_struktural',
                'code'      => 'DIR',
                'parent_id' => $yayasan->org_unit_id,
                'level'     => 2,
                'is_active' => true,
            ]);

            $spm = OrgUnit::create([
                'name'      => 'Kepala Satuan Penjaminan Mutu',
                'type'      => 'jabatan_struktural',
                'code'      => 'SPM',
                'parent_id' => $yayasan->org_unit_id,
                'level'     => 2,
                'is_active' => true,
            ]);

            // Level 3: Wakil Direktur (Under Direktur)
            $wadir1 = OrgUnit::create([
                'name'      => 'Wakil Direktur Bidang Akademik dan Inovasi Pembelajaran',
                'type'      => 'jabatan_struktural',
                'code'      => 'WADIR1',
                'parent_id' => $direktur->org_unit_id,
                'level'     => 3,
                'is_active' => true,
            ]);

            $wadir2 = OrgUnit::create([
                'name'      => 'Wakil Direktur Bidang Sumber Daya',
                'type'      => 'jabatan_struktural',
                'code'      => 'WADIR2',
                'parent_id' => $direktur->org_unit_id,
                'level'     => 3,
                'is_active' => true,
            ]);

            $wadir3 = OrgUnit::create([
                'name'      => 'Wakil Direktur Bidang Keuangan, Perencanaan dan Kelembagaan',
                'type'      => 'jabatan_struktural',
                'code'      => 'WADIR3',
                'parent_id' => $direktur->org_unit_id,
                'level'     => 3,
                'is_active' => true,
            ]);

            $wadir4 = OrgUnit::create([
                'name'      => 'Wakil Direktur Bidang Kemahasiswaan, Pemasaran, dan Kemitraan',
                'type'      => 'jabatan_struktural',
                'code'      => 'WADIR4',
                'parent_id' => $direktur->org_unit_id,
                'level'     => 3,
                'is_active' => true,
            ]);

            // Level 4: Kepala Bagian Under Wadir 1
            $this->createUnit('Kepala Bagian Administrasi Akademik', 'jabatan_struktural', 'KAA', $wadir1->org_unit_id, 4);
            $this->createUnit('Kepala Bagian Inovasi, Pengembangan Pembelajaran dan Perpustakaan', 'jabatan_struktural', 'KIPP', $wadir1->org_unit_id, 4);
            $this->createUnit('Kepala Bagian Penelitian dan Pengabdian Kepada Masyarakat', 'jabatan_struktural', 'KPPM', $wadir1->org_unit_id, 4);

            // Level 4: Kepala Bagian Under Wadir 2
            $this->createUnit('Kepala Bagian Sumber Daya Manusia', 'jabatan_struktural', 'KSDM', $wadir2->org_unit_id, 4);
            $this->createUnit('Kepala Bagian Manajemen Aset dan Sarana Prasarana', 'jabatan_struktural', 'KMAP', $wadir2->org_unit_id, 4);

            // Level 4: Kepala Bagian Under Wadir 3
            $this->createUnit('Kepala Bagian Sistem dan Teknologi Informasi', 'jabatan_struktural', 'KSTI', $wadir3->org_unit_id, 4);
            $this->createUnit('Kepala Bagian Keuangan', 'jabatan_struktural', 'KKEU', $wadir3->org_unit_id, 4);
            $this->createUnit('Kepala Bagian Kelembagaan', 'jabatan_struktural', 'KKEL', $wadir3->org_unit_id, 4);
            $this->createUnit('Kepala Bagian Perencanaan dan Pengembangan', 'jabatan_struktural', 'KPP', $wadir3->org_unit_id, 4);

            // Level 4: Kepala Bagian Under Wadir 4
            $this->createUnit('Kepala Bagian Pemasaran, Komunikasi dan PMB', 'jabatan_struktural', 'KPMB', $wadir4->org_unit_id, 4);
            $this->createUnit('Kepala Bagian Kemitraan dan Urusan Internasional', 'jabatan_struktural', 'KKUI', $wadir4->org_unit_id, 4);
            $this->createUnit('Kepala Bagian Kemahasiswaan, Pusat Karir dan Alumni', 'jabatan_struktural', 'KKPKA', $wadir4->org_unit_id, 4);
            $this->createUnit('Kepala Bagian Bisnis', 'jabatan_struktural', 'KBIS', $wadir4->org_unit_id, 4);

            // Level 3: Jurusan (Under Direktur)
            $jti = OrgUnit::create([
                'name'      => 'Ketua Jurusan Teknologi Industri',
                'type'      => 'departemen',
                'code'      => 'JTI',
                'parent_id' => $direktur->org_unit_id,
                'level'     => 3,
                'is_active' => true,
            ]);

            $jbk = OrgUnit::create([
                'name'      => 'Ketua Jurusan Bisnis dan Komunikasi',
                'type'      => 'departemen',
                'code'      => 'JBK',
                'parent_id' => $direktur->org_unit_id,
                'level'     => 3,
                'is_active' => true,
            ]);

            $jtif = OrgUnit::create([
                'name'      => 'Ketua Jurusan Teknologi Informasi',
                'type'      => 'departemen',
                'code'      => 'JTIF',
                'parent_id' => $direktur->org_unit_id,
                'level'     => 3,
                'is_active' => true,
            ]);

            // Level 4: Prodi Under JTI
            $this->createUnit('Ketua Prodi D4 Teknologi Rekayasa Mekatronika', 'prodi', 'TRM', $jti->org_unit_id, 4);
            $this->createUnit('Ketua Prodi D4 Teknologi Rekayasa Jaringan Telekomunikasi', 'prodi', 'TRJT', $jti->org_unit_id, 4);
            $this->createUnit('Ketua Prodi D4 Teknologi Rekayasa Sistem Elektronika', 'prodi', 'TRSE', $jti->org_unit_id, 4);
            $this->createUnit('Ketua Prodi D4 Teknik Mesin', 'prodi', 'TM', $jti->org_unit_id, 4);
            $this->createUnit('Ketua Prodi D4 Teknik Listrik', 'prodi', 'TL', $jti->org_unit_id, 4);
            $this->createUnit('Ketua Prodi D4 Teknik Elektronika (Telekomunikasi)', 'prodi', 'TE', $jti->org_unit_id, 4);
            $this->createUnit('Kepala Laboratorium JTI', 'jabatan_struktural', 'LABJTI', $jti->org_unit_id, 4);

            // Level 4: Prodi Under JBK
            $this->createUnit('Ketua Prodi D4 Akuntansi Perpajakan', 'prodi', 'AP', $jbk->org_unit_id, 4);
            $this->createUnit('Ketua Prodi D4 Hubungan Masyarakat dan Komunikasi Digital', 'prodi', 'HMKD', $jbk->org_unit_id, 4);
            $this->createUnit('Ketua Prodi Bisnis Digital', 'prodi', 'BD', $jbk->org_unit_id, 4);
            $this->createUnit('Kepala Laboratorium JBK', 'jabatan_struktural', 'LABJBK', $jbk->org_unit_id, 4);
            $this->createUnit('Sekretaris Program Studi JBK', 'jabatan_struktural', 'SEKJBK', $jbk->org_unit_id, 4);

            // Level 4: Prodi Under JTIF
            $this->createUnit('Ketua Prodi D4 Teknik Informatika', 'prodi', 'TI', $jtif->org_unit_id, 4);
            $this->createUnit('Ketua Prodi D4 Teknologi Rekayasa Komputer', 'prodi', 'TRK', $jtif->org_unit_id, 4);
            $this->createUnit('Ketua Prodi D4 Sistem Informasi', 'prodi', 'SI', $jtif->org_unit_id, 4);
            $this->createUnit('Ketua Prodi S2 Teknik Komputer', 'prodi', 'MTK', $jtif->org_unit_id, 4);
            $this->createUnit('Kepala Laboratorium JTIF', 'jabatan_struktural', 'LABJTIF', $jtif->org_unit_id, 4);

            // Posisi (Role-based, independent)
            $this->createUnit('Dosen Tetap', 'posisi', 'DSN', null, 1);
            $this->createUnit('Staff Administrasi', 'posisi', 'ADM', null, 1);
            $this->createUnit('Teknisi', 'posisi', 'TEK', null, 1);
            $this->createUnit('Security', 'posisi', 'SEC', null, 1);
            $this->createUnit('Cleaning Service', 'posisi', 'CS', null, 1);
        });
    }

    private function createUnit(string $name, string $type, string $code, ?int $parentId, int $level): OrgUnit
    {
        return OrgUnit::create([
            'name'      => $name,
            'type'      => $type,
            'code'      => $code,
            'parent_id' => $parentId,
            'level'     => $level,
            'is_active' => true,
        ]);
    }
}
