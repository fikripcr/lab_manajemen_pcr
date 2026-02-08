<?php
namespace App\Services\Lab;

use App\Models\Lab\Inventaris;
use App\Models\Lab\Lab;
use App\Models\Lab\LabInventaris;
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
    public function updateAssignment(string $id, array $data): bool
    {
        return DB::transaction(function () use ($id, $data) {
            $assignment = $this->findOrFail($id);

            // Allow updating inventaris_id? If so, might need to regenerate code.
            // Controller allows it. Let's assume code regeneration is NOT required or handled if ID changes?
            // Controller logic: $labInventaris->update(['inventaris_id' => ...]);
            // If inventaris_id changes, kode_inventaris might become invalid (it usually contains tool code).
            // LabInventaris model helper `generateKodeInventaris` uses `Inventaris` type/count.
            // If we change type, we should probably regenerate code.
            // But for now, let's stick to simple update as per Controller logic which didn't regenerate code explicitly on update.
            // Wait, Controller DOES NOT regenerate code on Update. It just updates fields.

            $assignment->update($data);

            logActivity('lab_inventaris_management', "Memperbarui inventaris lab ID: {$id}");

            return true;
        });
    }

    /**
     * Remove Assignment (Soft Delete)
     */
    public function deleteAssignment(string $id): bool
    {
        return DB::transaction(function () use ($id) {
            $assignment = $this->findOrFail($id);
            $kode       = $assignment->kode_inventaris;

            $assignment->delete();

            logActivity('lab_inventaris_management', "Menghapus inventaris lab: {$kode}");

            return true;
        });
    }

    protected function findOrFail(string $id): LabInventaris
    {
        $model = LabInventaris::find($id);
        if (! $model) {
            throw new \Exception("Data Inventaris Lab dengan ID {$id} tidak ditemukan.");
        }
        return $model;
    }
}
