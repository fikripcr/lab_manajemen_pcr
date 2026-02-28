<?php
namespace App\Http\Controllers\Eoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Eoffice\LayananStatusUpdateRequest;
use App\Models\Eoffice\Layanan;
use App\Services\Eoffice\LayananStatusService;

class LayananStatusController extends Controller
{
    public function __construct(protected LayananStatusService $LayananStatusService)
    {}

    /**
     * Update the status of a layanan (disposition workflow).
     */
    public function update(LayananStatusUpdateRequest $request, Layanan $layanan, $status = null)
    {
        $data = $request->validated();

        if ($request->hasFile('file_lampiran')) {
            $data['file_lampiran'] = $request->file('file_lampiran')
                ->store('eoffice/status_attachments/' . date('Y/m'), 'public');
        }

        $this->LayananStatusService->update($layanan->layanan_id, $status, $data);

        if (in_array($status, ['proses', 'batal'])) {
            return redirect()->back()->with('success', 'Status berhasil diperbarui.');
        }

        return jsonSuccess('Status berhasil diperbarui.');
    }
}
