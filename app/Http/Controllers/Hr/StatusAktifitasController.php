<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\StatusAktifitasRequest;
use App\Models\Hr\StatusAktifitas;
use App\Services\Hr\StatusAktifitasService;
use Yajra\DataTables\Facades\DataTables;

class StatusAktifitasController extends Controller
{
    public function __construct(protected StatusAktifitasService $statusAktifitasService)
    {}

    public function index()
    {
        return view('pages.hr.status-aktifitas.index');
    }

    public function data()
    {
        $query = StatusAktifitas::query()->latest();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('kode_status', function ($row) {
                return $row->kode_status ?? '-';
            })
            ->addColumn('nama_status', function ($row) {
                return $row->nama_status;
            })
            ->editColumn('is_active', function ($row) {
                return $row->is_active ? '<span class="badge bg-success text-white">Aktif</span>' : '<span class="badge bg-danger">Non-Aktif</span>';
            })
            ->addColumn('action', function ($row) {
                return view('components.tabler.datatables-actions', [
                    'editUrl'   => route('hr.status-aktifitas.edit', ['status_aktifita' => $row->hashid]),
                    'editModal' => true,
                    'deleteUrl' => route('hr.status-aktifitas.destroy', ['status_aktifita' => $row->hashid]),
                ])->render();
            })
            ->rawColumns(['is_active', 'action'])
            ->make(true);
    }

    public function create()
    {
        return view('pages.hr.status-aktifitas.create-edit-ajax');
    }

    public function store(StatusAktifitasRequest $request)
    {
        $this->statusAktifitasService->create($request->validated());
        return jsonSuccess('Status Aktifitas created successfully.');
    }

    public function edit(StatusAktifitas $statusAktifitas)
    {
        return view('pages.hr.status-aktifitas.create-edit-ajax', compact('statusAktifitas'));
    }

    public function update(StatusAktifitasRequest $request, StatusAktifitas $statusAktifitas)
    {
        $this->statusAktifitasService->update($statusAktifitas, $request->validated());
        return jsonSuccess('Status Aktifitas updated successfully.');
    }

    public function destroy(StatusAktifitas $statusAktifitas)
    {
        $statusAktifitas->delete();
        return jsonSuccess('Status Aktifitas deleted successfully.');
    }
}
