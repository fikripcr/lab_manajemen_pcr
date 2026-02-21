<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\LabelTypeRequest;
use App\Services\Pemutu\LabelService;
use Exception;

class LabelTypeController extends Controller
{
    public function __construct(protected LabelService $labelService)
    {}

    public function create()
    {
        return view('pages.pemutu.label-types.create-edit-ajax');
    }

    public function store(LabelTypeRequest $request)
    {
        try {
            $this->labelService->createLabelType($request->validated());

            logActivity('pemutu', "Menambah jenis label baru: " . ($request->name ?? ''));

            return jsonSuccess('Label Type created successfully.', route('pemutu.labels.index'));
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal menyimpan jenis label: ' . $e->getMessage());
        }
    }

    public function edit(LabelType $labelType)
    {
        return view('pages.pemutu.label-types.create-edit-ajax', compact('labelType'));
    }

    public function update(LabelTypeRequest $request, LabelType $labelType)
    {
        try {
            $this->labelService->updateLabelType($labelType->labeltype_id, $request->validated());

            logActivity('pemutu', "Memperbarui jenis label: {$labelType->name}");

            return jsonSuccess('Label Type updated successfully.', route('pemutu.labels.index'));
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal memperbarui jenis label: ' . $e->getMessage());
        }
    }

    public function destroy(LabelType $labelType)
    {
        try {
            $typeName = $labelType->name;
            $this->labelService->deleteLabelType($labelType->labeltype_id);

            logActivity('pemutu', "Menghapus jenis label: {$typeName}");

            return jsonSuccess('Label Type deleted successfully.', route('pemutu.labels.index'));
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal menghapus jenis label: ' . $e->getMessage());
        }
    }
}
