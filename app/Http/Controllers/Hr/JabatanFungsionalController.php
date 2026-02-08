<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\JabatanFungsionalRequest;
use App\Models\Hr\JabatanFungsional;
use App\Services\Hr\JabatanFungsionalService;
use Yajra\DataTables\Facades\DataTables;

class JabatanFungsionalController extends Controller
{
    protected $service;

    public function __construct(JabatanFungsionalService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return view('pages.hr.jabatan-fungsional.index');
    }

    public function data()
    {
        $query = JabatanFungsional::query()->latest();

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('is_active', function ($row) {
                return $row->is_active ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Non-Aktif</span>';
            })
            ->addColumn('action', function ($row) {
                return view('components.tabler.datatables-actions', [
                    'editUrl'   => route('hr.jabatan-fungsional.edit', ['jabatan_fungsional' => $row->jabfungsional_id]),
                    'editModal' => true,
                    'deleteUrl' => route('hr.jabatan-fungsional.destroy', ['jabatan_fungsional' => $row->jabfungsional_id]),
                ])->render();
            })
            ->rawColumns(['is_active', 'action'])
            ->make(true);
    }

    public function create()
    {
        return view('pages.hr.jabatan-fungsional.create');
    }

    public function store(JabatanFungsionalRequest $request)
    {
        try {
            $this->service->create($request->validated());
            return jsonSuccess('Jabatan Fungsional created successfully.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function edit($id)
    {
        $jabatanFungsional = JabatanFungsional::findOrFail($id);
        return view('pages.hr.jabatan-fungsional.edit', compact('jabatanFungsional'));
    }

    public function update(JabatanFungsionalRequest $request, $id)
    {
        try {
            $jabatanFungsional = JabatanFungsional::findOrFail($id);
            $this->service->update($jabatanFungsional, $request->validated());
            return jsonSuccess('Jabatan Fungsional updated successfully.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->service->delete($id);
            return jsonSuccess('Jabatan Fungsional deleted successfully.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }
}
