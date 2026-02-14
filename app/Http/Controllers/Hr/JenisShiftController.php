<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\JenisShiftRequest;
use App\Models\Hr\JenisShift;
use App\Services\Hr\JenisShiftService;
use Exception;
use Yajra\DataTables\Facades\DataTables;

class JenisShiftController extends Controller
{
    protected $JenisShiftService;

    public function __construct(JenisShiftService $JenisShiftService)
    {
        $this->JenisShiftService = $JenisShiftService;
    }

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
                return $row->is_active ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Non-Aktif</span>';
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
        return view('pages.hr.jenis-shift.create');
    }

    public function store(JenisShiftRequest $request)
    {
        try {
            $this->JenisShiftService->create($request->validated());
            return jsonSuccess('Jenis Shift created successfully.');
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function edit(JenisShift $jenis_shift)
    {
        $jenisShift = $jenis_shift;
        return view('pages.hr.jenis-shift.edit', compact('jenisShift'));
    }

    public function update(JenisShiftRequest $request, JenisShift $jenis_shift)
    {
        try {
            $this->JenisShiftService->update($jenis_shift, $request->validated());
            return jsonSuccess('Jenis Shift updated successfully.');
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function destroy(JenisShift $jenis_shift)
    {
        try {
            $jenis_shift->delete();
            return jsonSuccess('Jenis Shift deleted successfully.');
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }
}
