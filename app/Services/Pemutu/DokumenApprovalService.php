<?php

namespace App\Services\Pemutu;

use App\Models\Pemutu\Dokumen;
use App\Services\Sys\ApprovalService;

/**
 * Dokumen-specific approval logic.
 *
 * Most approval functionality has been migrated to the global ApprovalService.
 * This service only retains Dokumen-specific verification logic (QR code generation).
 */
class DokumenApprovalService
{
    public function __construct(
        protected ApprovalService $approvalService,
    ) {}

    /**
     * Sinkronisasikan daftar approver (pejabat penandatangan) dokumen.
     */
    public function syncApprovers(Dokumen $dokumen, array $approvers): array
    {
        return $this->approvalService->syncApprovers($dokumen, $approvers);
    }

    /**
     * Cek apakah semua approver sudah menyetujui dan generate QR Code jika sah.
     *
     * @return array{0: bool, 1: string|null} [$isSah, $qrCode]
     */
    public function resolveValidationStatus(Dokumen $dokumen): array
    {
        $approvals = $dokumen->riwayatApprovals;

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
