<?php
namespace App\Services\Lab;

use App\Models\Lab\Mahasiswa;
use Illuminate\Support\Facades\DB;

class MahasiswaService
{
    /**
     * Get Query for DataTables
     */
    public function getFilteredQuery(array $filters = [])
    {
        return Mahasiswa::with(['user.roles', 'prodi']);
    }

    /**
     * Get Mahasiswa by ID
     */
    public function getById(string $id): ?Mahasiswa
    {
        return Mahasiswa::with(['user.roles', 'prodi'])->find($id);
    }

    /**
     * Create New Mahasiswa
     */
    public function createMahasiswa(array $data): Mahasiswa
    {
        return DB::transaction(function () use ($data) {
            $data['created_by'] = auth()->id();
            $data['updated_by'] = auth()->id();

            $mahasiswa = Mahasiswa::create($data);

            logActivity('mahasiswa', "Menambahkan mahasiswa baru: {$data['nama']}");

            return $mahasiswa;
        });
    }

    /**
     * Update Mahasiswa
     */
    public function updateMahasiswa(Mahasiswa $mahasiswa, array $data): bool
    {
        return DB::transaction(function () use ($mahasiswa, $data) {
            $data['updated_by'] = auth()->id();

            $mahasiswa->update($data);

            logActivity('mahasiswa', "Memperbarui data mahasiswa ID {$mahasiswa->mahasiswa_id}");

            return true;
        });
    }

    /**
     * Delete Mahasiswa
     */
    public function deleteMahasiswa(Mahasiswa $mahasiswa): bool
    {
        return DB::transaction(function () use ($mahasiswa) {
            $mahasiswa->delete();

            logActivity('mahasiswa', "Menghapus data mahasiswa ID {$mahasiswa->mahasiswa_id}");

            return true;
        });
    }
}
