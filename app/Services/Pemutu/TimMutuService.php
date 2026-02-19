<?php
namespace App\Services\Pemutu;

use App\Models\Pemutu\OrgUnit;
use App\Models\Pemutu\TimMutu;
use App\Models\Shared\Pegawai;
use Illuminate\Support\Facades\DB;

class TimMutuService
{
    /**
     * Get all assignments for a given periode, grouped by org_unit_id.
     */
    public function getByPeriode($periodeId)
    {
        return TimMutu::with(['orgUnit', 'pegawai.latestDataDiri'])
            ->forPeriode($periodeId)
            ->get()
            ->groupBy('org_unit_id');
    }

    /**
     * Get summary stats for a periode.
     */
    public function getSummary($periodeId)
    {
        $assignments = TimMutu::forPeriode($periodeId)->get();

        return [
            'total_units'         => $assignments->pluck('org_unit_id')->unique()->count(),
            'total_auditee'       => $assignments->where('role', 'auditee')->count(),
            'total_anggota'       => $assignments->where('role', 'anggota')->count(),
            'total_auditor'       => $assignments->where('role', 'auditor')->count(),
            'total_ketua_auditor' => $assignments->where('role', 'ketua_auditor')->count(),
            'total_pegawai'       => $assignments->pluck('pegawai_id')->unique()->count(),
        ];
    }

    /**
     * Sync all assignments for a periode.
     * $data is an array keyed by org_unit_id:
     * [
     *   orgunit_id => [
     *     'auditee'       => pegawai_id (single),
     *     'ketua_auditor' => pegawai_id (single),
     *     'auditor'       => [pegawai_id, ...],
     *     'anggota'       => [pegawai_id, ...],
     *   ],
     * ]
     */
    public function syncAllAssignments($periodeId, array $data)
    {
        return DB::transaction(function () use ($periodeId, $data) {
            // Delete all existing assignments for this periode
            TimMutu::forPeriode($periodeId)->forceDelete();

            $inserts = [];
            foreach ($data as $unitId => $roles) {
                // Helper to add insert
                $addInsert = function ($pegawaiId, $role) use ($periodeId, $unitId) {
                    return [
                        'periodespmi_id' => $periodeId,
                        'org_unit_id'    => $unitId,
                        'pegawai_id'     => $pegawaiId,
                        'role'           => $role,
                        'created_at'     => now(),
                        'updated_at'     => now(),
                        'created_by'     => auth()->id(),
                        'updated_by'     => auth()->id(),
                    ];
                };

                // Single Roles
                if (! empty($roles['auditee'])) {
                    $inserts[] = $addInsert($roles['auditee'], 'auditee');
                }
                if (! empty($roles['ketua_auditor'])) {
                    $inserts[] = $addInsert($roles['ketua_auditor'], 'ketua_auditor');
                }

                // Multiple Roles
                foreach (['anggota', 'auditor'] as $roleKey) {
                    if (! empty($roles[$roleKey]) && is_array($roles[$roleKey])) {
                        foreach ($roles[$roleKey] as $pegawaiId) {
                            if ($pegawaiId) {
                                $inserts[] = $addInsert($pegawaiId, $roleKey);
                            }
                        }
                    }
                }
            }

            if (! empty($inserts)) {
                TimMutu::insert($inserts);
            }

            return count($inserts);
        });
    }

    /**
     * Update Tim Mutu for a single OrgUnit.
     */
    public function updateUnitTimMutu($periodeId, $unitId, $auditeeId, $ketuaAuditorId, array $auditorIds, array $anggotaIds)
    {
        return DB::transaction(function () use ($periodeId, $unitId, $auditeeId, $ketuaAuditorId, $auditorIds, $anggotaIds) {
            $periode = \App\Models\Pemutu\PeriodeSpmi::findOrFail($periodeId);
            $unit    = OrgUnit::findOrFail($unitId);

            // Delete existing
            TimMutu::forPeriode($periodeId)
                ->forUnit($unitId)
                ->forceDelete();

            $inserts = [];
            $now     = now();
            $userId  = auth()->id();

            // Helper
            $addInsert = function ($pegawaiId, $role) use ($periodeId, $unitId, $now, $userId) {
                return [
                    'periodespmi_id' => $periodeId,
                    'org_unit_id'    => $unitId,
                    'pegawai_id'     => $pegawaiId,
                    'role'           => $role,
                    'created_at'     => $now,
                    'updated_at'     => $now,
                    'created_by'     => $userId,
                    'updated_by'     => $userId,
                ];
            };

            if ($auditeeId) {
                $inserts[] = $addInsert($auditeeId, 'auditee');
            }
            if ($ketuaAuditorId) {
                $inserts[] = $addInsert($ketuaAuditorId, 'ketua_auditor');
            }

            foreach ($auditorIds as $pegawaiId) {
                if ($pegawaiId) {
                    $inserts[] = $addInsert($pegawaiId, 'auditor');
                }
            }
            foreach ($anggotaIds as $pegawaiId) {
                if ($pegawaiId) {
                    $inserts[] = $addInsert($pegawaiId, 'anggota');
                }
            }

            if (! empty($inserts)) {
                TimMutu::insert($inserts);
            }

            // Log Activity
            logActivity(
                'Tim Mutu Updated',
                "Mengupdate Tim Mutu untuk unit {$unit->name} pada periode {$periode->periode}",
                $unit
            );

            return true;
        });
    }

    /**
     * Search Pegawai for Select2.
     */
    public function searchPegawai($search)
    {
        return Pegawai::with('latestDataDiri')
            ->whereHas('latestDataDiri', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%");
            })
            ->limit(20)
            ->get()
            ->map(function ($p) {
                return [
                    'id'   => $p->pegawai_id,
                    'text' => $p->nama . ' (' . ($p->nip ?? 'No NIP') . ')',
                ];
            })
            ->values();
    }

    /**
     * Get all active pegawai for Select2 options (Legacy/Full list).
     */
    public function getAvailablePegawai()
    {
        return $this->searchPegawai('');
    }

    /**
     * Get OrgUnits as flat list for manage page.
     */
    public function getOrgUnitsFlat()
    {
        return OrgUnit::with('parent')
            ->orderBy('level')
            ->orderBy('seq')
            ->get();
    }
}
