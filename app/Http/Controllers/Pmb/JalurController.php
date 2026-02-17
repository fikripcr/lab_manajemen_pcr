<?php
namespace App\Http\Controllers\Pmb;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pmb\StoreJalurRequest;
use App\Models\Pmb\Jalur;
use App\Services\Pmb\JalurService;
use Exception;
use Illuminate\Http\Request;

class JalurController extends Controller
{
    protected $JalurService;

    public function __construct(JalurService $JalurService)
    {
        $this->JalurService = $JalurService;
    }

    public function index()
    {
        return view('pages.pmb.jalur.index');
    }

    public function paginate(Request $request)
    {
        return datatables()->of($this->JalurService->getPaginateData($request))
            ->addIndexColumn()
            ->editColumn('biaya_pendaftaran', fn($j) => 'Rp ' . number_format($j->biaya_pendaftaran, 0, ',', '.'))
            ->editColumn('is_aktif', function ($j) {
                return $j->is_aktif
                    ? '<span class="badge bg-success text-white">Aktif</span>'
                    : '<span class="badge bg-danger text-white">Non-Aktif</span>';
            })
            ->addColumn('action', function ($j) {
                return view('pages.pmb.jalur._actions', compact('j'));
            })
            ->rawColumns(['is_aktif', 'action'])
            ->make(true);
    }

    public function create()
    {
        return view('pages.pmb.jalur.create');
    }

    public function store(StoreJalurRequest $request)
    {
        try {
            $this->JalurService->createJalur($request->validated());
            return jsonSuccess('Jalur berhasil ditambahkan.', route('pmb.jalur.index'));
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function edit(Jalur $jalur)
    {
        return view('pages.pmb.jalur.edit', compact('jalur'));
    }

    public function update(StoreJalurRequest $request, Jalur $jalur)
    {
        try {
            $this->JalurService->updateJalur($jalur->id, $request->validated());
            return jsonSuccess('Jalur berhasil diperbarui.', route('pmb.jalur.index'));
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function destroy(Jalur $jalur)
    {
        try {
            $this->JalurService->deleteJalur($jalur->id);
            return jsonSuccess('Jalur berhasil dihapus.');
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }
}
