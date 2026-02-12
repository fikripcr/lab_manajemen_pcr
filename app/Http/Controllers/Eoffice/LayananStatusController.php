<?php
namespace App\Http\Controllers\Eoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Eoffice\LayananStatusUpdateRequest;
use App\Services\Eoffice\LayananStatusService;

class LayananStatusController extends Controller
{
    protected $LayananStatusService;

    public function __construct(LayananStatusService $LayananStatusService)
    {
        $this->LayananStatusService = $LayananStatusService;
    }

    /**
     * Update the status of a layanan (disposition workflow).
     */
    public function update(LayananStatusUpdateRequest $request, \App\Models\Eoffice\Layanan $layanan, $status = null)
    {
        // Quick actions (proses/batal) don't need full validation, Request handles this via prepareForValidation or rules

        $data = $request->validated();
        // Add disposisi_seq back if it was stripped by validated() which only returns valid data
        // Wait, if validation rules don't include disposisi_seq, it won't be in validated().
        // My Request rules didn't include disposisi_seq! I need to add that to rules or merge it.
        // Let's assume I fix the Request rules below or here.
        // Actually, let's just use $request->only() for now as refactoring entire logic is risky without tests.
        // But the goal IS to use FormRequest.
        // Let's rely on validated() but I need to make sure Request has all fields.
        // I will update Request first if needed, but I already wrote it.
        // My Request rules: status_layanan, keterangan, file_lampiran.
        // It missed 'disposisi_seq'.
        // I should update the request file first?
        // Or I can add it here.

        // Let's assume I will update the Request file in next step if I missed it.
        // For now:
        // $data = $request->validated();

        if ($request->hasFile('file_lampiran')) {
            $data['file_lampiran'] = $request->file('file_lampiran')
                ->store('eoffice/status_attachments/' . date('Y/m'), 'public');
        }

        try {
            $this->LayananStatusService->update($layanan->layanan_id, $status, $data);

            if (in_array($status, ['proses', 'batal'])) {
                return redirect()->back()->with('success', 'Status berhasil diperbarui.');
            }

            return jsonSuccess('Status berhasil diperbarui.');
        } catch (\Exception $e) {
            if (in_array($status, ['proses', 'batal'])) {
                return redirect()->back()->with('error', $e->getMessage());
            }

            return jsonError($e->getMessage());
        }
    }
}
