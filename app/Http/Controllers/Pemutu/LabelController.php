<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\LabelRequest;
use App\Services\Pemutu\LabelService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class LabelController extends Controller
{
    protected $LabelService;

    public function __construct(LabelService $LabelService)
    {
        $this->LabelService = $LabelService;
    }

    public function index()
    {
        $types = $this->LabelService->getAllLabelTypes();
        return view('pages.pemutu.labels.index', compact('types'));
    }

    public function paginate(Request $request)
    {
        $query = $this->LabelService->getLabelFilteredQuery($request->all());

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
                    'editUrl'   => route('pemutu.labels.edit', $row->label_id),
                    'editModal' => true,
                    'deleteUrl' => route('pemutu.labels.destroy', $row->label_id),
                ])->render();
            })
            ->rawColumns(['name', 'action'])
            ->make(true);
    }

    public function create()
    {
        $types = $this->LabelService->getAllLabelTypes();
        return view('pages.pemutu.labels.create', compact('types'));
    }

    public function store(LabelRequest $request)
    {
        try {
            $this->LabelService->createLabel($request->validated());

            return jsonSuccess('Label created successfully.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function edit($id)
    {
        $label = $this->LabelService->getLabelById($id);
        if (! $label) {
            abort(404);
        }

        $types = $this->LabelService->getAllLabelTypes();

        return view('pages.pemutu.labels.edit', compact('label', 'types'));
    }

    public function update(LabelRequest $request, $id)
    {
        try {
            $this->LabelService->updateLabel($id, $request->validated());

            return jsonSuccess('Label updated successfully.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->LabelService->deleteLabel($id);

            return jsonSuccess('Label deleted successfully.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }
}
