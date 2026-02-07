<?php
namespace App\Imports\Pemtu;

use App\Models\Pemtu\OrgUnit;
use App\Models\Pemtu\Personil;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PersonilImport implements ToModel, WithHeadingRow
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

        return new Personil([
            'user_id'         => $user ? $user->id : null,
            'org_unit_id'     => $orgUnit ? $orgUnit->orgunit_id : null,
            'nama'            => $row['nama'],
            'email'           => $row['email'] ?? null,
            'jenis'           => $row['jenis'] ?? 'Dosen', // Default
            'external_id'     => $row['nip'] ?? $row['id'] ?? null,
            'external_source' => 'import',
        ]);
    }
}
