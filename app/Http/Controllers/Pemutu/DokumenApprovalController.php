<?php

namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\DokumenApprovalRequest;
use App\Models\Pemutu\Dokumen;
use App\Models\Pemutu\RiwayatApproval;
use App\Services\Sys\ApprovalService;

class DokumenApprovalController extends Controller
{
    public function __construct(protected ApprovalService $approvalService) {}

    public function create(Dokumen $dokumen)
    {
        $data = $this->approvalService->getApprovalFormData($dokumen);
        $data['dokumen'] = $dokumen;

        return view('pages.pemutu.dokumen._approval_form', $data);
    }

    public function store(DokumenApprovalRequest $request, Dokumen $dokumen)
    {
        $result = $this->approvalService->syncApprovers($dokumen, $request->input('approvers', []));

        if (!empty($result['messages'])) {
            logActivity('pemutu', "Memperbarui daftar approval dokumen: {$dokumen->judul}. ".implode('. ', $result['messages']));
        }

        return jsonSuccess('Approver berhasil disinkronisasi.', url()->previous());
    }

    public function destroy(RiwayatApproval $approval)
    {
        $subjectName = $this->approvalService->deleteApproval($approval);

        logActivity('pemutu', "Menghapus approval untuk: {$subjectName}");

        return jsonSuccess('Approval berhasil dihapus');
    }

    public function verify(Dokumen $dokumen)
    {
        $data = $this->approvalService->getPublicVerificationData($dokumen);
        $data['dokumen'] = $dokumen;

        return view('pages.pemutu.dokumen.verify', $data);
    }
}
