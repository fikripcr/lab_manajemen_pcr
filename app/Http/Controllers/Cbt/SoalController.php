<?php
namespace App\Http\Controllers\Cbt;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cbt\StoreSoalRequest;
use App\Http\Requests\Cbt\UpdateSoalRequest;
use App\Models\Cbt\MataUji;
use App\Models\Cbt\Soal;
use App\Services\Cbt\SoalService;
use Exception;
use Illuminate\Http\Request;

class SoalController extends Controller
{
    protected $SoalService;

    public function __construct(SoalService $SoalService)
    {
        $this->SoalService = $SoalService;
    }

    public function index(Request $request)
    {
        $mataUji = MataUji::all();
        return view('pages.cbt.soal.index', compact('mataUji'));
    }

    public function paginate(Request $request)
    {
        $query = Soal::with(['mataUji', 'pembuat']);

        if ($request->mata_uji_id) {
            $query->where('mata_uji_id', decryptId($request->mata_uji_id));
        }

        return datatables()->of($query)
            ->addIndexColumn()
            ->editColumn('konten_pertanyaan', fn($s) => strip_tags(substr($s->konten_pertanyaan, 0, 100)) . '...')
            ->addColumn('action', function ($s) {
                return view('pages.cbt.soal._actions', compact('s'));
            })
            ->make(true);
    }

    public function create()
    {
        $mataUji = MataUji::all();
        return view('pages.cbt.soal.create', compact('mataUji'));
    }

    public function store(StoreSoalRequest $request)
    {
        try {
            $this->SoalService->store($request->validated());
            return jsonSuccess('Soal berhasil disimpan.', route('cbt.soal.index'));
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function edit(Soal $soal)
    {
        $soal->load('opsiJawaban');
        $mataUji = MataUji::all();
        return view('pages.cbt.soal.edit', compact('soal', 'mataUji'));
    }

    public function update(UpdateSoalRequest $request, Soal $soal)
    {
        try {
            $this->SoalService->update($soal, $request->validated());
            return jsonSuccess('Soal berhasil diperbarui.', route('cbt.soal.index'));
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function destroy(Soal $soal)
    {
        try {
            $this->SoalService->delete($soal);
            return jsonSuccess('Soal berhasil dihapus.');
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }
}
