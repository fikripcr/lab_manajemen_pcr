<?php
namespace App\Http\Controllers\Pmb;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pmb\StoreJenisDokumenRequest;
use App\Models\Pmb\JenisDokumen;
use App\Services\Pmb\JenisDokumenService;
use Exception;
use Illuminate\Http\Request;

class JenisDokumenController extends Controller
{
    protected $JenisDokumenService;

    public function __construct(JenisDokumenService $JenisDokumenService)
    {
        $this->JenisDokumenService = $JenisDokumenService;
    }

    public function index()
    {
        return view('pages.pmb.jenis-dokumen.index');
    }

    public function paginate(Request $request)
    {
        return datatables()->of($this->JenisDokumenService->getPaginateData($request))
            ->addIndexColumn()
            ->editColumn('max_size_kb', fn($d) => formatBytes($d->max_size_kb * 1024))
            ->addColumn('action', function ($d) {
                return view('pages.pmb.jenis-dokumen._actions', compact('d'));
            })
            ->make(true);
    }

    public function create()
    {
        return view('pages.pmb.jenis-dokumen.create');
    }

    public function store(StoreJenisDokumenRequest $request)
    {
        try {
            $this->JenisDokumenService->createJenisDokumen($request->validated());
            return jsonSuccess('Jenis Dokumen berhasil ditambahkan.', route('pmb.jenis-dokumen.index'));
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function edit(JenisDokumen $jenisDokumen)
    {
        return view('pages.pmb.jenis-dokumen.edit', compact('jenisDokumen'));
    }

    public function update(StoreJenisDokumenRequest $request, JenisDokumen $jenisDokumen)
    {
        try {
            $this->JenisDokumenService->updateJenisDokumen($jenisDokumen->id, $request->validated());
            return jsonSuccess('Jenis Dokumen berhasil diperbarui.', route('pmb.jenis-dokumen.index'));
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function destroy(JenisDokumen $jenisDokumen)
    {
        try {
            $this->JenisDokumenService->deleteJenisDokumen($jenisDokumen->id);
            return jsonSuccess('Jenis Dokumen berhasil dihapus.');
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }
}
