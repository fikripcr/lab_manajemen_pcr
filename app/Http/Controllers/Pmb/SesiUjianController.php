<?php
namespace App\Http\Controllers\Pmb;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pmb\StoreSesiRequest;
use App\Models\Pmb\Periode;
use App\Models\Pmb\SesiUjian;
use App\Services\Pmb\SesiUjianService;
use Exception;
use Illuminate\Http\Request;

class SesiUjianController extends Controller
{
    protected $SesiUjianService;

    public function __construct(SesiUjianService $SesiUjianService)
    {
        $this->SesiUjianService = $SesiUjianService;
    }

    public function index()
    {
        return view('pages.pmb.sesi-ujian.index');
    }

    public function paginate(Request $request)
    {
        $query = SesiUjian::with('periode');
        return datatables()->of($query)
            ->addIndexColumn()
            ->editColumn('waktu_mulai', fn($s) => formatTanggalIndo($s->waktu_mulai))
            ->editColumn('waktu_selesai', fn($s) => formatTanggalIndo($s->waktu_selesai))
            ->addColumn('action', function ($s) {
                return view('pages.pmb.sesi-ujian._actions', compact('s'));
            })
            ->make(true);
    }

    public function create()
    {
        $periode = Periode::where('is_aktif', true)->get();
        return view('pages.pmb.sesi-ujian.create', compact('periode'));
    }

    public function store(StoreSesiRequest $request)
    {
        try {
            $this->SesiUjianService->store($request->validated());
            return jsonSuccess('Sesi ujian berhasil dibuat.', route('pmb.sesi-ujian.index'));
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function destroy(SesiUjian $sesi)
    {
        try {
            $this->SesiUjianService->delete($sesi);
            return jsonSuccess('Sesi ujian berhasil dihapus.');
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }
}
