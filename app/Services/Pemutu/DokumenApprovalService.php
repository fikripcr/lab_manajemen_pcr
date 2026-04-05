<?php

namespace App\Services\Pemutu;

use App\Models\Pemutu\Dokumen;
use App\Services\Sys\ApprovalService;
use BaconQrCode\Renderer\GDLibRenderer;
use BaconQrCode\Writer;
use PhpOffice\PhpWord\PhpWord;

/**
 * Dokumen-specific approval logic.
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
     * @return array{0: bool, 1: string|null} [$isSah, $qrCodePath]
     */
    public function resolveValidationStatus(Dokumen $dokumen): array
    {
        $approvals = $dokumen->riwayatApprovals;

        if ($approvals->count() === 0 || $approvals->where('status', 'Approved')->count() !== $approvals->count()) {
            return [false, null];
        }

        $verifyUrl = route('pemutu.dokumen.verify', $dokumen->encrypted_dok_id);
        $qrCodePath = $this->generateQrCodePng($verifyUrl);

        return [true, $qrCodePath];
    }

    /**
     * Export dokumen ke DOCX dengan daftar approver dan QR code.
     */
    public function exportToDocx(Dokumen $dokumen)
    {
        $approvals = $dokumen->riwayatApprovals;
        [$isSah, $qrCodePath] = $this->resolveValidationStatus($dokumen);

        $phpWord = new PhpWord();
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(12);

        $section = $phpWord->addSection([
            'marginTop' => 720,
            'marginBottom' => 720,
            'marginLeft' => 720,
            'marginRight' => 720,
        ]);

        // Header
        $section->addText('DOKUMEN SPMI', ['bold' => true, 'size' => 16, 'align' => 'center']);
        $section->addTextBreak(1);

        // Document info table
        $table = $section->addTable(['borderSize' => 6, 'borderColor' => '000000', 'cellMargin' => 80]);
        $table->addRow();
        $table->addCell(2500)->addText('Kode Dokumen', ['bold' => true]);
        $table->addCell()->addText($dokumen->kode ?? '-');
        $table->addRow();
        $table->addCell(2500)->addText('Judul', ['bold' => true]);
        $table->addCell()->addText($dokumen->judul ?? '-');
        $table->addRow();
        $table->addCell(2500)->addText('Jenis', ['bold' => true]);
        $table->addCell()->addText(ucfirst($dokumen->jenis ?? '-'));
        $table->addRow();
        $table->addCell(2500)->addText('Periode', ['bold' => true]);
        $table->addCell()->addText($dokumen->periode ?? '-');
        $table->addRow();
        $table->addCell(2500)->addText('Status', ['bold' => true]);
        $table->addCell()->addText($dokumen->std_is_staging ? 'Staging (Draft)' : 'Aktif');

        $section->addTextBreak(2);

        // Content
        $section->addText('Isi Dokumen', ['bold' => true, 'size' => 14]);
        $section->addTextBreak(1);
        $section->addText(strip_tags($dokumen->isi ?? '(Tidak ada isi)'));

        $section->addTextBreak(2);

        // Approver section
        $section->addText('Daftar Pengesahan', ['bold' => true, 'size' => 14]);
        $section->addTextBreak(1);

        $approvalTable = $section->addTable(['borderSize' => 6, 'borderColor' => '000000', 'cellMargin' => 80]);
        $approvalTable->addRow();
        $approvalTable->addCell(400)->addText('No', ['bold' => true, 'align' => 'center']);
        $approvalTable->addCell(3000)->addText('Pejabat', ['bold' => true]);
        $approvalTable->addCell(2000)->addText('Jabatan', ['bold' => true]);
        $approvalTable->addCell(1500)->addText('Status', ['bold' => true, 'align' => 'center']);
        $approvalTable->addCell(2500)->addText('Tanggal', ['bold' => true, 'align' => 'center']);

        foreach ($approvals as $index => $approval) {
            $approvalTable->addRow();
            $approvalTable->addCell(400)->addText($index + 1, ['align' => 'center']);
            $approvalTable->addCell(3000)->addText($approval->pejabat ?? '-');
            $approvalTable->addCell(2000)->addText($approval->jabatan ?? '-');

            $statusText = match ($approval->status) {
                'Approved' => 'Disetujui',
                'Rejected' => 'Ditolak',
                default => 'Pending',
            };
            $approvalTable->addCell(1500)->addText($statusText, ['align' => 'center']);
            $approvalTable->addCell(2500)->addText(
                $approval->status !== 'Pending' ? $approval->updated_at?->format('d M Y H:i') : '-',
                ['align' => 'center']
            );
        }

        // QR Code section
        if ($isSah && $qrCodePath) {
            $section->addTextBreak(2);
            $section->addText('QR Code Verifikasi', ['bold' => true, 'size' => 12]);

            $section->addImage($qrCodePath, [
                'width' => 120,
                'height' => 120,
                'align' => 'center',
            ]);

            $section->addText(
                'Scan untuk verifikasi keaslian dokumen.',
                ['italic' => true, 'size' => 10, 'align' => 'center']
            );
        }

        // Footer
        $section->addTextBreak(2);
        $section->addText(
            'Dicetak otomatis dari Sistem Penjaminan Mutu Internal — ' . now()->format('d M Y H:i'),
            ['italic' => true, 'size' => 9, 'color' => '808080']
        );

        // Save
        $fileName = 'Dokumen_' . ($dokumen->kode ?? 'SPMI') . '_' . date('YmdHis') . '.docx';
        $tempPath = storage_path('app/temp/' . $fileName);

        if (!file_exists(dirname($tempPath))) {
            mkdir(dirname($tempPath), 0755, true);
        }

        $phpWord->save($tempPath);

        if ($qrCodePath && file_exists($qrCodePath)) {
            @unlink($qrCodePath);
        }

        return response()->download($tempPath, $fileName)->deleteFileAfterSend(true);
    }

    /**
     * Generate QR Code sebagai file PNG menggunakan GDLibRenderer.
     */
    protected function generateQrCodePng(string $url): ?string
    {
        try {
            $tempDir = storage_path('app/temp');
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            $qrCodePath = $tempDir . '/qr_' . uniqid() . '.png';

            $renderer = new GDLibRenderer(300);
            $writer = new Writer($renderer);
            $writer->writeFile($url, $qrCodePath);

            if (file_exists($qrCodePath)) {
                return $qrCodePath;
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }
}
