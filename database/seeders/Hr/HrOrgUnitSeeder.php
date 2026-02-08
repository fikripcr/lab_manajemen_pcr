<?php
namespace Database\Seeders\Hr;

use App\Models\Hr\Departemen;
use App\Models\Hr\JabatanStruktural;
use App\Models\Hr\OrgUnit;
use App\Models\Hr\Posisi;
use App\Models\Hr\Prodi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HrOrgUnitSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            // 1. Migrate Departemen
            $deptMap     = [];
            $departemens = Departemen::all();
            foreach ($departemens as $dept) {
                $org = OrgUnit::create([
                    'name'      => $dept->departemen,
                    'code'      => $dept->abbr,
                    'type'      => 'departemen',
                    'level'     => 1,
                    'is_active' => $dept->is_active ?? true,
                ]);
                $deptMap[$dept->departemen_id] = $org->org_unit_id;
            }

            // 2. Migrate Prodi (Children of Departemen)
            $prodis = Prodi::all();
            foreach ($prodis as $prodi) {
                $parentId = $prodi->departemen_id ? ($deptMap[$prodi->departemen_id] ?? null) : null;
                OrgUnit::create([
                    'parent_id' => $parentId,
                    'name'      => $prodi->nama_prodi,
                    'code'      => $prodi->alias,
                    'type'      => 'prodi',
                    'level'     => $parentId ? 2 : 1,
                    'is_active' => true,
                ]);
            }

            // 3. Migrate Jabatan Struktural (flat, no hierarchy in legacy)
            $jabatans = JabatanStruktural::all();
            foreach ($jabatans as $jab) {
                OrgUnit::create([
                    'name'      => $jab->jabstruktural,
                    'type'      => 'jabatan_struktural',
                    'level'     => 1,
                    'is_active' => $jab->is_active ?? true,
                ]);
            }

            // 4. Migrate Posisi
            $posisis = Posisi::all();
            foreach ($posisis as $pos) {
                OrgUnit::create([
                    'name'      => $pos->posisi,
                    'code'      => $pos->alias,
                    'type'      => 'posisi',
                    'level'     => 1,
                    'is_active' => $pos->is_active ?? true,
                ]);
            }
        });
    }
}
