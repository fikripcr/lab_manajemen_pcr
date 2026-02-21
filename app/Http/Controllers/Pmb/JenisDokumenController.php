<?php
namespace App\Http\Controllers\Pmb;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pmb\StoreJenisDokumenRequest;
use App\Models\Pmb\JenisDokumen;
use App\Services\Pmb\JenisDokumenService;
use Exception;

class JenisDokumenController extends Controller
{
    public function __construct(protected JenisDokumenService $jenisDokumenService)
    {}

    public function index()
    {
        return view('pages.pmb.jenis-dokumen.index');
    }

    public function paginate(\Illuminate\Http\Request $request)
    {
        return datatables()->of($this->jenisDokumenService->getPaginateData($request->all()))
            ->addIndexColumn()
            ->editColumn('max_size_kb', fn($d) => formatBytes($d->max_size_kb * 1024))
            ->addColumn('action', function ($d) {
                return view('components.tabler.datatables-actions', [
                    'editUrl'   => route('pmb.jenis-dokumen.edit', $d->encrypted_jenis_dokumen_id),
                    'editModal' => true,
                    'deleteUrl' => route('pmb.jenis-dokumen.destroy', $d->encrypted_jenis_dokumen_id),
                ])->render();
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        return view('pages.pmb.jenis-dokumen.create-edit-ajax', ['jenisDokumen' => new JenisDokumen()]);
    }

    public function store(StoreJenisDokumenRequest $request)
    {
        try {
            $this->jenisDokumenService->createJenisDokumen($request->validated());
            return jsonSuccess('Jenis Dokumen berhasil ditambahkan.', route('pmb.jenis-dokumen.index'));
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal menambahkan jenis dokumen: ' . $e->getMessage());
        }
    }

    public function edit(JenisDokumen $jenisDokumen)
    {
        return view('pages.pmb.jenis-dokumen.create-edit-ajax', compact('jenisDokumen'));
    }

    public function update(StoreJenisDokumenRequest $request, JenisDokumen $jenisDokumen)
    {
        try {
            $this->jenisDokumenService->updateJenisDokumen($jenisDokumen, $request->validated());
            return jsonSuccess('Jenis Dokumen berhasil diperbarui.', route('pmb.jenis-dokumen.index'));
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal memperbarui jenis dokumen: ' . $e->getMessage());
        }
    }

    public function destroy(JenisDokumen $jenisDokumen)
    {
        try {
            $this->jenisDokumenService->deleteJenisDokumen($jenisDokumen);
            return jsonSuccess('Jenis Dokumen berhasil dihapus.');
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal menghapus jenis dokumen: ' . $e->getMessage());
        }
    }
}
