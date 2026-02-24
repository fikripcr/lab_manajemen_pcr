<?php
namespace App\Services\Lab;

use App\Models\Lab\RiwayatApproval;
use App\Models\Lab\SuratBebasLab;
use Illuminate\Support\Facades\DB;

class SuratBebasLabService
{
    /**
     * Get Query for DataTables
     */
    public function getFilteredQuery(array $filters = [])
    {
        return SuratBebasLab::with(['student', 'latestApproval'])
            ->latest();
    }

    /**
     * Get Request by ID
     */
    public function getById(string $id): ?SuratBebasLab
    {
        return SuratBebasLab::with(['student', 'latestApproval', 'approvals'])->find($id);
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
            ]);

            // Create Initial Approval (Pending)
            $approval = RiwayatApproval::create([
                'model'    => SuratBebasLab::class,
                'model_id' => $surat->surat_bebas_lab_id,
                'status'   => 'pending',
                'catatan'  => $data['catatan'] ?? 'Pengajuan baru',
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
            $catatan = $data['catatan'] ?? null;

            // Create New Approval Record
            $approval = RiwayatApproval::create([
                'model'    => SuratBebasLab::class,
                'model_id' => $surat->surat_bebas_lab_id,
                'status'   => $status,
                'pejabat'  => auth()->user()->name,
                'jabatan'  => auth()->user()->pegawai->jabatan_fungsional->jabfungsional ?? 'Staff',
                'catatan'  => $catatan,
            ]);

            $surat->update([
                'status' => $status,
                'latest_riwayatapproval_id' => $approval->riwayatapproval_id,
            ]);

            logActivity('surat_bebas_lab', "Update status pengajuan surat bebas lab ID {$surat->surat_bebas_lab_id} menjadi {$status}");

            return true;
        });
    }
}
