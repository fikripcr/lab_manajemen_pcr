<?php

namespace App\Services\Pemutu;

use App\Models\Pemutu\Dokumen;
use App\Models\Sys\SysApproval;
use App\Services\Sys\ApprovalService;
use Illuminate\Support\Facades\DB;

class DokumenApprovalService
{
    protected $approvalService;

    public function __construct(ApprovalService $approvalService)
    {
        $this->approvalService = $approvalService;
    }
    /**
     * Dapatkan data awal untuk form penetapan approver dokumen
     */
    public function getApprovalFormData(Dokumen $dokumen): array
    {
        $pegawais = Pegawai::with('latestDataDiri')->get()->sortBy('nama');
        $approvals = $dokumen->riwayatApprovals;

        [$isSah, $qrCode] = $this->resolveValidationStatus($dokumen, $approvals);

        $existingApprovals = $approvals->where('status', 'Pending')->values();

        return compact('pegawais', 'isSah', 'qrCode', 'existingApprovals', 'approvals');
    }

    /**
     * Sinkronisasikan daftar approver (pejabat penandatangan) dokumen
     * 
     * Handles CREATE, UPDATE, and DELETE operations based on form input.
     * 
     * @return array{added: array, updated: array, deleted_count: int, messages: array}
     */
    public function syncApprovers(Dokumen $dokumen, array $approvers): array
    {
        // Delegate to global ApprovalService
        return $this->approvalService->syncApprovers($dokumen, $approvers);
    }

    /**
     * Hapus single approval
     * 
     * @deprecated Use ApprovalService::deleteApproval() directly
     */
    public function deleteApproval(RiwayatApproval $approval): string
    {
        // Delegate to global ApprovalService
        return $this->approvalService->deleteApproval($approval);
    }

    /**
     * Kalkulasikan display data laman public verifikasi
     * 
     * @deprecated Use ApprovalService::getPublicVerificationData() directly
     */
    public function getPublicVerificationData(Dokumen $dokumen): array
    {
        // Delegate to global ApprovalService
        return $this->approvalService->getPublicVerificationData($dokumen);
    }

    // ==========================================
    // BAGIAN INBOX PEGAWAI (ApprovalController)
    // ==========================================
    // INBOX METHODS - Delegate to global ApprovalService
    // ==========================================

    /**
     * Kueri untuk tabel Datatables inbox pegawai
     * 
     * @deprecated Use ApprovalService::getInboxQuery() instead
     */
    public function getInboxQuery(string $pegawaiId)
    {
        return $this->approvalService->getInboxQuery((int) $pegawaiId);
    }

    /**
     * Eksekusi tampilan detail surat di inbox pegawai
     * 
     * @deprecated Use ApprovalService methods directly
     */
    public function getInboxShowData(string $pegawaiId, string $approvalId): array
    {
        $approval = $this->approvalService->getInboxQuery((int) $pegawaiId)
            ->where('sys_approval_id', decryptIdIfEncrypted($approvalId))
            ->with('subject')
            ->firstOrFail();

        $dokumen = $approval->subject;
        $isSah = false;
        $qrCode = null;
        $allApprovals = null;

        if ($dokumen instanceof Dokumen) {
            $allApprovals = $dokumen->riwayatApprovals;
            [$isSah, $qrCode] = $this->resolveValidationStatus($dokumen, $allApprovals);
        }

        return compact('approval', 'isSah', 'qrCode', 'allApprovals');
    }

    /**
     * Cek apakah semua approver sudah menyetujui dan generate QR Code jika sah.
     * Specific untuk Dokumen verification.
     *
     * @return array{0: bool, 1: string|null} [$isSah, $qrCode]
     */
    private function resolveValidationStatus(Dokumen $dokumen, $approvals): array
    {
        if ($approvals->count() === 0 || $approvals->where('status', 'Approved')->count() !== $approvals->count()) {
            return [false, null];
        }

        $verifyUrl = route('pemutu.dokumen.verify', $dokumen->encrypted_dok_id);
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
}
