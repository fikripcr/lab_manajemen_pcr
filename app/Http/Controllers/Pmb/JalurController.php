<?php
namespace App\Http\Controllers\Pmb;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pmb\StoreJalurRequest;
use App\Models\Pmb\Jalur;
use App\Services\Pmb\JalurService;

class JalurController extends Controller
{
    public function __construct(protected JalurService $jalurService)
    {}

    public function index()
    {
        return view('pages.pmb.jalur.index');
    }

    public function paginate(\Illuminate\Http\Request $request)
    {
        return datatables()->of($this->jalurService->getPaginateData($request->all()))
            ->addIndexColumn()
            ->editColumn('biaya_pendaftaran', fn($j) => 'Rp ' . number_format($j->biaya_pendaftaran, 0, ',', '.'))
            ->editColumn('is_aktif', function ($j) {
                return $j->is_aktif
                    ? '<span class="badge bg-success text-white">Aktif</span>'
                    : '<span class="badge bg-danger text-white">Non-Aktif</span>';
            })
            ->addColumn('action', function ($j) {
                return view('components.tabler.datatables-actions', [
                    'editUrl'   => route('pmb.jalur.edit', $j->encrypted_jalur_id),
                    'editModal' => true,
                    'deleteUrl' => route('pmb.jalur.destroy', $j->encrypted_jalur_id),
                ])->render();
            })
            ->rawColumns(['is_aktif', 'action'])
            ->make(true);
    }

    public function create()
    {
        return view('pages.pmb.jalur.create-edit-ajax', ['jalur' => new Jalur()]);
    }

    public function store(StoreJalurRequest $request)
    {
        $this->jalurService->createJalur($request->validated());
        return jsonSuccess('Jalur berhasil ditambahkan.', route('pmb.jalur.index'));
    }

    public function edit(Jalur $jalur)
    {
        return view('pages.pmb.jalur.create-edit-ajax', compact('jalur'));
    }

    public function update(StoreJalurRequest $request, Jalur $jalur)
    {
        $this->jalurService->updateJalur($jalur, $request->validated());
        return jsonSuccess('Jalur berhasil diperbarui.', route('pmb.jalur.index'));
    }

    public function destroy(Jalur $jalur)
    {
        $this->jalurService->deleteJalur($jalur);
        return jsonSuccess('Jalur berhasil dihapus.');
    }
}
