<?php
namespace App\Services\Lab;

use App\Imports\JadwalImport;
use App\Models\Lab\JadwalKuliah;
use Exception;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class JadwalService
{
    /**
     * Get filtered query for DataTables
     */
    public function getFilteredQuery(array $filters = [])
    {
        $query = JadwalKuliah::withTrashed()
            ->from('lab_jadwal_kuliah as jadwal_kuliah')
            ->select([
                'jadwal_kuliah.jadwal_kuliah_id',
                'jadwal_kuliah.semester_id',
                'jadwal_kuliah.mata_kuliah_id',
                'jadwal_kuliah.dosen_id',
                'jadwal_kuliah.hari',
                'jadwal_kuliah.jam_mulai',
                'jadwal_kuliah.jam_selesai',
                'jadwal_kuliah.lab_id',
                'jadwal_kuliah.created_at',
                'jadwal_kuliah.updated_at',
                'jadwal_kuliah.deleted_at',
                'semesters.tahun_ajaran',
                'semesters.semester as semester_nama',
                'mata_kuliahs.kode_mk',
                'mata_kuliahs.nama_mk',
                'users.name as dosen_name',
                'labs.name as lab_name',
            ])
            ->with(['semester', 'mataKuliah', 'dosen', 'lab'])
            ->leftJoin('semesters', 'jadwal_kuliah.semester_id', '=', 'semesters.semester_id')
            ->leftJoin('mata_kuliahs', 'jadwal_kuliah.mata_kuliah_id', '=', 'mata_kuliahs.mata_kuliah_id')
            ->leftJoin('users', 'jadwal_kuliah.dosen_id', '=', 'users.id')
            ->leftJoin('labs', 'jadwal_kuliah.lab_id', '=', 'labs.lab_id')
            ->whereNull('jadwal_kuliah.deleted_at');

        // Apply specific filters
        if (! empty($filters['hari'])) {
            $query->where('jadwal_kuliah.hari', $filters['hari']);
        }

        if (! empty($filters['dosen'])) {
            $query->where('users.name', 'like', '%' . $filters['dosen'] . '%');
        }

        // Global search logic usually handled by DataTables 'filter' callback in Controller or here?
        // Service typically returns Builder. Controller applies specific DataTable logic.
        // But if I want to encapsulate logic, I can add a method or handle it here if passed explicitly.
        // For standard "filter" param from DataTables, complex logic is often in Controller closure.
        // But let's return the Builder.

        return $query;
    }

    /**
     * Get Jadwal by ID
     */
    public function getJadwalById(string $id): ?JadwalKuliah
    {
        return JadwalKuliah::with(['semester', 'mataKuliah', 'dosen', 'lab'])->find($id);
    }

    /**
     * Create a new Jadwal
     */
    public function createJadwal(array $data): JadwalKuliah
    {
        return DB::transaction(function () use ($data) {
            $jadwal = JadwalKuliah::create($data);

            logActivity('jadwal_management', "Membuat jadwal baru untuk matkul ID: {$jadwal->mata_kuliah_id}");

            return $jadwal;
        });
    }

    /**
     * Update an existing Jadwal
     */
    public function updateJadwal(string $id, array $data): bool
    {
        return DB::transaction(function () use ($id, $data) {
            $jadwal = $this->findOrFail($id);
            $jadwal->update($data);

            logActivity('jadwal_management', "Memperbarui jadwal ID: {$id}");

            return true;
        });
    }

    /**
     * Delete a Jadwal
     */
    public function deleteJadwal(string $id): bool
    {
        return DB::transaction(function () use ($id) {
            $jadwal = $this->findOrFail($id);

            // Dependency Check
            if ($jadwal->pcAssignments()->count() > 0 || $jadwal->logPenggunaanPcs()->count() > 0) {
                throw new Exception('Tidak dapat menghapus jadwal yang terkait dengan penggunaan PC (Assignments/Logs).');
            }

            $jadwal->delete();

            logActivity('jadwal_management', "Menghapus jadwal ID: {$id}");

            return true;
        });
    }

    /**
     * Import Jadwal from Excel
     */
    public function importJadwal($file)
    {
        return DB::transaction(function () use ($file) {
            Excel::import(new JadwalImport, $file);
            logActivity('jadwal_management', "Import jadwal dari file.");
            return true;
        });
    }

    protected function findOrFail(string $id): JadwalKuliah
    {
        $model = JadwalKuliah::find($id);
        if (! $model) {
            throw new \Exception("Jadwal dengan ID {$id} tidak ditemukan.");
        }
        return $model;
    }
}
