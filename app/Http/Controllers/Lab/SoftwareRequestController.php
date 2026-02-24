<?php
namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lab\SoftwareRequest;
use App\Models\Lab\MataKuliah;
use App\Models\Lab\PeriodSoftRequest;
use App\Models\Lab\RequestSoftware;
use App\Services\Lab\SoftwareRequestService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SoftwareRequestController extends Controller
{
    public function __construct(protected SoftwareRequestService $softwareRequestService)
    {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return \view('pages.lab.software-requests.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $activePeriod = PeriodSoftRequest::where('is_active', true)
            ->whereDate('start_date', '<=', \now())
            ->whereDate('end_date', '>=', \now())
            ->first();

        if (! $activePeriod) {
            // If no active period, maybe show all or show error?
            // User request suggests it should be scheduled.
            // For now, let's get courses from the latest active semester if no specific software period found,
            // or just notify that no active period is open.
            return \view('pages.lab.software-requests.create-edit-ajax', [
                'softwareRequest' => new RequestSoftware(),
                'mataKuliahs'  => \collect(),
                'activePeriod' => null,
                'error'        => 'Tidak ada periode pengajuan software yang aktif saat ini.',
            ]);
        }

        // Filter Mata Kuliah based on the semester in the active period
        $mataKuliahs = MataKuliah::whereHas('jadwals', function ($q) use ($activePeriod) {
            $q->where('semester_id', $activePeriod->semester_id);
        })->get();

        $softwareRequest = new RequestSoftware();

        return \view('pages.lab.software-requests.create-edit-ajax', compact('mataKuliahs', 'activePeriod', 'softwareRequest'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SoftwareRequest $request)
    {
        try {
            $this->softwareRequestService->createRequest($request->validated());

            return jsonSuccess('Permintaan software berhasil dibuat.', \route('lab.software-requests.index'));
        } catch (\Exception $e) {
            logError($e);
            return jsonError('Gagal membuat permintaan software: ' . $e->getMessage());
        }
    }

    public function paginate(Request $request)
    {
        $softwareRequests = $this->softwareRequestService->getFilteredQuery($request->all());

        return DataTables::of($softwareRequests)
            ->addIndexColumn()
            ->editColumn('status', function ($request) {
                $status     = $request->status;
                $badgeClass = 'bg-label-secondary';

                // Handle both Indonesian (legacy) and English (new) statuses
                switch ($status) {
                    case 'menunggu_approval':
                    case 'pending':
                        $badgeClass = 'bg-label-warning';
                        $statusText = 'Pending';
                        break;
                    case 'tangguhkan':
                        $badgeClass = 'bg-label-info';
                        $statusText = 'Tangguhkan';
                        break;
                    case 'disetujui':
                    case 'approved':
                        $badgeClass = 'bg-label-success';
                        $statusText = 'Approved';
                        break;
                    case 'ditolak':
                    case 'rejected':
                        $badgeClass = 'bg-label-danger';
                        $statusText = 'Rejected';
                        break;
                    default:
                        $statusText = ucfirst(str_replace('_', ' ', $status));
                }
                return '<span class="badge ' . $badgeClass . '">' . $statusText . '</span>';
            })
        // ... (rest of editColumn/addColumn logic same as before, just confirming context) ...
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
                return \view('components.tabler.datatables-actions', [
                    'editUrl'   => \route('lab.software-requests.edit', $request->id),
                    'editModal' => true,
                    'viewUrl'   => \route('lab.software-requests.show', $request->id),
                ])->render();
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    /**
     * Approve Request
     */
    public function approve(Request $request, RequestSoftware $requestSoftware)
    {
        $validated = $request->validate([
            'status'     => 'required|in:approved,rejected,tangguhkan',
            'pejabat'    => 'required|string|max:191',
            'keterangan' => 'nullable|string',
        ]);

        try {
            $this->softwareRequestService->approveRequest($requestSoftware, $validated);

            return jsonSuccess('Status permintaan software berhasil diperbarui.');
        } catch (\Exception $e) {
            logError($e);
            return jsonError('Gagal memperbarui status: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(RequestSoftware $id)
    {
        $id->load(['latestApproval', 'dosen', 'mataKuliahs', 'approvals' => function ($q) {
            $q->orderBy('created_at', 'desc');
        }]);
        $requestSoftware = $id;
        return \view('pages.lab.software-requests.show', compact('requestSoftware'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RequestSoftware $requestSoftware)
    {
        $activePeriod = $requestSoftware->period ?? PeriodSoftRequest::where('is_active', true)->first();

        if ($activePeriod) {
            $mataKuliahs = MataKuliah::whereHas('jadwals', function ($q) use ($activePeriod) {
                $q->where('semester_id', $activePeriod->semester_id);
            })->get();
        } else {
            $mataKuliahs = MataKuliah::all();
        }

        return \view('pages.lab.software-requests.create-edit-ajax', [
            'softwareRequest' => $requestSoftware,
            'mataKuliahs'     => $mataKuliahs,
            'activePeriod'    => $activePeriod,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SoftwareRequest $request, RequestSoftware $requestSoftware)
    {
        try {
            $this->softwareRequestService->updateRequest($requestSoftware, $request->validated());

            return jsonSuccess('Status permintaan software berhasil diperbarui.', \route('lab.software-requests.index'));
        } catch (\Exception $e) {
            logError($e);
            return jsonError('Gagal memperbarui permintaan software: ' . $e->getMessage());
        }
    }
}
