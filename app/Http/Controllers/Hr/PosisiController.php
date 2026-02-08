<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\PosisiRequest;
use App\Models\Hr\Posisi;
use App\Services\Hr\PosisiService;
use Yajra\DataTables\Facades\DataTables;

class PosisiController extends Controller
{
    protected $service;

    public function __construct(PosisiService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return view('pages.hr.posisi.index');
    }

    public function data()
    {
        $query = Posisi::query()->latest();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return view('components.tabler.datatables-actions', [
                    'editUrl'   => route('hr.posisi.edit', $row->hashid),
                    'editModal' => true,
                    'deleteUrl' => route('hr.posisi.destroy', $row->hashid),
                ])->render();
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        return view('pages.hr.posisi.create');
    }

    public function store(PosisiRequest $request)
    {
        try {
            $this->service->create($request->validated());
            return jsonSuccess('Posisi created successfully.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function edit(Posisi $posisi)
    {
        return view('pages.hr.posisi.edit', compact('posisi'));
    }

    public function update(PosisiRequest $request, Posisi $posisi)
    {
        try {
            $this->service->update($posisi, $request->validated());
            return jsonSuccess('Posisi updated successfully.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function destroy(Posisi $posisi)
    {
        try {
            $posisi->delete();
            return jsonSuccess('Posisi deleted successfully.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }
}
