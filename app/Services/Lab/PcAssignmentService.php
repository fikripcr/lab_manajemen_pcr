<?php
namespace App\Services\Lab;

use App\Models\Lab\PcAssignment;
use Exception;
use Illuminate\Support\Facades\DB;

class PcAssignmentService
{
    /**
     * Get Query for Assignments by Schedule
     */
    public function getAssignmentsByJadwalQuery(string $jadwalId)
    {
        return PcAssignment::with(['user'])
            ->where('jadwal_id', $jadwalId)
            ->select('lab_pc_assignments.*');
    }

    /**
     * Get Assignment by ID
     */
    public function getAssignmentById(string $id): ?PcAssignment
    {
        return PcAssignment::find($id);
    }

    /**
     * Create Assignment
     */
    public function createAssignment(JadwalKuliah $jadwal, array $data): PcAssignment
    {
        $userId  = $data['user_id'];
        $nomorPc = $data['nomor_pc'];

        // Cek apakah mahasiswa sudah punya PC di jadwal ini
        $existing = PcAssignment::where('jadwal_id', $jadwal->jadwal_kuliah_id)
            ->where('user_id', $userId)
            ->where('is_active', true)
            ->first();

        if ($existing) {
            throw new Exception('Mahasiswa ini sudah memiliki assignment PC aktif di jadwal ini.');
        }

        // Cek apakah PC sudah dipakai orang lain
        $pcTaken = PcAssignment::where('jadwal_id', $jadwal->jadwal_kuliah_id)
            ->where('nomor_pc', $nomorPc)
            ->where('is_active', true)
            ->first();

        if ($pcTaken) {
            throw new Exception('Nomor PC ini sudah dipakai oleh mahasiswa lain.');
        }

        return DB::transaction(function () use ($jadwal, $data) {
            $assignment = PcAssignment::create([
                'jadwal_id'     => $jadwal->jadwal_kuliah_id,
                'lab_id'        => $jadwal->lab_id,
                'user_id'       => $data['user_id'],
                'nomor_pc'      => $data['nomor_pc'],
                'nomor_loker'   => $data['nomor_loker'] ?? null,
                'assigned_date' => now(),
                'is_active'     => true,
            ]);

            logActivity('lab_assignment', "Assign User ID: {$data['user_id']} to PC {$data['nomor_pc']} in Schedule {$jadwal->jadwal_kuliah_id}");

            return $assignment;
        });
    }

    /**
     * Remove Assignment
     */
    public function deleteAssignment(PcAssignment $assignment): bool
    {
        return DB::transaction(function () use ($assignment) {
            $assignment->delete();
            logActivity('lab_assignment', "Menghapus data assignment PC ID {$assignment->pc_assignment_id}");
            return true;
        });
    }

    protected function findOrFail(string $id): PcAssignment
    {
        $model = PcAssignment::find($id);
        if (! $model) {
            throw new Exception("Data Assignment dengan ID {$id} tidak ditemukan.");
        }
        return $model;
    }
}
