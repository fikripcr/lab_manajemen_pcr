<?php
namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lab\PeriodSoftRequestRequest;
use App\Models\Lab\PeriodSoftRequest;
use App\Models\Lab\Semester;
use App\Services\Lab\PeriodSoftRequestService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PeriodSoftRequestController extends Controller
{
    public function __construct(protected PeriodSoftRequestService $periodSoftRequestService)
    {}

    public function index()
    {
        return view('pages.lab.periode-request.index');
    }

    public function data(Request $request)
    {
        $query = $this->periodSoftRequestService->getFilteredQuery($request->all());

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('semester', function ($row) {
                return $row->semester ? $row->semester->tahun_ajaran . ' - ' . $row->semester->semester : '-';
            })
            ->editColumn('is_active', function ($row) {
                return $row->is_active
                    ? '<span class="badge bg-success text-white">Active</span>'
                    : '<span class="badge bg-secondary text-white">Inactive</span>';
            })
            ->editColumn('date_range', function ($row) {
                return formatTanggalIndo($row->start_date) . ' - ' . formatTanggalIndo($row->end_date);
            })
            ->addColumn('action', function ($row) {
                return view('components.tabler.datatables-actions', [
                    'editUrl'   => route('lab.periode-request.edit', $row->encrypted_periodsoftreq_id),
                    'deleteUrl' => route('lab.periode-request.destroy', $row->encrypted_periodsoftreq_id),
                ])->render();
            })
            ->rawColumns(['is_active', 'action'])
            ->make(true);
    }

    public function create()
    {
        $semesters = Semester::all();
        $period    = new PeriodSoftRequest();
        return view('pages.lab.periode-request.create-edit-ajax', compact('semesters', 'period'));
    }

    public function store(PeriodSoftRequestRequest $request)
    {
        $this->periodSoftRequestService->createPeriod($request->validated());
        return jsonSuccess('Periode Request berhasil dibuat.', route('lab.periode-request.index'));
    }

    public function edit(PeriodSoftRequest $periodeRequest)
    {
        $period    = $periodeRequest;
        $semesters = Semester::all();
        return view('pages.lab.periode-request.create-edit-ajax', compact('period', 'semesters'));
    }

    public function update(PeriodSoftRequestRequest $request, PeriodSoftRequest $periodeRequest)
    {
        $this->periodSoftRequestService->updatePeriod($periodeRequest, $request->validated());
        return jsonSuccess('Periode Request berhasil diperbarui.', route('lab.periode-request.index'));
    }

    public function destroy(PeriodSoftRequest $periodeRequest)
    {
        $this->periodSoftRequestService->deletePeriod($periodeRequest);
        return jsonSuccess('Periode Request berhasil dihapus.', route('lab.periode-request.index'));
    }
}
