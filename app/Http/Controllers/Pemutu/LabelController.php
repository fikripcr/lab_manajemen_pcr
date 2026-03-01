<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\LabelRequest;
use App\Services\Pemutu\LabelService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class LabelController extends Controller
{
    public function __construct(protected LabelService $labelService)
    {}

    public function index()
    {
        $types = $this->labelService->getAllLabelTypes();
        return view('pages.pemutu.labels.index', compact('types'));
    }

    public function data(Request $request)
    {
        $query = $this->labelService->getLabelFilteredQuery($request->all());

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('name', function ($row) {
                $color = $row->type->color ?? 'blue';
                return '<span class="badge bg-' . $color . '-lt text-' . $color . '">' . e($row->name) . '</span>';
            })
            ->editColumn('description', function ($row) {
                return $row->description ?: '-';
            })
            ->editColumn('type_id', function ($row) {
                return $row->type ? $row->type->name : '-';
            })
            ->addColumn('action', function ($row) {
                return view('components.tabler.datatables-actions', [
                    'editUrl'   => route('pemutu.labels.edit', $row->encrypted_label_id),
                    'editModal' => true,
                    'deleteUrl' => route('pemutu.labels.destroy', $row->encrypted_label_id),
                ])->render();
            })
            ->rawColumns(['name', 'action'])
            ->make(true);
    }

    public function create()
    {
        $types = $this->labelService->getAllLabelTypes();
        return view('pages.pemutu.labels.create-edit-ajax', compact('types'));
    }

    public function store(LabelRequest $request)
    {
        $this->labelService->createLabel($request->validated());

        logActivity('pemutu', "Menambah label baru: " . ($request->name ?? ''));

        return jsonSuccess('Label created successfully.');
    }

    public function edit(\App\Models\Pemutu\Label $label)
    {
        $types = $this->labelService->getAllLabelTypes();

        return view('pages.pemutu.labels.create-edit-ajax', compact('label', 'types'));
    }

    public function update(LabelRequest $request, \App\Models\Pemutu\Label $label)
    {
        $this->labelService->updateLabel($label->label_id, $request->validated());

        logActivity('pemutu', "Memperbarui label: {$label->name}");

        return jsonSuccess('Label updated successfully.');
    }

    public function destroy(\App\Models\Pemutu\Label $label)
    {
        $labelName = $label->name;
        $this->labelService->deleteLabel($label->label_id);

        logActivity('pemutu', "Menghapus label: {$labelName}");

        return jsonSuccess('Label deleted successfully.');
    }
}
