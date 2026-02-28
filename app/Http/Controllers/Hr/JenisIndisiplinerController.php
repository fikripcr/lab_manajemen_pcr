<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\JenisIndisiplinerRequest;
use App\Models\Hr\JenisIndisipliner;
use Yajra\DataTables\Facades\DataTables;

class JenisIndisiplinerController extends Controller
{
    public function index()
    {
        return view('pages.hr.jenis-indisipliner.index');
    }

    public function data()
    {
        $query = JenisIndisipliner::query()->latest();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return view('components.tabler.datatables-actions', [
                    'editUrl'   => route('hr.jenis-indisipliner.edit', ['jenis_indisipliner' => $row->hashid]),
                    'editModal' => true,
                    'deleteUrl' => route('hr.jenis-indisipliner.destroy', ['jenis_indisipliner' => $row->hashid]),
                ])->render();
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        $jenisIndisipliner = new JenisIndisipliner();
        return view('pages.hr.jenis-indisipliner.create-edit-ajax', compact('jenisIndisipliner'));
    }

    public function store(JenisIndisiplinerRequest $request)
    {
        $validated = $request->validated();
        JenisIndisipliner::create($validated);
        return jsonSuccess('Jenis indisipliner berhasil dibuat.');
    }

    public function edit(JenisIndisipliner $jenisIndisipliner)
    {
        return view('pages.hr.jenis-indisipliner.create-edit-ajax', compact('jenisIndisipliner'));
    }

    public function update(JenisIndisiplinerRequest $request, JenisIndisipliner $jenisIndisipliner)
    {
        $validated = $request->validated();
        $jenisIndisipliner->update($validated);
        return jsonSuccess('Jenis Indisipliner berhasil diperbarui.');
    }

    public function destroy(JenisIndisipliner $jenisIndisipliner)
    {
        // Check if there are any indisipliner records using this type
        if ($jenisIndisipliner->indisipliner()->count() > 0) {
            return jsonError('Tidak dapat menghapus jenis indisipliner yang masih digunakan.', 422);
        }

        $jenisIndisipliner->delete();
        return jsonSuccess('Jenis Indisipliner berhasil dihapus.');
    }
}
