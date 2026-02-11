<?php
namespace App\Http\Controllers\Eoffice;

use App\Http\Controllers\Controller;
use App\Services\Eoffice\LayananStatusService;
use Illuminate\Http\Request;

class LayananStatusController extends Controller
{
    protected $service;

    public function __construct(LayananStatusService $service)
    {
        $this->service = $service;
    }

    /**
     * Update the status of a layanan (disposition workflow).
     */
    public function update(Request $request, $id, $status = null)
    {
        // Quick actions (proses/batal) don't need full validation
        if (! in_array($status, ['proses', 'batal'])) {
            $request->validate([
                'status_layanan' => 'required|string',
                'keterangan'     => 'nullable|string',
                'file_lampiran'  => 'nullable|file|mimes:pdf,docx,zip,jpg,png|max:5120',
            ]);
        }

        $layananId = decryptId($id);
        $data      = $request->only(['status_layanan', 'keterangan', 'disposisi_seq']);

        if ($request->hasFile('file_lampiran')) {
            $data['file_lampiran'] = $request->file('file_lampiran')
                ->store('eoffice/status_attachments/' . date('Y/m'), 'public');
        }

        try {
            $this->service->update($layananId, $status, $data);

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
