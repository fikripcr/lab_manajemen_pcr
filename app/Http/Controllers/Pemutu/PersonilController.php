<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\PersonilImportRequest;
use App\Http\Requests\Pemutu\PersonilRequest;
use App\Models\Pemutu\OrgUnit;
use App\Services\Pemutu\PersonilService; // Import Service
use Yajra\DataTables\DataTables;

class PersonilController extends Controller
{
    protected $PersonilService;

    public function __construct(PersonilService $PersonilService)
    {
        $this->PersonilService = $PersonilService;
    }

    public function index()
    {
        return view('pages.pemutu.personils.index');
    }

    public function paginate()
    {
        $query = $this->PersonilService->getFilteredQuery();

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('org_unit_id', function ($row) {
                return $row->orgUnit ? $row->orgUnit->name : '-';
            })
            ->editColumn('user_id', function ($row) {
                return $row->user ? '<span class="badge bg-success-lt">Linked</span>' : '<span class="badge bg-secondary-lt">Unlinked</span>';
            })
            ->addColumn('action', function ($row) {
                return view('components.tabler.datatables-actions', [
                    'editUrl'   => route('pemutu.personils.edit', $row->personil_id),
                    'editModal' => true,
                    'deleteUrl' => route('pemutu.personils.destroy', $row->personil_id),
                ])->render();
            })
            ->rawColumns(['user_id', 'action'])
            ->make(true);
    }

    public function create()
    {
        $units = OrgUnit::orderBy('name')->get();
        return view('pages.pemutu.personils.create', compact('units'));
    }

    public function store(PersonilRequest $request)
    {
        try {
            $this->PersonilService->createPersonil($request->validated());

            return jsonSuccess('Personil created successfully.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function edit($id)
    {
        $personil = $this->PersonilService->getPersonilById($id);
        if (! $personil) {
            abort(404);
        }

        $units = OrgUnit::orderBy('name')->get();
        return view('pages.pemutu.personils.edit', compact('personil', 'units'));
    }

    public function update(PersonilRequest $request, $id)
    {
        try {
            $this->PersonilService->updatePersonil($id, $request->validated());

            return jsonSuccess('Personil updated successfully.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->PersonilService->deletePersonil($id);

            return jsonSuccess('Personil deleted successfully.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function import(PersonilImportRequest $request)
    {
        if ($request->isMethod('get')) {
            return view('pages.pemutu.personils.import');
        }

        try {
            $this->PersonilService->importPersonils($request->file('file'));

            return jsonSuccess('Personils imported successfully.', route('pemutu.personils.index'));
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }
}
