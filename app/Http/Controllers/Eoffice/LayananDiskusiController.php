<?php
namespace App\Http\Controllers\Eoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Eoffice\LayananDiskusiStoreRequest;
use App\Services\Eoffice\LayananDiskusiService;
use Exception;

class LayananDiskusiController extends Controller
{
    public function __construct(protected LayananDiskusiService $LayananDiskusiService)
    {}

    /**
     * Store a new discussion message.
     */
    public function store(LayananDiskusiStoreRequest $request)
    {
        try {
            $validated = $request->validated();

            if ($request->hasFile('file_lampiran')) {
                $validated['file_lampiran'] = $request->file('file_lampiran')
                    ->store('eoffice/diskusi/' . date('Y/m'), 'public');
            }

            $this->LayananDiskusiService->store($validated);
            return jsonSuccess('Diskusi berhasil dikirim.');
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }
}
