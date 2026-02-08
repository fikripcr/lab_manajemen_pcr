<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Models\Hr\JenisIndisipliner;
use Illuminate\Http\Request;
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
        return view('pages.hr.jenis-indisipliner.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_indisipliner' => 'required|string|max:100|unique:hr_jenis_indisipliner,jenis_indisipliner',
        ]);

        try {
            JenisIndisipliner::create($validated);
            return jsonSuccess('Jenis Indisipliner berhasil ditambahkan.');
        } catch (\Exception $e) {
            return jsonError('Gagal menyimpan data: ' . $e->getMessage(), 500);
        }
    }

    public function edit(JenisIndisipliner $jenis_indisipliner)
    {
        $jenisIndisipliner = $jenis_indisipliner;
        return view('pages.hr.jenis-indisipliner.edit', compact('jenisIndisipliner'));
    }

    public function update(Request $request, JenisIndisipliner $jenis_indisipliner)
    {
        $validated = $request->validate([
            'jenis_indisipliner' => 'required|string|max:100|unique:hr_jenis_indisipliner,jenis_indisipliner,' . $jenis_indisipliner->jenisindisipliner_id . ',jenisindisipliner_id',
        ]);

        try {
            $jenis_indisipliner->update($validated);
            return jsonSuccess('Jenis Indisipliner berhasil diperbarui.');
        } catch (\Exception $e) {
            return jsonError('Gagal memperbarui data: ' . $e->getMessage(), 500);
        }
    }

    public function destroy(JenisIndisipliner $jenis_indisipliner)
    {
        try {
            // Check if there are any indisipliner records using this type
            if ($jenis_indisipliner->indisipliner()->count() > 0) {
                return jsonError('Tidak dapat menghapus jenis indisipliner yang masih digunakan.', 422);
            }

            $jenis_indisipliner->delete();
            return jsonSuccess('Jenis Indisipliner berhasil dihapus.');
        } catch (\Exception $e) {
            return jsonError('Gagal menghapus data: ' . $e->getMessage(), 500);
        }
    }
}
