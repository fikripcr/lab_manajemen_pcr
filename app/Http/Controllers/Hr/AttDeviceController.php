<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\AttDeviceRequest;
use App\Models\Hr\AttDevice;
use App\Services\Hr\AttDeviceService;
use Yajra\DataTables\Facades\DataTables;

class AttDeviceController extends Controller
{
    protected $service;

    public function __construct(AttDeviceService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return view('pages.hr.att-device.index');
    }

    public function data()
    {
        $query = AttDevice::query()->latest();

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('is_active', function ($row) {
                return $row->is_active ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Non-Aktif</span>';
            })
            ->addColumn('action', function ($row) {
                return view('components.tabler.datatables-actions', [
                    'editUrl'   => route('hr.att-device.edit', ['att_device' => $row->att_device_id]),
                    'editModal' => true,
                    'deleteUrl' => route('hr.att-device.destroy', ['att_device' => $row->att_device_id]),
                ])->render();
            })
            ->rawColumns(['is_active', 'action'])
            ->make(true);
    }

    public function create()
    {
        return view('pages.hr.att-device.create');
    }

    public function store(AttDeviceRequest $request)
    {
        try {
            $this->service->create($request->validated());
            return jsonSuccess('Mesin Presensi created successfully.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function edit($id)
    {
        $attDevice = AttDevice::findOrFail($id);
        return view('pages.hr.att-device.edit', compact('attDevice'));
    }

    public function update(AttDeviceRequest $request, $id)
    {
        try {
            $attDevice = AttDevice::findOrFail($id);
            $this->service->update($attDevice, $request->validated());
            return jsonSuccess('Mesin Presensi updated successfully.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->service->delete($id);
            return jsonSuccess('Mesin Presensi deleted successfully.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }
}
