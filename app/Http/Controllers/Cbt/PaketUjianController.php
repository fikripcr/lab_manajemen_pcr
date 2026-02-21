<?php
namespace App\Http\Controllers\Cbt;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cbt\StorePaketRequest;
use App\Http\Requests\Cbt\UpdatePaketRequest;
use App\Models\Cbt\KomposisiPaket;
use App\Models\Cbt\PaketUjian;
use App\Models\Cbt\Soal;
use App\Services\Cbt\PaketUjianService;
use Exception;
use Illuminate\Http\Request;

class PaketUjianController extends Controller
{
    public function __construct(protected PaketUjianService $paketUjianService)
    {}

    public function index()
    {
        return view('pages.cbt.paket.index');
    }

    public function paginate(Request $request)
    {
        $query = $this->paketUjianService->getFilteredQuery($request->all());
        return datatables()->of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($p) {
                return view('components.tabler.datatables-actions', [
                    'viewUrl'   => route('cbt.paket.show', $p->encrypted_paket_ujian_id),
                    'editUrl'   => route('cbt.paket.edit', $p->encrypted_paket_ujian_id),
                    'editModal' => true,
                    'editTitle' => 'Edit Paket',
                    'deleteUrl' => route('cbt.paket.destroy', $p->encrypted_paket_ujian_id),
                ])->render();
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        return view('pages.cbt.paket.create-edit-ajax');
    }

    public function store(StorePaketRequest $request)
    {
        try {
            $data                = $request->validated();
            $data['dibuat_oleh'] = auth()->id();

            $this->paketUjianService->store($data);
            return jsonSuccess('Paket ujian berhasil dibuat.', route('cbt.paket.index'));
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function edit(PaketUjian $paket)
    {
        return view('pages.cbt.paket.create-edit-ajax', compact('paket'));
    }

    public function update(UpdatePaketRequest $request, PaketUjian $paket)
    {
        try {
            $this->paketUjianService->update($paket, $request->validated());
            return jsonSuccess('Paket ujian berhasil diperbarui.', route('cbt.paket.index'));
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function show(PaketUjian $paket)
    {
        $paket->load(['komposisi.soal.mataUji']);
        $soalTersedia = Soal::with('mataUji')
            ->whereNotIn('soal_id', $paket->komposisi->pluck('soal_id'))
            ->where('is_aktif', true)
            ->orderBy('mata_uji_id')
            ->get();

        return view('pages.cbt.paket.show', compact('paket', 'soalTersedia'));
    }

    public function addSoal(\App\Http\Requests\Cbt\AddSoalRequest $request, PaketUjian $paket)
    {
        try {
            $this->paketUjianService->addSoal($paket, $request->input('soal_ids', []));
            return jsonSuccess('Soal berhasil ditambahkan ke paket.', route('cbt.paket.show', $paket->hashid));
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function removeSoal(PaketUjian $paket, KomposisiPaket $komposisi)
    {
        try {
            $this->paketUjianService->removeSoal($komposisi);
            return jsonSuccess('Soal berhasil dihapus dari paket.', route('cbt.paket.show', $paket->hashid));
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function destroy(PaketUjian $paket)
    {
        try {
            $this->paketUjianService->delete($paket);
            return jsonSuccess('Paket ujian berhasil dihapus.');
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }
}
