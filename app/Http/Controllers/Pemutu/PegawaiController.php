<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\PegawaiImportRequest;
use App\Http\Requests\Pemutu\PegawaiRequest;
use App\Models\Pemutu\OrgUnit;
use App\Services\Pemutu\PegawaiService;
use Exception;
use Yajra\DataTables\DataTables;

class PegawaiController extends Controller
{
    protected $PegawaiService;

    public function __construct(PegawaiService $PegawaiService)
    {
        $this->PegawaiService = $PegawaiService;
    }

    public function index()
    {
        return view('pages.pemutu.pegawai.index');
    }

    public function paginate()
    {
        $query = $this->PegawaiService->getFilteredQuery();

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
                    'editUrl'   => route('pemutu.pegawai.edit', $row->pegawai_id),
                    'editModal' => true,
                    'deleteUrl' => route('pemutu.pegawai.destroy', $row->pegawai_id),
                ])->render();
            })
            ->rawColumns(['user_id', 'action'])
            ->make(true);
    }

    public function create()
    {
        $units = OrgUnit::orderBy('name')->get();
        return view('pages.pemutu.pegawai.create', compact('units'));
    }

    public function store(PegawaiRequest $request)
    {
        try {
            $this->PegawaiService->createPegawai($request->validated());

            return jsonSuccess('Pegawai created successfully.');
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function edit($id)
    {
        $pegawai = $this->PegawaiService->getPegawaiById($id);
        if (! $pegawai) {
            abort(404);
        }

        $units = OrgUnit::orderBy('name')->get();
        return view('pages.pemutu.pegawai.edit', compact('pegawai', 'units'));
    }

    public function update(PegawaiRequest $request, $id)
    {
        try {
            $this->PegawaiService->updatePegawai($id, $request->validated());

            return jsonSuccess('Pegawai updated successfully.');
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->PegawaiService->deletePegawai($id);

            return jsonSuccess('Pegawai deleted successfully.');
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function import(PegawaiImportRequest $request)
    {
        if ($request->isMethod('get')) {
            return view('pages.pemutu.pegawai.import');
        }

        try {
            $this->PegawaiService->importPegawai($request->file('file'));

            return jsonSuccess('Pegawai imported successfully.', route('pemutu.pegawai.index'));
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }
}
