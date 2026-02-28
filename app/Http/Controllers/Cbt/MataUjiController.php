<?php
namespace App\Http\Controllers\Cbt;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cbt\StoreMataUjiRequest;
use App\Http\Requests\Cbt\UpdateMataUjiRequest;
use App\Models\Cbt\MataUji;
use App\Services\Cbt\MataUjiService;
use Illuminate\Http\Request;

class MataUjiController extends Controller
{
    public function __construct(protected MataUjiService $MataUjiService)
    {}

    public function index()
    {
        return view('pages.cbt.mata-uji.index');
    }

    public function paginate(Request $request)
    {
        $query = $this->MataUjiService->getFilteredQuery($request->all())
            ->withCount([
                'soal',
                'soal as mudah_count'  => fn($q)  => $q->where('tingkat_kesulitan', 'Mudah'),
                'soal as sedang_count' => fn($q) => $q->where('tingkat_kesulitan', 'Sedang'),
                'soal as sulit_count'  => fn($q)  => $q->where('tingkat_kesulitan', 'Sulit'),
            ]);

        return datatables()->of($query)
            ->addIndexColumn()
            ->addColumn('jumlah_soal', function ($mu) {
                return '<span class="badge bg-blue-lt">' . $mu->soal_count . ' Soal</span>';
            })
            ->addColumn('kesulitan', function ($mu) {
                return '<div class="d-flex flex-wrap gap-1">
                    <span class="badge bg-success-lt">Mudah: ' . $mu->mudah_count . '</span>
                    <span class="badge bg-warning-lt">Sedang: ' . $mu->sedang_count . '</span>
                    <span class="badge bg-danger-lt">Sulit: ' . $mu->sulit_count . '</span>
                </div>';
            })
            ->addColumn('action', function ($mu) {
                return view('components.tabler.datatables-actions', [
                    'viewUrl'   => route('cbt.mata-uji.show', $mu->encrypted_mata_uji_id),
                    'editUrl'   => route('cbt.mata-uji.edit', $mu->encrypted_mata_uji_id),
                    'editModal' => true,
                    'editTitle' => 'Edit Mata Uji',
                    'deleteUrl' => route('cbt.mata-uji.destroy', $mu->encrypted_mata_uji_id),
                ])->render();
            })
            ->rawColumns(['jumlah_soal', 'kesulitan', 'action'])
            ->make(true);
    }

    public function create()
    {
        return view('pages.cbt.mata-uji.create-edit-ajax');
    }

    public function show(MataUji $mata_uji)
    {
        $mata_uji->load(['soal']);
        return view('pages.cbt.mata-uji.show', ['mu' => $mata_uji]);
    }

    public function store(StoreMataUjiRequest $request)
    {
        $this->MataUjiService->store($request->validated());
        return jsonSuccess('Mata uji berhasil disimpan.', route('cbt.mata-uji.index'));
    }

    public function edit(MataUji $mata_uji)
    {
        return view('pages.cbt.mata-uji.create-edit-ajax', ['mu' => $mata_uji]);
    }

    public function update(UpdateMataUjiRequest $request, MataUji $mata_uji)
    {
        $this->MataUjiService->update($mata_uji, $request->validated());
        return jsonSuccess('Mata uji berhasil diperbarui.', route('cbt.mata-uji.index'));
    }

    public function destroy(MataUji $mata_uji)
    {
        $this->MataUjiService->delete($mata_uji);
        return jsonSuccess('Mata uji berhasil dihapus.');
    }
}
