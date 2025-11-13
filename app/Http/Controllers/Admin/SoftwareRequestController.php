<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RequestSoftware;
use App\Models\MataKuliah;
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

    public function data(Request $request)
    {
        $softwareRequests = RequestSoftware::with(['dosen', 'mataKuliahs'])->select('*');

        return DataTables::of($softwareRequests)
            ->addIndexColumn()
            ->editColumn('status', function ($request) {
                $badgeClass = '';
                switch ($request->status) {
                    case 'menunggu_approval':
                        $badgeClass = 'bg-warning';
                        break;
                    case 'disetujui':
                        $badgeClass = 'bg-success';
                        break;
                    case 'ditolak':
                        $badgeClass = 'bg-danger';
                        break;
                    default:
                        $badgeClass = 'bg-secondary';
                }
                return '<span class="badge ' . $badgeClass . '">' . ucfirst(str_replace('_', ' ', $request->status)) . '</span>';
            })
            ->editColumn('mata_kuliah', function ($request) {
                $mataKuliahNames = $request->mataKuliahs->map(function ($mk) {
                    return $mk->kode . ' - ' . $mk->nama;
                })->join(', ');

                return $mataKuliahNames ?: 'Tidak ada';
            })
            ->addColumn('action', function ($request) {
                return '
                    <div class="d-flex">
                        <a href="' . route('software-requests.show', $request->id) . '" class="btn btn-info btn-sm me-1" title="View">
                            <i class="bx bx-show"></i>
                        </a>
                        <a href="' . route('software-requests.edit', $request->id) . '" class="btn btn-primary btn-sm me-1" title="Edit">
                            <i class="bx bx-edit"></i>
                        </a>
                    </div>';
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
        $mataKuliahs = MataKuliah::all();
        return view('pages.admin.software-requests.edit', compact('softwareRequest', 'mataKuliahs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $softwareRequest = RequestSoftware::findOrFail($id);

        $request->validate([
            'status' => 'required|in:menunggu_approval,disetujui,ditolak',
            'catatan_admin' => 'nullable|string',
        ]);

        $softwareRequest->update([
            'status' => $request->status,
            'catatan_admin' => $request->catatan_admin,
        ]);

        return redirect()->route('software-requests.index')
            ->with('success', 'Status permintaan software berhasil diperbarui.');
    }
}
