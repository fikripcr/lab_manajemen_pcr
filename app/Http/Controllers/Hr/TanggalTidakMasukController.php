<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\TanggalTidakMasukRequest;
use App\Models\Hr\TanggalTidakMasuk;
use App\Services\Hr\TanggalTidakMasukService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TanggalTidakMasukController extends Controller
{
    protected $service;

    public function __construct(TanggalTidakMasukService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = TanggalTidakMasuk::query()->latest('tanggal');

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('tanggal', function ($row) {
                    return $row->tanggal ? $row->tanggal->format('d M Y') : '-';
                })
                ->addColumn('action', 'components.tabler.datatables-actions')
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('pages.hr.tanggal-tidak-masuk.index');
    }

    public function create()
    {
        return view('pages.hr.tanggal-tidak-masuk.create');
    }

    public function store(TanggalTidakMasukRequest $request)
    {
        try {
            $count = $this->service->createBatch($request->validated());
            return jsonSuccess("Berhasil menambahkan $count tanggal tidak masuk.", route('hr.tanggal-tidak-masuk.index'));
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->service->delete($id);
            return jsonSuccess('Data berhasil dihapus');
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }
}
