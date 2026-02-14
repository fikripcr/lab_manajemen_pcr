<?php
namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lab\SoftwareRequestStoreRequest;
use App\Http\Requests\Lab\SoftwareRequestUpdateRequest;
use App\Models\Lab\MataKuliah;
use App\Models\Lab\PeriodSoftRequest;
use App\Services\Lab\SoftwareRequestService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SoftwareRequestController extends Controller
{
    protected $SoftwareRequestService;

    public function __construct(SoftwareRequestService $SoftwareRequestService)
    {
        $this->SoftwareRequestService = $SoftwareRequestService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('pages.lab.software-requests.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $activePeriod = PeriodSoftRequest::where('is_active', true)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->first();

        if (! $activePeriod) {
            // If no active period, maybe show all or show error?
            // User request suggests it should be scheduled.
            // For now, let's get courses from the latest active semester if no specific software period found,
            // or just notify that no active period is open.
            return view('pages.lab.software-requests.create', [
                'mataKuliahs'  => collect(),
                'activePeriod' => null,
                'error'        => 'Tidak ada periode pengajuan software yang aktif saat ini.',
            ]);
        }

        // Filter Mata Kuliah based on the semester in the active period
        $mataKuliahs = MataKuliah::whereHas('jadwals', function ($q) use ($activePeriod) {
            $q->where('semester_id', $activePeriod->semester_id);
        })->get();

        return view('pages.lab.software-requests.create', compact('mataKuliahs', 'activePeriod'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SoftwareRequestStoreRequest $request)
    {
        try {
            $this->SoftwareRequestService->createRequest($request->validated());

            return jsonSuccess('Permintaan software berhasil dibuat.', route('lab.software-requests.index'));
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function paginate(Request $request)
    {
        $softwareRequests = $this->SoftwareRequestService->getFilteredQuery($request->all());

        return DataTables::of($softwareRequests)
            ->addIndexColumn()
            ->editColumn('status', function ($request) {
                $badgeClass = '';
                switch ($request->status) {
                    case 'menunggu_approval':
                        $badgeClass = 'bg-label-warning';
                        break;
                    case 'disetujui':
                        $badgeClass = 'bg-label-success';
                        break;
                    case 'ditolak':
                        $badgeClass = 'bg-label-danger';
                        break;
                    default:
                        $badgeClass = 'bg-label-secondary';
                }
                return '<span class="badge ' . $badgeClass . '">' . ucfirst(str_replace('_', ' ', $request->status)) . '</span>';
            })
            ->editColumn('mata_kuliah', function ($request) {
                $mataKuliahNames = $request->mataKuliahs->map(function ($mk) {
                    return $mk->kode_mk . ' - ' . $mk->nama_mk;
                })->join(', ');

                return $mataKuliahNames ?: 'Tidak ada';
            })
            ->addColumn('dosen_name', function ($request) {
                return $request->dosen ? $request->dosen->name : ($request->dosen_name ?: 'Guest');
            })
            ->editColumn('created_at', function ($request) {
                return formatTanggalIndo($request->created_at);
            })
            ->addColumn('action', function ($request) {
                return view('components.tabler.datatables-actions', [
                    'editUrl' => route('lab.software-requests.edit', $request->id),
                    'viewUrl' => route('lab.software-requests.show', $request->id),
                    // deleteUrl omitted as SoftwareRequestController doesn't have destroy method in preview
                ])->render();
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $softwareRequest = $this->SoftwareRequestService->getRequestById($id);

        if (! $softwareRequest) {
            abort(404);
        }

        return view('pages.lab.software-requests.show', compact('softwareRequest'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $softwareRequest = $this->SoftwareRequestService->getRequestById($id);

        if (! $softwareRequest) {
            abort(404);
        }

        $activePeriod = $softwareRequest->period ?? PeriodSoftRequest::where('is_active', true)->first();

        if ($activePeriod) {
            $mataKuliahs = MataKuliah::whereHas('jadwals', function ($q) use ($activePeriod) {
                $q->where('semester_id', $activePeriod->semester_id);
            })->get();
        } else {
            $mataKuliahs = MataKuliah::all();
        }

        return view('pages.lab.software-requests.edit', compact('softwareRequest', 'mataKuliahs', 'activePeriod'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SoftwareRequestUpdateRequest $request, $id)
    {
        try {
            $this->SoftwareRequestService->updateRequest($id, $request->validated());

            return jsonSuccess('Status permintaan software berhasil diperbarui.', route('lab.software-requests.index'));
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }
}
