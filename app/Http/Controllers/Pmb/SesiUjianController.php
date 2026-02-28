<?php
namespace App\Http\Controllers\Pmb;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pmb\StoreSesiRequest;
use App\Models\Pmb\Periode;
use App\Models\Pmb\SesiUjian;
use App\Services\Pmb\SesiUjianService;

class SesiUjianController extends Controller
{
    public function __construct(protected SesiUjianService $sesiUjianService)
    {}

    public function index()
    {
        return view('pages.pmb.sesi-ujian.index');
    }

    public function paginate(\Illuminate\Http\Request $request)
    {
        return datatables()->of($this->sesiUjianService->getPaginateQuery())
            ->addIndexColumn()
            ->editColumn('waktu_mulai', fn($s) => formatTanggalIndo($s->waktu_mulai))
            ->editColumn('waktu_selesai', fn($s) => formatTanggalIndo($s->waktu_selesai))
            ->addColumn('action', function ($s) {
                return view('components.tabler.datatables-actions', [
                    'editUrl'       => route('pmb.sesi-ujian.edit', $s->encrypted_sesiujian_id),
                    'editModal'     => true,
                    'deleteUrl'     => route('pmb.sesi-ujian.destroy', $s->encrypted_sesiujian_id),
                    'customActions' => [
                        [
                            'url'   => route('cbt.dashboard'),
                            'label' => 'Test Ujian',
                            'icon'  => 'player-play',
                            'class' => '',
                        ],
                    ],
                ])->render();
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        $periode = Periode::where('is_aktif', true)->get();
        return view('pages.pmb.sesi-ujian.create-edit-ajax', [
            'sesi'    => new SesiUjian(),
            'periode' => $periode,
        ]);
    }

    public function store(StoreSesiRequest $request)
    {
        $this->sesiUjianService->store($request->validated());
        return jsonSuccess('Sesi ujian berhasil dibuat.', route('pmb.sesi-ujian.index'));
    }

    public function edit(SesiUjian $sesi)
    {
        $periode = Periode::where('is_aktif', true)->get();
        return view('pages.pmb.sesi-ujian.create-edit-ajax', compact('sesi', 'periode'));
    }

    public function update(StoreSesiRequest $request, SesiUjian $sesi)
    {
        $this->sesiUjianService->update($sesi, $request->validated());
        return jsonSuccess('Sesi ujian berhasil diperbarui.', route('pmb.sesi-ujian.index'));
    }

    public function destroy(SesiUjian $sesi)
    {
        $this->sesiUjianService->delete($sesi);
        return jsonSuccess('Sesi ujian berhasil dihapus.');
    }
}
