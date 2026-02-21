<?php
namespace App\Services\Lab;

use App\Models\Lab\Personil;
use Illuminate\Support\Facades\DB;

class PersonilService
{
    /**
     * Get Query for DataTables
     */
    public function getFilteredQuery(array $filters = [])
    {
        return Personil::with(['user.roles']);
    }

    /**
     * Get Personil by ID
     */
    public function getById(string $id): ?Personil
    {
        return Personil::with(['user.roles'])->find($id);
    }

    /**
     * Create New Personil
     */
    public function createPersonil(array $data): Personil
    {
        return DB::transaction(function () use ($data) {
            $data['created_by'] = auth()->id();
            $data['updated_by'] = auth()->id();

            $personil = Personil::create($data);

            logActivity('personil', "Menambahkan personil baru: {$data['nama']}");

            return $personil;
        });
    }

    /**
     * Update Personil
     */
    public function updatePersonil(Personil $personil, array $data): bool
    {
        return DB::transaction(function () use ($personil, $data) {
            $data['updated_by'] = auth()->id();

            $personil->update($data);

            logActivity('personil', "Memperbarui data personil ID {$personil->personil_id}");

            return true;
        });
    }

    /**
     * Delete Personil
     */
    public function deletePersonil(Personil $personil): bool
    {
        return DB::transaction(function () use ($personil) {
            $personil->delete();

            logActivity('personil', "Menghapus data personil ID {$personil->personil_id}");

            return true;
        });
    }
}
