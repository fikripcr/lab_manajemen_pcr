<?php
namespace App\Http\Controllers\Eoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Eoffice\LayananDiskusiStoreRequest;
use App\Services\Eoffice\LayananDiskusiService;

class LayananDiskusiController extends Controller
{
    protected $LayananDiskusiService;

    public function __construct(LayananDiskusiService $LayananDiskusiService)
    {
        $this->LayananDiskusiService = $LayananDiskusiService;
    }

    /**
     * Store a new discussion message.
     */
    public function store(LayananDiskusiStoreRequest $request)
    {
        try {
            $validated = $request->validated();
            $layananId = decryptId($validated['layanan_id']);

            if ($request->hasFile('file_lampiran')) {
                $validated['file_lampiran'] = $request->file('file_lampiran')
                    ->store('eoffice/diskusi/' . date('Y/m'), 'public');
            }

            $this->LayananDiskusiService->store($layananId, $validated);
            return jsonSuccess('Diskusi berhasil dikirim.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }
}
