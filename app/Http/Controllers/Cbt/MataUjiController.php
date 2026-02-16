<?php
namespace App\Http\Controllers\Cbt;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cbt\StoreMataUjiRequest;
use App\Http\Requests\Cbt\UpdateMataUjiRequest;
use App\Models\Cbt\MataUji;
use App\Services\Cbt\MataUjiService;
use Exception;
use Illuminate\Http\Request;

class MataUjiController extends Controller
{
    protected $MataUjiService;

    public function __construct(MataUjiService $MataUjiService)
    {
        $this->MataUjiService = $MataUjiService;
    }

    public function index()
    {
        return view('pages.cbt.mata-uji.index');
    }

    public function paginate(Request $request)
    {
        $query = MataUji::query();
        return datatables()->of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($mu) {
                return view('pages.cbt.mata-uji._actions', compact('mu'));
            })
            ->make(true);
    }

    public function create()
    {
        return view('pages.cbt.mata-uji.create');
    }

    public function store(StoreMataUjiRequest $request)
    {
        try {
            $this->MataUjiService->store($request->validated());
            return jsonSuccess('Mata uji berhasil disimpan.', route('cbt.mata-uji.index'));
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function edit(MataUji $mu)
    {
        return view('pages.cbt.mata-uji.edit', compact('mu'));
    }

    public function update(UpdateMataUjiRequest $request, MataUji $mu)
    {
        try {
            $this->MataUjiService->update($mu, $request->validated());
            return jsonSuccess('Mata uji berhasil diperbarui.', route('cbt.mata-uji.index'));
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function destroy(MataUji $mu)
    {
        try {
            $this->MataUjiService->delete($mu);
            return jsonSuccess('Mata uji berhasil dihapus.');
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }
}
