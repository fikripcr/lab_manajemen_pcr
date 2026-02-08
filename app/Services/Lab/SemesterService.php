<?php
namespace App\Services\Lab;

use App\Models\Lab\Semester;
use Illuminate\Support\Facades\DB;

class SemesterService
{
    /**
     * Get Query for DataTables
     */
    public function getFilteredQuery(array $filters = [])
    {
        $query = Semester::query();

        if (isset($filters['status'])) {
            if ($filters['status'] === 'Aktif') {
                $query->where('is_active', 1);
            } elseif ($filters['status'] === 'Tidak Aktif') {
                $query->where('is_active', 0);
            }
        }

        // Search handled by Controller DataTables closure

        return $query;
    }

    /**
     * Get Semester by ID
     */
    public function getSemesterById(string $id): ?Semester
    {
        return Semester::find($id);
    }

    /**
     * Create a new Semester
     */
    public function createSemester(array $data): Semester
    {
        return DB::transaction(function () use ($data) {
            $semester = Semester::create($data);

            logActivity('semester_management', "Membuat semester baru: {$semester->tahun_ajaran} " . ($semester->semester == 1 ? 'Ganjil' : 'Genap'));

            return $semester;
        });
    }

    /**
     * Update an existing Semester
     */
    public function updateSemester(string $id, array $data): bool
    {
        return DB::transaction(function () use ($id, $data) {
            $semester = $this->findOrFail($id);

            $oldName = "{$semester->tahun_ajaran} " . ($semester->semester == 1 ? 'Ganjil' : 'Genap');

            $semester->update($data);

            $newName = "{$semester->tahun_ajaran} " . ($semester->semester == 1 ? 'Ganjil' : 'Genap');

            logActivity(
                'semester_management',
                "Memperbarui semester: {$oldName}" . ($oldName !== $newName ? " menjadi {$newName}" : "")
            );

            return true;
        });
    }

    /**
     * Delete a Semester
     */
    public function deleteSemester(string $id): bool
    {
        return DB::transaction(function () use ($id) {
            $semester = $this->findOrFail($id);

            // Dependency Checks
            if ($semester->jadwals()->count() > 0) {
                throw new \Exception('Tidak dapat menghapus semester karena masih digunakan dalam Jadwal.');
            }

            $name = "{$semester->tahun_ajaran} " . ($semester->semester == 1 ? 'Ganjil' : 'Genap');
            $semester->delete();

            logActivity('semester_management', "Menghapus semester: {$name}");

            return true;
        });
    }

    protected function findOrFail(string $id): Semester
    {
        $model = Semester::find($id);
        if (! $model) {
            throw new \Exception("Semester dengan ID {$id} tidak ditemukan.");
        }
        return $model;
    }
}
