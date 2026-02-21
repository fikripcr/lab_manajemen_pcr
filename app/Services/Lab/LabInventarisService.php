<?php
namespace App\Services\Lab;

use App\Models\Lab\Inventaris;
use App\Models\Lab\Lab;
use App\Models\Lab\LabInventaris;
use Exception;
use Illuminate\Support\Facades\DB;

class LabInventarisService
{
    /**
     * Get Query for DataTables (Specific to a Lab)
     */
    public function getLabInventarisQuery(string $labId)
    {
        return LabInventaris::with(['inventaris', 'lab'])
            ->where('lab_id', $labId);
    }

    /**
     * Get LabInventaris by ID
     */
    public function getAssignmentById(string $id): ?LabInventaris
    {
        return LabInventaris::with(['inventaris', 'lab'])->find($id);
    }

    /**
     * Assign Inventaris to Lab
     */
    public function assignInventaris(string $labId, array $data): LabInventaris
    {
        return DB::transaction(function () use ($labId, $data) {
            $inventarisId = $data['inventaris_id'];

            // Generate Code
            $kodeInventaris = LabInventaris::generateKodeInventaris($labId, $inventarisId);

            $labInventaris = LabInventaris::create([
                'inventaris_id'      => $inventarisId,
                'lab_id'             => $labId,
                'kode_inventaris'    => $kodeInventaris,
                'no_series'          => $data['no_series'] ?? null,
                'tanggal_penempatan' => $data['tanggal_penempatan'] ?? now(),
                'keterangan'         => $data['keterangan'] ?? null,
                'status'             => $data['status'] ?? 'active',
            ]);

            logActivity(
                'lab_inventaris_management',
                "Menambahkan inventaris ID: {$inventarisId} ke Lab ID: {$labId} dengan kode: {$kodeInventaris}"
            );

            return $labInventaris;
        });
    }

    /**
     * Update Assignment
     */
    public function updateAssignment(LabInventaris $assignment, array $data): bool
    {
        return DB::transaction(function () use ($assignment, $data) {
            $assignment->update($data);

            logActivity('lab_inventaris_management', "Memperbarui inventaris lab ID: {$assignment->id}");

            return true;
        });
    }

    /**
     * Remove Assignment (Soft Delete)
     */
    public function deleteAssignment(LabInventaris $assignment): bool
    {
        return DB::transaction(function () use ($assignment) {
            $kode = $assignment->kode_inventaris;
            $assignment->delete();

            logActivity('lab_inventaris_management', "Menghapus inventaris lab: {$kode}");

            return true;
        });
    }

    protected function findOrFail(string $id): LabInventaris
    {
        $model = LabInventaris::find($id);
        if (! $model) {
            throw new Exception("Data Inventaris Lab dengan ID {$id} tidak ditemukan.");
        }
        return $model;
    }
}
