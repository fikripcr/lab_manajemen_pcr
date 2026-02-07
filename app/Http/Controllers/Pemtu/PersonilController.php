<?php
namespace App\Http\Controllers\Pemtu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemtu\PersonilRequest;
use App\Models\Pemtu\OrgUnit;
use App\Services\Pemtu\PersonilService; // Import Service
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PersonilController extends Controller
{
    protected $personilService;

    public function __construct(PersonilService $personilService)
    {
        $this->personilService = $personilService;
    }

    public function index()
    {
        return view('pages.pemtu.personils.index');
    }

    public function paginate()
    {
        $query = $this->personilService->getFilteredQuery();

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
                    'editUrl'   => route('pemtu.personils.edit', $row->personil_id),
                    'editModal' => true,
                    'deleteUrl' => route('pemtu.personils.destroy', $row->personil_id),
                ])->render();
            })
            ->rawColumns(['user_id', 'action'])
            ->make(true);
    }

    public function create()
    {
        $units = OrgUnit::orderBy('name')->get();
        return view('pages.pemtu.personils.create', compact('units'));
    }

    public function store(PersonilRequest $request)
    {
        try {
            $this->personilService->createPersonil($request->validated());

            return jsonSuccess('Personil created successfully.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function edit($id)
    {
        $personil = $this->personilService->getPersonilById($id);
        if (! $personil) {
            abort(404);
        }

        $units = OrgUnit::orderBy('name')->get();
        return view('pages.pemtu.personils.edit', compact('personil', 'units'));
    }

    public function update(PersonilRequest $request, $id)
    {
        try {
            $this->personilService->updatePersonil($id, $request->validated());

            return jsonSuccess('Personil updated successfully.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->personilService->deletePersonil($id);

            return jsonSuccess('Personil deleted successfully.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function import(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('pages.pemtu.personils.import');
        }

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            $this->personilService->importPersonils($request->file('file'));

            return jsonSuccess('Personils imported successfully.', route('pemtu.personils.index'));
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }
}
