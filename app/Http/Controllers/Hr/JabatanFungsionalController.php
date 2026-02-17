<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\JabatanFungsionalRequest;
use App\Models\Hr\JabatanFungsional;
use App\Services\Hr\JabatanFungsionalService;
use Exception;
use Yajra\DataTables\Facades\DataTables;

class JabatanFungsionalController extends Controller
{
    protected $JabatanFungsionalService;

    public function __construct(JabatanFungsionalService $JabatanFungsionalService)
    {
        $this->JabatanFungsionalService = $JabatanFungsionalService;
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
                return $row->is_active ? '<span class="badge bg-success text-white">Aktif</span>' : '<span class="badge bg-danger">Non-Aktif</span>';
            })
            ->editColumn('tunjangan', function ($row) {
                return 'Rp ' . number_format($row->tunjangan, 0, ',', '.');
            })
            ->addColumn('action', function ($row) {
                return view('components.tabler.datatables-actions', [
                    'editUrl'   => route('hr.jabatan-fungsional.edit', ['jabatan_fungsional' => $row->hashid]),
                    'editModal' => true,
                    'deleteUrl' => route('hr.jabatan-fungsional.destroy', ['jabatan_fungsional' => $row->hashid]),
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
            $this->JabatanFungsionalService->create($request->validated());
            return jsonSuccess('Jabatan Fungsional created successfully.');
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function edit(JabatanFungsional $jabatan_fungsional)
    {
        $jabatanFungsional = $jabatan_fungsional;
        return view('pages.hr.jabatan-fungsional.edit', compact('jabatanFungsional'));
    }

    public function update(JabatanFungsionalRequest $request, JabatanFungsional $jabatan_fungsional)
    {
        try {
            $this->JabatanFungsionalService->update($jabatan_fungsional, $request->validated());
            return jsonSuccess('Jabatan Fungsional updated successfully.');
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function destroy(JabatanFungsional $jabatan_fungsional)
    {
        try {
            $jabatan_fungsional->delete();
            return jsonSuccess('Jabatan Fungsional deleted successfully.');
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }
}
