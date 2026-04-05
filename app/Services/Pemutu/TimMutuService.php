<?php

namespace App\Services\Pemutu;

use App\Models\Hr\Pegawai;
use App\Models\Hr\StrukturOrganisasi;
use App\Models\Pemutu\PeriodeSpmi;
use App\Models\Pemutu\TimMutu;
use App\Models\User;
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
            'total_units' => $assignments->pluck('org_unit_id')->unique()->count(),
            'total_auditee' => $assignments->where('role', 'auditee')->count(),
            'total_anggota' => $assignments->where('role', 'anggota')->count(),
            'total_auditor' => $assignments->where('role', 'auditor')->count(),
            'total_ketua_auditor' => $assignments->where('role', 'ketua_auditor')->count(),
            'total_pegawai' => $assignments->pluck('pegawai_id')->unique()->count(),
        ];
    }

    /**
     * Update Tim Mutu for a single OrgUnit.
     */
    public function updateUnitTimMutu($periodeId, $unitId, $auditeeId, $ketuaAuditorId, array $auditorIds, array $anggotaIds)
    {
        return DB::transaction(function () use ($periodeId, $unitId, $auditeeId, $ketuaAuditorId, $auditorIds, $anggotaIds) {
            $periode = PeriodeSpmi::findOrFail($periodeId);
            $unit = StrukturOrganisasi::findOrFail($unitId);

            TimMutu::forPeriode($periodeId)
                ->forUnit($unitId)
                ->forceDelete();

            $inserts = [];

            if ($auditeeId) {
                $inserts[] = $this->buildInsertPayload($periodeId, $unitId, $auditeeId, 'auditee');
            }
            if ($ketuaAuditorId) {
                $inserts[] = $this->buildInsertPayload($periodeId, $unitId, $ketuaAuditorId, 'ketua_auditor');
            }
            $inserts = array_merge($inserts, $this->buildBulkInserts($periodeId, $unitId, $auditorIds, 'auditor'));
            $inserts = array_merge($inserts, $this->buildBulkInserts($periodeId, $unitId, $anggotaIds, 'anggota'));

            if (! empty($inserts)) {
                TimMutu::insert($inserts);
            }

            logActivity(
                'Tim Mutu Updated',
                "Mengupdate Tim Mutu untuk unit {$unit->name} pada periode {$periode->periode}",
                $unit
            );

            return true;
        });
    }

    /**
     * Update Tim Auditee for a single OrgUnit.
     */
    public function updateAuditee($periodeId, $unitId, $auditeeId, array $anggotaIds)
    {
        return DB::transaction(function () use ($periodeId, $unitId, $auditeeId, $anggotaIds) {
            $periode = PeriodeSpmi::findOrFail($periodeId);
            $unit = StrukturOrganisasi::findOrFail($unitId);

            TimMutu::forPeriode($periodeId)
                ->forUnit($unitId)
                ->whereIn('role', ['auditee', 'anggota'])
                ->forceDelete();

            $inserts = [];

            if ($auditeeId) {
                $inserts[] = $this->buildInsertPayload($periodeId, $unitId, $auditeeId, 'auditee');
                $this->syncRoleForPegawai($auditeeId, 'Auditee');
            }
            foreach ($anggotaIds as $anggotaId) {
                $inserts[] = $this->buildInsertPayload($periodeId, $unitId, $anggotaId, 'anggota');
                $this->syncRoleForPegawai($anggotaId, 'Auditee');
            }

            if (! empty($inserts)) {
                TimMutu::insert($inserts);
            }

            logActivity('Tim Mutu Updated', "Mengupdate Tim Auditee untuk unit {$unit->name} pada periode {$periode->periode}", $unit);

            return true;
        });
    }

    /**
     * Update Tim Auditor for a single OrgUnit.
     */
    public function updateAuditor($periodeId, $unitId, $ketuaAuditorId, array $auditorIds)
    {
        return DB::transaction(function () use ($periodeId, $unitId, $ketuaAuditorId, $auditorIds) {
            $periode = PeriodeSpmi::findOrFail($periodeId);
            $unit = StrukturOrganisasi::findOrFail($unitId);

            TimMutu::forPeriode($periodeId)
                ->forUnit($unitId)
                ->whereIn('role', ['ketua_auditor', 'auditor'])
                ->forceDelete();

            $inserts = [];

            if ($ketuaAuditorId) {
                $inserts[] = $this->buildInsertPayload($periodeId, $unitId, $ketuaAuditorId, 'ketua_auditor');
                $this->syncRoleForPegawai($ketuaAuditorId, 'Auditor Internal');
            }
            foreach ($auditorIds as $auditorId) {
                $inserts[] = $this->buildInsertPayload($periodeId, $unitId, $auditorId, 'auditor');
                $this->syncRoleForPegawai($auditorId, 'Auditor Internal');
            }

            if (! empty($inserts)) {
                TimMutu::insert($inserts);
            }

            logActivity('Tim Mutu Updated', "Mengupdate Tim Auditor untuk unit {$unit->name} pada periode {$periode->periode}", $unit);

            return true;
        });
    }

    /**
     * Search Pegawai for Select2.
     */
    public function searchPegawai($search)
    {
        return Pegawai::query()
            ->where('nama', 'like', "%{$search}%")
            ->orWhere('nip', 'like', "%{$search}%")
            ->limit(20)
            ->get()
            ->map(function ($p) {
                return [
                    'id' => encryptId($p->pegawai_id),
                    'text' => $p->nama.' ('.($p->nip ?? 'No NIP').')',
                ];
            })
            ->values();
    }

    /**
     * Build a single insert payload for Tim Mutu assignment.
     */
    private function buildInsertPayload($periodeId, $unitId, $pegawaiId, string $role): array
    {
        return [
            'periodespmi_id' => $periodeId,
            'org_unit_id' => $unitId,
            'pegawai_id' => $pegawaiId,
            'role' => $role,
            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ];
    }

    /**
     * Build bulk insert payloads for an array of pegawai IDs with the same role.
     */
    private function buildBulkInserts($periodeId, $unitId, array $pegawaiIds, string $role): array
    {
        $inserts = [];
        foreach ($pegawaiIds as $pegawaiId) {
            if ($pegawaiId) {
                $inserts[] = $this->buildInsertPayload($periodeId, $unitId, $pegawaiId, $role);
            }
        }

        return $inserts;
    }

    /**
     * Assign a Spatie role to a pegawai's user account.
     * Uses assignRole() which ADDS the role without removing existing roles.
     * This way, if a user is removed from Tim Mutu, they keep their role
     * in case they get reassigned in a future period.
     */
    private function syncRoleForPegawai(int $pegawaiId, string $roleName): void
    {
        $pegawai = Pegawai::find($pegawaiId);
        if (! $pegawai || ! $pegawai->user_id) {
            return;
        }

        $user = User::find($pegawai->user_id);
        if (! $user) {
            return;
        }

        // assignRole() only adds the role if not already assigned — does NOT remove existing roles
        if (! $user->hasRole($roleName)) {
            $user->assignRole($roleName);
        }
    }
}
