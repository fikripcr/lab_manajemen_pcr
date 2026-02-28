<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\PegawaiImportRequest;
use App\Http\Requests\Pemutu\PegawaiRequest;
use App\Models\Pemutu\OrgUnit;
use App\Services\Pemutu\PegawaiService;
use Yajra\DataTables\DataTables;

class PegawaiController extends Controller
{
    public function __construct(protected PegawaiService $pegawaiService)
    {}

    public function index()
    {
        return view('pages.pemutu.pegawai.index');
    }

    public function paginate()
    {
        $query = $this->pegawaiService->getFilteredQuery();

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('org_unit_id', function ($row) {
                return $row->orgUnit ? $row->orgUnit->name : '-';
            })
            ->editColumn('user_id', function ($row) {
                return $row->user ? '<span class="badge bg-success-lt">Linked</span>' : '<span class="badge bg-secondary-lt">Unlinked</span>';
            })
            ->addColumn('action', function ($row) {
                return view('components.tabler.datatables-actions', [
                    'editUrl'   => route('pemutu.pegawai.edit', $row->encrypted_pegawai_id),
                    'editModal' => true,
                    'deleteUrl' => route('pemutu.pegawai.destroy', $row->encrypted_pegawai_id),
                ])->render();
            })
            ->rawColumns(['user_id', 'action'])
            ->make(true);
    }

    public function create()
    {
        $pegawai = new \App\Models\Shared\Pegawai();
        $units   = OrgUnit::orderBy('name')->get();
        return view('pages.pemutu.pegawai.create-edit-ajax', compact('pegawai', 'units'));
    }

    public function store(PegawaiRequest $request)
    {
        $this->pegawaiService->createPegawai($request->validated());

        logActivity('pemutu', "Menambah pegawai baru: " . ($request->nama ?? ''));

        return jsonSuccess('Pegawai created successfully.');
    }

    public function edit(\App\Models\Shared\Pegawai $pegawai)
    {
        $units = OrgUnit::orderBy('name')->get();
        return view('pages.pemutu.pegawai.create-edit-ajax', compact('pegawai', 'units'));
    }

    public function update(PegawaiRequest $request, \App\Models\Shared\Pegawai $pegawai)
    {
        $this->pegawaiService->updatePegawai($pegawai->pegawai_id, $request->validated());

        logActivity('pemutu', "Memperbarui data pegawai: {$pegawai->nama}");

        return jsonSuccess('Pegawai updated successfully.');
    }

    public function destroy(\App\Models\Shared\Pegawai $pegawai)
    {
        $pegawaiName = $pegawai->nama;
        $this->pegawaiService->deletePegawai($pegawai->pegawai_id);

        logActivity('pemutu', "Menghapus pegawai: {$pegawaiName}");

        return jsonSuccess('Pegawai deleted successfully.');
    }

    public function import(PegawaiImportRequest $request)
    {
        if ($request->isMethod('get')) {
            return view('pages.pemutu.pegawai.import');
        }

        $this->pegawaiService->importPegawai($request->file('file'));

        logActivity('pemutu', "Mengimport data pegawai via Excel");

        return jsonSuccess('Pegawai imported successfully.', route('pemutu.pegawai.index'));
    }
}
