<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\TanggalLiburRequest;
use App\Models\Hr\TanggalLibur;
use App\Services\Hr\TanggalLiburService;
use Exception;
use Illuminate\Http\Request;

class TanggalLiburController extends Controller
{
    public function __construct(protected TanggalLiburService $tanggalLiburService)
    {}

    public function index(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));

        $data = TanggalLibur::where('tahun', $tahun)
            ->orderBy('tgl_libur', 'asc')
            ->get();

        // Get available years for filter
        $years = TanggalLibur::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();

        // Ensure current year is in the list
        if (! in_array(date('Y'), $years)) {
            array_unshift($years, date('Y'));
        }

        return view('pages.hr.tanggal-libur.index', compact('data', 'tahun', 'years'));
    }

    public function create()
    {
        return view('pages.hr.tanggal-libur.create-edit-ajax');
    }

    public function store(TanggalLiburRequest $request)
    {
        try {
            $count = $this->tanggalLiburService->createBatch($request->validated());
            return jsonSuccess("Berhasil menambahkan $count tanggal libur.", route('hr.tanggal-libur.index'));
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->tanggalLiburService->delete($id);
            return jsonSuccess('Data berhasil dihapus');
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }
}
