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
    public function __construct(protected MataUjiService $mataUjiService)
    {}

    public function index()
    {
        return view('pages.cbt.mata-uji.index');
    }

    public function paginate(Request $request)
    {
        $query = $this->mataUjiService->getFilteredQuery($request->all());
        return datatables()->of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($mu) {
                return view('components.tabler.datatables-actions', [
                    'viewUrl'   => route('cbt.mata-uji.show', $mu->encrypted_mata_uji_id),
                    'editUrl'   => route('cbt.mata-uji.edit', $mu->encrypted_mata_uji_id),
                    'editModal' => true,
                    'editTitle' => 'Edit Mata Uji',
                    'deleteUrl' => route('cbt.mata-uji.destroy', $mu->encrypted_mata_uji_id),
                ])->render();
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        return view('pages.cbt.mata-uji.create-edit-ajax');
    }

    public function show(MataUji $mu)
    {
        try {
            $mu->load(['soal']);
            return view('pages.cbt.mata-uji.show', compact('mu'));
        } catch (Exception $e) {
            logError($e);
            return redirect()->back()->with('error', 'Gagal memuat detail mata uji: ' . $e->getMessage());
        }
    }

    public function store(StoreMataUjiRequest $request)
    {
        try {
            $this->mataUjiService->store($request->validated());
            return jsonSuccess('Mata uji berhasil disimpan.', route('cbt.mata-uji.index'));
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal menyimpan mata uji.');
        }
    }

    public function edit(MataUji $mu)
    {
        return view('pages.cbt.mata-uji.create-edit-ajax', compact('mu'));
    }

    public function update(UpdateMataUjiRequest $request, MataUji $mu)
    {
        try {
            $this->mataUjiService->update($mu, $request->validated());
            return jsonSuccess('Mata uji berhasil diperbarui.', route('cbt.mata-uji.index'));
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal memperbarui mata uji.');
        }
    }

    public function destroy(MataUji $mu)
    {
        try {
            $this->mataUjiService->delete($mu);
            return jsonSuccess('Mata uji berhasil dihapus.');
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal menghapus mata uji.');
        }
    }
}
