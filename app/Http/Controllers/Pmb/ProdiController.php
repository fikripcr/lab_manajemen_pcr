<?php
namespace App\Http\Controllers\Pmb;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pmb\StoreProdiRequest;
use App\Models\Pmb\Prodi;
use App\Services\Pmb\ProdiService;
use Exception;
use Illuminate\Http\Request;

class ProdiController extends Controller
{
    protected $ProdiService;

    public function __construct(ProdiService $ProdiService)
    {
        $this->ProdiService = $ProdiService;
    }

    public function index()
    {
        return view('pages.pmb.prodi.index');
    }

    public function paginate(Request $request)
    {
        return datatables()->of($this->ProdiService->getPaginateData($request))
            ->addIndexColumn()
            ->addColumn('action', function ($p) {
                return view('pages.pmb.prodi._actions', compact('p'));
            })
            ->make(true);
    }

    public function create()
    {
        return view('pages.pmb.prodi.create');
    }

    public function store(StoreProdiRequest $request)
    {
        try {
            $this->ProdiService->createProdi($request->validated());
            return jsonSuccess('Prodi berhasil ditambahkan.', route('pmb.prodi.index'));
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function edit(Prodi $prodi)
    {
        return view('pages.pmb.prodi.edit', compact('prodi'));
    }

    public function update(StoreProdiRequest $request, Prodi $prodi)
    {
        try {
            $this->ProdiService->updateProdi($prodi->id, $request->validated());
            return jsonSuccess('Prodi berhasil diperbarui.', route('pmb.prodi.index'));
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function destroy(Prodi $prodi)
    {
        try {
            $this->ProdiService->deleteProdi($prodi->id);
            return jsonSuccess('Prodi berhasil dihapus.');
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }
}
