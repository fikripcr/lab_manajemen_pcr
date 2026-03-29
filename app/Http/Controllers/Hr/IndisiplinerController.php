<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\IndisiplinerRequest;
use App\Models\Hr\Indisipliner;
use App\Services\Hr\IndisiplinerService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class IndisiplinerController extends Controller
{
    public function __construct(
        protected IndisiplinerService $indisiplinerService
    ) {}

    public function index()
    {
        $jenisIndisipliner = $this->indisiplinerService->getJenisIndisipliner();

        return view('pages.hr.indisipliner.index', compact('jenisIndisipliner'));
    }

    public function data(Request $request)
    {
        $query = $this->indisiplinerService->getDataQuery($request->input('f_tahun'));

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('tgl_indisipliner', function ($row) {
                return $row->tgl_indisipliner ? $row->tgl_indisipliner->format('d-m-Y') : '-';
            })
            ->addColumn('jenis', function ($row) {
                return $row->jenisIndisipliner->jenis_indisipliner ?? '-';
            })
            ->addColumn('hr_pegawai', function ($row) {
                $badges = [];
                foreach ($row->indisiplinerPegawai as $ip) {
                    $inisial = $ip->pegawai->latestDataDiri->inisial ?? 'N/A';
                    $badges[] = '<span class="badge bg-red-lt me-1">'.e($inisial).'</span>';
                }

                return implode('', $badges);
            })
            ->addColumn('action', function ($row) {
                return view('components.tabler.datatables-actions', [
                    'editUrl' => route('hr.indisipliner.edit', ['indisipliner' => $row->encrypted_indisipliner_id]),
                    'editModal' => true,
                    'deleteUrl' => route('hr.indisipliner.destroy', ['indisipliner' => $row->encrypted_indisipliner_id]),
                ])->render();
            })
            ->rawColumns(['hr_pegawai', 'action'])
            ->make(true);
    }

    public function create()
    {
        $jenisIndisipliner = $this->indisiplinerService->getJenisIndisipliner();
        $indisipliner = new Indisipliner;

        return view('pages.hr.indisipliner.create-edit-ajax', compact('jenisIndisipliner', 'indisipliner'));
    }

    public function store(IndisiplinerRequest $request)
    {
        $this->indisiplinerService->store($request->validated(), $request->file('bukti'));

        return jsonSuccess('Indisipliner berhasil dibuat.');
    }

    public function edit(Indisipliner $indisipliner)
    {
        $jenisIndisipliner = $this->indisiplinerService->getJenisIndisipliner();
        $indisipliner->load('indisiplinerPegawai.pegawai.latestDataDiri');

        return view('pages.hr.indisipliner.create-edit-ajax', compact('indisipliner', 'jenisIndisipliner'));
    }

    public function update(IndisiplinerRequest $request, Indisipliner $indisipliner)
    {
        $this->indisiplinerService->update($indisipliner, $request->validated(), $request->file('bukti'));

        return jsonSuccess('Indisipliner berhasil diperbarui.');
    }

    public function destroy(Indisipliner $indisipliner)
    {
        $this->indisiplinerService->delete($indisipliner);

        return jsonSuccess('Data indisipliner berhasil dihapus.');
    }
}
