<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\TanggalTidakMasukRequest;
use App\Models\Hr\TanggalTidakMasuk;
use App\Services\Hr\TanggalTidakMasukService;
use Illuminate\Http\Request;

class TanggalTidakMasukController extends Controller
{
    protected $service;

    public function __construct(TanggalTidakMasukService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));

        $data = TanggalTidakMasuk::whereYear('tanggal', $tahun)
            ->orderBy('tanggal', 'asc')
            ->get();

        // Get available years for filter
        $years = TanggalTidakMasuk::selectRaw('YEAR(tanggal) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        // Ensure current year is in the list
        if (! in_array(date('Y'), $years)) {
            array_unshift($years, date('Y'));
        }

        return view('pages.hr.tanggal-tidak-masuk.index', compact('data', 'tahun', 'years'));
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
