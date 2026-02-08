<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\DepartemenRequest;
use App\Models\Hr\Departemen;
use App\Services\Hr\DepartemenService;
use Yajra\DataTables\Facades\DataTables;

class DepartemenController extends Controller
{
    protected $service;

    public function __construct(DepartemenService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return view('pages.hr.departemen.index');
    }

    public function data()
    {
        $query = Departemen::query()->latest();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return view('components.tabler.datatables-actions', [
                    'editUrl'   => route('hr.departemen.edit', $row->hashid),
                    'editModal' => true,
                    'deleteUrl' => route('hr.departemen.destroy', $row->hashid),
                ])->render();
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        return view('pages.hr.departemen.create');
    }

    public function store(DepartemenRequest $request)
    {
        try {
            $this->service->create($request->validated());
            return jsonSuccess('Departemen created successfully.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function edit(Departemen $departemen)
    {
        return view('pages.hr.departemen.edit', compact('departemen'));
    }

    public function update(DepartemenRequest $request, Departemen $departemen)
    {
        try {
            $this->service->update($departemen, $request->validated());
            return jsonSuccess('Departemen updated successfully.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function destroy(Departemen $departemen)
    {
        try {
            $departemen->delete();
            return jsonSuccess('Departemen deleted successfully.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }
}
