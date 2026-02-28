<?php
namespace App\Http\Controllers\Cbt;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cbt\StoreSoalRequest;
use App\Http\Requests\Cbt\UpdateSoalRequest;
use App\Models\Cbt\MataUji;
use App\Models\Cbt\Soal;
use App\Services\Cbt\SoalService;
use Illuminate\Http\Request;

class SoalController extends Controller
{
    public function __construct(protected SoalService $SoalService)
    {}

    public function index()
    {
        $mataUji = MataUji::all();
        return view('pages.cbt.soal.index', compact('mataUji'));
    }

    public function paginate(Request $request)
    {
        $query = $this->SoalService->getFilteredQuery($request->all());

        return datatables()->of($query)
            ->addIndexColumn()
            ->editColumn('konten_pertanyaan', fn($s) => strip_tags(substr($s->konten_pertanyaan, 0, 100)) . '...')
            ->addColumn('action', function ($s) {
                return view('components.tabler.datatables-actions', [
                    'editUrl'     => route('cbt.soal.edit', $s->encrypted_soal_id),
                    'editModal'   => true,
                    'editTitle'   => 'Edit Soal',
                    'deleteUrl'   => route('cbt.soal.destroy', $s->encrypted_soal_id),
                    'deleteTitle' => 'Hapus soal ini?',
                ])->render();
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create(MataUji $mataUji)
    {
        // Build an empty Soal instance associated with MataUji for the view
        $soal = new Soal([
            'tipe_soal'   => 'Pilihan_Ganda',
            'mata_uji_id' => $mataUji->mata_uji_id,
        ]);
        $soal->setRelation('mataUji', $mataUji);

        return view('pages.cbt.soal.create-edit-ajax', compact('soal'));
    }

    public function store(StoreSoalRequest $request)
    {
        $this->SoalService->store($request->validated());
        return jsonSuccess('Soal berhasil disimpan.', route('cbt.soal.index'));
    }

    public function edit(Soal $soal)
    {
        $soal->load(['opsiJawaban', 'mataUji']);
        return view('pages.cbt.soal.create-edit-ajax', compact('soal'));
    }

    public function update(UpdateSoalRequest $request, Soal $soal)
    {
        $this->SoalService->update($soal, $request->validated());
        return jsonSuccess('Soal berhasil diperbarui.', route('cbt.soal.index'));
    }

    public function destroy(Soal $soal)
    {
        $this->SoalService->delete($soal);
        return jsonSuccess('Soal berhasil dihapus.');
    }
}
