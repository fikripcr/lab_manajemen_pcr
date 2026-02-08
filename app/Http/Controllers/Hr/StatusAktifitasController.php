<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\StatusAktifitasRequest;
use App\Models\Hr\StatusAktifitas;
use App\Services\Hr\StatusAktifitasService;
use Yajra\DataTables\Facades\DataTables;

class StatusAktifitasController extends Controller
{
    protected $service;

    public function __construct(StatusAktifitasService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return view('pages.hr.status-aktifitas.index');
    }

    public function data()
    {
        $query = StatusAktifitas::query()->latest();

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('is_active', function ($row) {
                return $row->is_active ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Non-Aktif</span>';
            })
            ->addColumn('action', function ($row) {
                return view('components.tabler.datatables-actions', [
                    'editUrl'   => route('hr.status-aktifitas.edit', ['status_aktifita' => $row->statusaktifitas_id]),
                    'editModal' => true,
                    'deleteUrl' => route('hr.status-aktifitas.destroy', ['status_aktifita' => $row->statusaktifitas_id]),
                ])->render();
            })
            ->rawColumns(['is_active', 'action'])
            ->make(true);
    }

    public function create()
    {
        return view('pages.hr.status-aktifitas.create');
    }

    public function store(StatusAktifitasRequest $request)
    {
        try {
            $this->service->create($request->validated());
            return jsonSuccess('Status Aktifitas created successfully.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function edit($id)
    {
        $statusAktifitas = StatusAktifitas::findOrFail($id);
        return view('pages.hr.status-aktifitas.edit', compact('statusAktifitas'));
    }

    public function update(StatusAktifitasRequest $request, $id)
    {
        try {
            $statusAktifitas = StatusAktifitas::findOrFail($id);
            $this->service->update($statusAktifitas, $request->validated());
            return jsonSuccess('Status Aktifitas updated successfully.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->service->delete($id);
            return jsonSuccess('Status Aktifitas deleted successfully.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }
}
