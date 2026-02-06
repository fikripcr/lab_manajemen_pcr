<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SoftwareRequestUpdateRequest;
use App\Models\MataKuliah;
use App\Models\RequestSoftware;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SoftwareRequestController extends Controller
{
    public function __construct()
    {
        // $this->middleware(['permission:manage-software-requests']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('pages.admin.software-requests.index');
    }

    public function paginate(Request $request)
    {
        $softwareRequests = RequestSoftware::with(['dosen', 'mataKuliahs'])->select('*');

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
                    'editUrl' => route('software-requests.edit', $request->id),
                    'viewUrl' => route('software-requests.show', $request->id),
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
        $softwareRequest = RequestSoftware::with(['dosen', 'mataKuliahs'])->findOrFail($id);
        return view('pages.admin.software-requests.show', compact('softwareRequest'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $softwareRequest = RequestSoftware::with(['dosen', 'mataKuliahs'])->findOrFail($id);
        $mataKuliahs     = MataKuliah::all();
        return view('pages.admin.software-requests.edit', compact('softwareRequest', 'mataKuliahs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SoftwareRequestUpdateRequest $request, $id)
    {
        $softwareRequest = RequestSoftware::findOrFail($id);

        $softwareRequest->update($request->validated());

        return redirect()->route('software-requests.index')
            ->with('success', 'Status permintaan software berhasil diperbarui.');
    }
}
