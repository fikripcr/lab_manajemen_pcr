<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\StatusAktifitasRequest;
use App\Models\Hr\StatusAktifitas;
use App\Services\Hr\StatusAktifitasService;
use Exception;
use Yajra\DataTables\Facades\DataTables;

class StatusAktifitasController extends Controller
{
    protected $StatusAktifitasService;

    public function __construct(StatusAktifitasService $StatusAktifitasService)
    {
        $this->StatusAktifitasService = $StatusAktifitasService;
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
        return view('pages.hr.status-aktifitas.create');
    }

    public function store(StatusAktifitasRequest $request)
    {
        try {
            $this->StatusAktifitasService->create($request->validated());
            return jsonSuccess('Status Aktifitas created successfully.');
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function edit(StatusAktifitas $status_aktifita)
    {
        $statusAktifitas = $status_aktifita;
        return view('pages.hr.status-aktifitas.edit', compact('statusAktifitas'));
    }

    public function update(StatusAktifitasRequest $request, StatusAktifitas $status_aktifita)
    {
        try {
            $this->StatusAktifitasService->update($status_aktifita, $request->validated());
            return jsonSuccess('Status Aktifitas updated successfully.');
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function destroy(StatusAktifitas $status_aktifita)
    {
        try {
            $status_aktifita->delete();
            return jsonSuccess('Status Aktifitas deleted successfully.');
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }
}
