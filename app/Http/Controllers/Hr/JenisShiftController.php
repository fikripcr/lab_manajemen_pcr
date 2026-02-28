<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\JenisShiftRequest;
use App\Models\Hr\JenisShift;
use App\Services\Hr\JenisShiftService;
use Yajra\DataTables\Facades\DataTables;

class JenisShiftController extends Controller
{
    public function __construct(protected JenisShiftService $jenisShiftService)
    {}

    public function index()
    {
        return view('pages.hr.jenis-shift.index');
    }

    public function data()
    {
        $query = JenisShift::query()->latest();

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('is_active', function ($row) {
                return $row->is_active ? '<span class="badge bg-success text-white">Aktif</span>' : '<span class="badge bg-danger">Non-Aktif</span>';
            })
            ->addColumn('action', function ($row) {
                return view('components.tabler.datatables-actions', [
                    'editUrl'   => route('hr.jenis-shift.edit', ['jenis_shift' => $row->hashid]),
                    'editModal' => true,
                    'deleteUrl' => route('hr.jenis-shift.destroy', ['jenis_shift' => $row->hashid]),
                ])->render();
            })
            ->rawColumns(['is_active', 'action'])
            ->make(true);
    }

    public function create()
    {
        $jenisShift = new JenisShift();
        return view('pages.hr.jenis-shift.create-edit-ajax', compact('jenisShift'));
    }

    public function store(JenisShiftRequest $request)
    {
        $this->jenisShiftService->create($request->validated());
        return jsonSuccess('Jenis Shift created successfully.');
    }

    public function edit(JenisShift $jenisShift)
    {
        return view('pages.hr.jenis-shift.create-edit-ajax', compact('jenisShift'));
    }

    public function update(JenisShiftRequest $request, JenisShift $jenisShift)
    {
        $this->jenisShiftService->update($jenisShift, $request->validated());
        return jsonSuccess('Jenis Shift updated successfully.');
    }

    public function destroy(JenisShift $jenisShift)
    {
        $jenisShift->delete();
        return jsonSuccess('Jenis Shift deleted successfully.');
    }
}
