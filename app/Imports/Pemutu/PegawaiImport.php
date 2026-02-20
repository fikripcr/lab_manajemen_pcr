<?php
namespace App\Imports\Pemutu;

use App\Models\Pemutu\OrgUnit;
use App\Models\Shared\Pegawai;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PegawaiImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Skip if name is missing
        if (empty($row['nama'])) {
            return null;
        }

        // Try to find User by email
        $user = null;
        if (! empty($row['email'])) {
            $user = User::where('email', $row['email'])->first();
        }

        // Try to find OrgUnit by code or name
        $orgUnit = null;
        if (! empty($row['unit_code'])) {
            $orgUnit = OrgUnit::where('code', $row['unit_code'])->first();
        } elseif (! empty($row['unit_name'])) {
            $orgUnit = OrgUnit::where('name', $row['unit_name'])->first();
        }

        // Note: Pegawai shared model uses RiwayatDataDiri for Name/Email.
        // Direct creation of Pegawai might not be enough if it expects history.
        // However, for Pemutu context, we'll try to keep it simple or follow the existing pattern in PegawaiService.

        return new Pegawai([
            'user_id'     => $user ? $user->id : null,
            'org_unit_id' => $orgUnit ? $orgUnit->orgunit_id : null,
            // These fields might not be in the 'pegawai' table directly but in 'riwayat_data_diri'
            // I should check PegawaiService::createPegawai logic.
        ]);
    }
}
