<?php
namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lab\SoftwareRequestUpdateRequest;
use App\Models\Lab\MataKuliah;
use App\Services\Lab\SoftwareRequestService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SoftwareRequestController extends Controller
{
    protected $softwareRequestService;

    public function __construct(SoftwareRequestService $softwareRequestService)
    {
        $this->softwareRequestService = $softwareRequestService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('pages.lab.software-requests.index');
    }

    public function paginate(Request $request)
    {
        $softwareRequests = $this->softwareRequestService->getFilteredQuery($request->all());

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
        $softwareRequest = $this->softwareRequestService->getRequestById($id);

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
        $softwareRequest = $this->softwareRequestService->getRequestById($id);

        if (! $softwareRequest) {
            abort(404);
        }

        $mataKuliahs = MataKuliah::all();
        return view('pages.lab.software-requests.edit', compact('softwareRequest', 'mataKuliahs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SoftwareRequestUpdateRequest $request, $id)
    {
        try {
            $this->softwareRequestService->updateRequest($id, $request->validated());

            return jsonSuccess('Status permintaan software berhasil diperbarui.', route('lab.software-requests.index'));
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }
}
