<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\StatusPegawaiRequest;
use App\Models\Hr\StatusPegawai;
use App\Services\Hr\StatusPegawaiService;
use Yajra\DataTables\Facades\DataTables;

class StatusPegawaiController extends Controller
{
    protected $service;

    public function __construct(StatusPegawaiService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return view('pages.hr.status-pegawai.index');
    }

    public function data()
    {
        $query = StatusPegawai::query()->latest();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('kode_status', function ($row) {
                return $row->kode_status ?? '-';
            })
            ->addColumn('status', function ($row) {
                return $row->nama_status;
            })
            ->editColumn('is_active', function ($row) {
                return $row->is_active ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Non-Aktif</span>';
            })
            ->addColumn('action', function ($row) {
                return view('components.tabler.datatables-actions', [
                    'editUrl'   => route('hr.status-pegawai.edit', ['status_pegawai' => $row->hashid]),
                    'editModal' => true,
                    'deleteUrl' => route('hr.status-pegawai.destroy', ['status_pegawai' => $row->hashid]),
                ])->render();
            })
            ->rawColumns(['is_active', 'action'])
            ->make(true);
    }

    public function create()
    {
        return view('pages.hr.status-pegawai.create');
    }

    public function store(StatusPegawaiRequest $request)
    {
        try {
            $this->service->create($request->validated());
            return jsonSuccess('Status Pegawai created successfully.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function edit(StatusPegawai $status_pegawai)
    {
        $statusPegawai = $status_pegawai;
        return view('pages.hr.status-pegawai.edit', compact('statusPegawai'));
    }

    public function update(StatusPegawaiRequest $request, StatusPegawai $status_pegawai)
    {
        try {
            $this->service->update($status_pegawai, $request->validated());
            return jsonSuccess('Status Pegawai updated successfully.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function destroy(StatusPegawai $status_pegawai)
    {
        try {
            $status_pegawai->delete();
            return jsonSuccess('Status Pegawai deleted successfully.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }
}
