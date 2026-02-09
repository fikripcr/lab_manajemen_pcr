<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\LabelTypeRequest;
use App\Services\Pemutu\LabelService;

class LabelTypeController extends Controller
{
    protected $labelService;

    public function __construct(LabelService $labelService)
    {
        $this->labelService = $labelService;
    }

    public function create()
    {
        return view('pages.pemutu.label-types.create');
    }

    public function store(LabelTypeRequest $request)
    {
        try {
            $this->labelService->createLabelType($request->validated());

            return jsonSuccess('Label Type created successfully.', route('pemutu.labels.index'));
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function edit($id)
    {
        $labelType = $this->labelService->getLabelTypeById($id);
        if (! $labelType) {
            abort(404);
        }

        return view('pages.pemutu.label-types.edit', compact('labelType'));
    }

    public function update(LabelTypeRequest $request, $id)
    {
        try {
            $this->labelService->updateLabelType($id, $request->validated());

            return jsonSuccess('Label Type updated successfully.', route('pemutu.labels.index'));
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->labelService->deleteLabelType($id);

            return jsonSuccess('Label Type deleted successfully.', route('pemutu.labels.index'));
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }
}
