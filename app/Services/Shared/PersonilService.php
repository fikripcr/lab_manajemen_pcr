<?php
namespace App\Services\Shared;

use App\Models\Shared\Personil;
use Illuminate\Support\Facades\DB;

class PersonilService
{
    public function getFilteredQuery(array $filters = [])
    {
        return Personil::with(['orgUnit', 'user.roles']);
    }

    public function createPersonil(array $data): Personil
    {
        return DB::transaction(function () use ($data) {
            $data['status_aktif'] = $data['status_aktif'] ?? true;
            $personil             = Personil::create($data);

            logActivity('personil_management', "Menambah personil baru: {$personil->nama}", $personil);

            return $personil;
        });
    }

    public function updatePersonil($id, array $data): bool
    {
        return DB::transaction(function () use ($id, $data) {
            $personil = Personil::findOrFail($id);
            $oldName  = $personil->nama;

            $data['status_aktif'] = $data['status_aktif'] ?? $personil->status_aktif;
            $personil->update($data);

            logActivity(
                'personil_management',
                "Memperbarui personil: {$oldName}" . ($oldName !== $personil->nama ? " menjadi {$personil->nama}" : ""),
                $personil
            );

            return true;
        });
    }

    public function deletePersonil($id): bool
    {
        return DB::transaction(function () use ($id) {
            $personil = Personil::findOrFail($id);
            $name     = $personil->nama;

            $personil->delete();

            logActivity('personil_management', "Menghapus personil: {$name}");

            return true;
        });
    }
}
