<?php
namespace App\Services\Lab;

use App\Models\Lab\LabRiwayatApproval;
use App\Models\Lab\SuratBebasLab;
use Illuminate\Support\Facades\DB;

class SuratBebasLabService
{
    /**
     * Get Query for DataTables
     */
    public function getFilteredQuery(array $filters = [])
    {
        return SuratBebasLab::with(['student', 'approver'])
            ->latest();
    }

    /**
     * Get Request by ID
     */
    public function getById(string $id): ?SuratBebasLab
    {
        return SuratBebasLab::with(['student', 'approver', 'approvals'])->find($id);
    }

    /**
     * Create New Lab Clearance Request
     */
    public function createRequest(array $data): SuratBebasLab
    {
        return DB::transaction(function () use ($data) {
            $surat = SuratBebasLab::create([
                'student_id' => auth()->id(),
                'status'     => 'pending',
                'remarks'    => $data['remarks'] ?? null,
            ]);

            // Create Initial Approval (Pending)
            $approval = LabRiwayatApproval::create([
                'model'      => SuratBebasLab::class,
                'model_id'   => $surat->surat_bebas_lab_id,
                'status'     => 'pending',
                'keterangan' => 'Pengajuan baru',
                'created_by' => auth()->id(),
            ]);

            $surat->update(['latest_riwayatapproval_id' => $approval->riwayatapproval_id]);

            logActivity('surat_bebas_lab', "Membuat pengajuan surat bebas lab baru untuk mahasiswa ID " . auth()->id());

            return $surat;
        });
    }

    /**
     * Update Status and Handle Approval
     */
    public function updateStatus(SuratBebasLab $surat, array $data): bool
    {
        return DB::transaction(function () use ($surat, $data) {
            $status  = $data['status'];
            $remarks = $data['remarks'] ?? null;

            $updateData = [
                'status'      => $status,
                'remarks'     => $remarks,
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ];

            // Create New Approval Record
            $approval = LabRiwayatApproval::create([
                'model'      => SuratBebasLab::class,
                'model_id'   => $surat->surat_bebas_lab_id,
                'status'     => $status,
                'pejabat'    => auth()->user()->name,
                'keterangan' => $remarks,
                'created_by' => auth()->id(),
            ]);

            $updateData['latest_riwayatapproval_id'] = $approval->riwayatapproval_id;

            $surat->update($updateData);

            logActivity('surat_bebas_lab', "Update status pengajuan surat bebas lab ID {$surat->surat_bebas_lab_id} menjadi {$status}");

            return true;
        });
    }
}
