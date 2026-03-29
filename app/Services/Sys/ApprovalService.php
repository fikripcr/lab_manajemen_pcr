<?php

namespace App\Services\Sys;

use App\Models\Hr\Pegawai;
use App\Models\Sys\SysApproval;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Global Approval Service - Polymorphic Approval Management
 * 
 * Digunakan untuk mengelola approval workflow di berbagai modul:
 * - Pemutu (Dokumen SPMI)
 * - HR (Perizinan, Lembur)
 * - PMB (Pendaftaran)
 * - Lab (Request Software, Surat Bebas, Kegiatan)
 * - Dan modul lainnya
 * 
 * ## Usage Example:
 * 
 * ```php
 * // Di controller
 * $result = $this->approvalService->syncApprovers($dokumen, $approvers);
 * 
 * // Result structure:
 * [
 *     'added' => ['John Doe', 'Jane Smith'],
 *     'updated' => ['Bob Williams'],
 *     'deleted_count' => 1,
 *     'messages' => ['Menambah 2 approver: John Doe, Jane Smith', ...]
 * ]
 * ```
 * 
 * @package App\Services\Sys
 */
class ApprovalService
{
    /**
     * Sinkronisasikan daftar approver untuk model apapun (polymorphic)
     * 
     * Handles CREATE, UPDATE, and DELETE operations based on form input.
     * 
     * @param  Model  $model  Model yang akan di-approve (Dokumen, Perizinan, dll)
     * @param  array  $approvers  Array approver dari form
     * @return array{added: array, updated: array, deleted_count: int, messages: array}
     */
    public function syncApprovers(Model $model, array $approvers): array
    {
        return DB::transaction(function () use ($model, $approvers) {
            $addedNames = [];
            $updatedNames = [];
            $deletedCount = 0;
            
            // Get all current pending approval IDs (polymorphic)
            $currentPendingIds = $model->sysApprovals()
                ->where('status', 'Pending')
                ->pluck('sys_approval_id')
                ->toArray();
            
            $processedIds = [];

            foreach ($approvers as $appData) {
                if (empty($appData['pegawai_id'])) {
                    continue;
                }

                // Check if this is an existing approval (has ID)
                if (!empty($appData['id'])) {
                    // UPDATE existing approval
                    $approval = SysApproval::find($appData['id']);
                    
                    if ($approval && $approval->status === 'Pending') {
                        $oldPejabat = $approval->pejabat;
                        
                        $approval->update([
                            'pegawai_id' => $this->decryptPegawaiId($appData['pegawai_id']),
                            'pejabat' => Pegawai::find($this->decryptPegawaiId($appData['pegawai_id']))->nama ?? $oldPejabat,
                            'jabatan' => $appData['jabatan'] ?? '-',
                        ]);
                        
                        $updatedNames[] = $approval->pejabat;
                        $processedIds[] = $approval->sys_approval_id;
                    }
                } else {
                    // CREATE new approval
                    $pegawaiId = $this->decryptPegawaiId($appData['pegawai_id']);
                    $pegawai = Pegawai::findOrFail($pegawaiId);

                    $approval = $model->sysApprovals()->create([
                        'pegawai_id' => $pegawaiId,
                        'pejabat' => $pegawai->nama,
                        'jabatan' => $appData['jabatan'] ?? '-',
                        'catatan' => null,
                        'status' => 'Pending', // ✅ EXPLICIT: Set status ke Pending saat approver ditambahkan
                    ]);

                    $addedNames[] = $pegawai->nama;
                    $processedIds[] = $approval->sys_approval_id;
                }
            }

            // DELETE approvals that are not in the form (removed by user)
            $toDelete = array_diff($currentPendingIds, $processedIds);
            if (!empty($toDelete)) {
                $deletedCount = SysApproval::whereIn('sys_approval_id', $toDelete)->delete();
            }

            // Build messages for logging
            $messages = [];
            if (!empty($addedNames)) {
                $messages[] = "Menambah ".count($addedNames)." approver: ".implode(', ', $addedNames);
            }
            if (!empty($updatedNames)) {
                $messages[] = "Mengupdate ".count($updatedNames)." approver: ".implode(', ', $updatedNames);
            }
            if ($deletedCount > 0) {
                $messages[] = "Menghapus {$deletedCount} approver";
            }

            return [
                'added' => $addedNames,
                'updated' => $updatedNames,
                'deleted_count' => $deletedCount,
                'messages' => $messages,
            ];
        });
    }

    /**
     * Hapus single approval
     * 
     * @param  SysApproval  $approval
     * @return string Nama model/subject yang dihapus approvalnya
     */
    public function deleteApproval(SysApproval $approval): string
    {
        $subject = $approval->subject;
        $subjectName = $subject ? ($subject->judul ?? $subject->nama ?? 'Unknown') : 'Unknown';
        
        $approval->delete();
        
        return $subjectName;
    }

    /**
     * Get inbox query untuk pegawai tertentu
     * 
     * @param  int  $pegawaiId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getInboxQuery(int $pegawaiId)
    {
        return SysApproval::with(['subject'])
            ->where('pegawai_id', $pegawaiId)
            ->latest();
    }

    /**
     * Get approvals query, optionally filtered by model type
     *
     * @param  string|null  $modelClass  Fully qualified model class (e.g. Dokumen::class)
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getApprovalsByTypeQuery(?string $modelClass = null)
    {
        $query = SysApproval::with(['subject']);

        if ($modelClass) {
            $query->where('model', $modelClass);
        }

        return $query->latest();
    }

    /**
     * Proses approval action (approve/reject)
     * 
     * @param  int  $approvalId  ID approval
     * @param  string  $status  Status (Approved/Rejected)
     * @param  string|null  $catatan  Catatan approval
     * @return SysApproval
     */
    public function processApproval(int $approvalId, string $status, ?string $catatan = null): SysApproval
    {
        return DB::transaction(function () use ($approvalId, $status, $catatan) {
            $approval = SysApproval::where('status', 'Pending')
                ->findOrFail($approvalId);

            $approval->update([
                'status' => $status,
                'catatan' => $catatan,
            ]);

            return $approval;
        });
    }

