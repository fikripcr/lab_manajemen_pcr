<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\JabatanFungsionalRequest;
use App\Models\Hr\JabatanFungsional;
use App\Services\Hr\JabatanFungsionalService;
use Yajra\DataTables\Facades\DataTables;

class JabatanFungsionalController extends Controller
{
    public function __construct(protected JabatanFungsionalService $jabatanFungsionalService)
    {}

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
        $jabatanFungsional = new JabatanFungsional();
        return view('pages.hr.jabatan-fungsional.create-edit-ajax', compact('jabatanFungsional'));
    }

    public function store(JabatanFungsionalRequest $request)
    {
        $this->jabatanFungsionalService->create($request->validated());
        return jsonSuccess('Jabatan Fungsional created successfully.');
    }

    public function edit(JabatanFungsional $jabatanFungsional)
    {
        return view('pages.hr.jabatan-fungsional.create-edit-ajax', compact('jabatanFungsional'));
    }

    public function update(JabatanFungsionalRequest $request, JabatanFungsional $jabatanFungsional)
    {
        $this->jabatanFungsionalService->update($jabatanFungsional, $request->validated());
        return jsonSuccess('Jabatan Fungsional updated successfully.');
    }

    public function destroy(JabatanFungsional $jabatanFungsional)
    {
        $jabatanFungsional->delete();
        return jsonSuccess('Jabatan Fungsional deleted successfully.');
    }
}