    /**
     * Check apakah semua approver sudah approve
     * 
     * @param  Model  $model
     * @return bool
     */
    public function isFullyApproved(Model $model): bool
    {
        $approvals = $model->sysApprovals;
        
        if ($approvals->count() === 0) {
            return false;
        }

        return $approvals->where('status', 'Approved')->count() === $approvals->count();
    }

    /**
     * Get approval status summary
     * 
     * @param  Model  $model
     * @return array{total: int, approved: int, rejected: int, pending: int, is_sah: bool}
     */
    public function getStatusSummary(Model $model): array
    {
        $approvals = $model->sysApprovals;
        
        $total = $approvals->count();
        $approved = $approvals->where('status', 'Approved')->count();
        $rejected = $approvals->where('status', 'Rejected')->count();
        $pending = $approvals->where('status', 'Pending')->count();
        
        return [
            'total' => $total,
            'approved' => $approved,
            'rejected' => $rejected,
            'pending' => $pending,
            'is_sah' => $total > 0 && $approved === $total,
        ];
    }

    /**
     * Decrypt pegawai ID (helper method)
     * 
     * @param  string  $pegawaiId
     * @return int
     */
    private function decryptPegawaiId(string $pegawaiId): int
    {
        return decryptIdIfEncrypted($pegawaiId);
    }

    /**
     * Get data for approval form
     * 
     * @param  Model  $model
     * @return array{pegawais: \Illuminate\Support\Collection, isSah: bool, qrCode: string|null, existingApprovals: \Illuminate\Support\Collection, approvals: \Illuminate\Support\Collection}
     */
    public function getApprovalFormData(Model $model): array
    {
        $pegawais = Pegawai::with('latestDataDiri')->get()->sortBy('nama');
        $approvals = $model->sysApprovals;

        [$isSah, $qrCode] = $this->resolveValidationStatus($model, $approvals);

        $existingApprovals = $approvals->where('status', 'Pending')->values();

        return compact('pegawais', 'isSah', 'qrCode', 'existingApprovals', 'approvals');
    }

    /**
     * Get public verification data
     * 
     * @param  Model  $model
     * @return array{approvals: \Illuminate\Support\Collection, status: string, icon: string, color: string, desc: string}
     */
    public function getPublicVerificationData(Model $model): array
    {
        $model->load('sysApprovals');
        $approvals = $model->sysApprovals;

        $totalApproval = $approvals->count();
        $approvedCount = $approvals->where('status', 'Approved')->count();
        $rejectedCount = $approvals->where('status', 'Rejected')->count();

        $status = 'Dalam Proses Verifikasi';
        $icon = 'ti ti-loader';
        $color = 'orange';
        $desc = 'Dokumen ini masih menunggu konfirmasi dari beberapa pihak berwenang.';

        if ($totalApproval == 0) {
            $status = 'Persetujuan Belum Diatur';
            $icon = 'ti ti-file-unknown';
            $color = 'secondary';
            $desc = 'Belum ada daftar pejabat penandatangan otentikator yang ditautkan ke dokumen ini.';
        } elseif ($rejectedCount > 0) {
            $status = 'Ditolak';
            $icon = 'ti ti-x';
            $color = 'red';
            $desc = 'Dokumen ini tidak sah karena ada pihak yang menolak.';
        } elseif ($approvedCount == $totalApproval) {
            $status = 'Sah dan Tervalidasi';
            $icon = 'ti ti-shield-check';
            $color = 'green';
            $desc = 'Dokumen ini merupakan Dokumen Master yang sah, telah disetujui, dan berlaku di lingkungan institusi.';
        }

        return compact('approvals', 'status', 'icon', 'color', 'desc');
    }

    /**
     * Resolve validation status and generate QR code if approved
     * 
     * @param  Model  $model
     * @param  \Illuminate\Support\Collection  $approvals
     * @return array{0: bool, 1: string|null} [isSah, qrCode]
     */
    private function resolveValidationStatus(Model $model, $approvals): array
    {
        if ($approvals->count() === 0 || $approvals->where('status', 'Approved')->count() !== $approvals->count()) {
            return [false, null];
        }

        // Get route based on model type
        $verifyUrl = $this->getVerifyUrl($model);
        $qrCode = null;

        if (class_exists(\BaconQrCode\Writer::class)) {
            $renderer = new \BaconQrCode\Renderer\ImageRenderer(
                new \BaconQrCode\Renderer\RendererStyle\RendererStyle(120, 1),
                new \BaconQrCode\Renderer\Image\SvgImageBackEnd
            );
            $writer = new \BaconQrCode\Writer($renderer);
            $qrCode = $writer->writeString($verifyUrl);
        }

        return [true, $qrCode];
    }

    /**
     * Get verify URL based on model type
     * 
     * @param  Model  $model
     * @return string
     */
    private function getVerifyUrl(Model $model): string
    {
        // Default to Pemutu route, can be extended for other modules
        if ($model instanceof \App\Models\Pemutu\Dokumen) {
            return route('pemutu.dokumen.verify', $model->encrypted_dok_id ?? $model->id);
        }
        
        // Add more routes for other modules as needed
        return route('pemutu.dokumen.verify', $model->id);
    }
}
