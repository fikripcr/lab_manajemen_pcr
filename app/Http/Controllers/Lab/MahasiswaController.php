<?php
namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Models\Lab\Mahasiswa;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class MahasiswaController extends Controller
{
    public function index()
    {
        return view('pages.lab.mahasiswa.index');
    }

    public function paginate(Request $request)
    {
        $query = Mahasiswa::query();

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('created_at', function ($row) {
                return formatTanggalIndo($row->created_at);
            })
            ->addColumn('action', function ($row) {
                $encryptedId = encryptId($row->mahasiswa_id);
                return view('components.tabler.datatables-actions', [
                    'editUrl'   => route('lab.mahasiswa.edit', $encryptedId),
                    'viewUrl'   => route('lab.mahasiswa.show', $encryptedId),
                    'deleteUrl' => route('lab.mahasiswa.destroy', $encryptedId),
                ])->render();
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function show($id)
    {
        $realId    = decryptId($id);
        $mahasiswa = Mahasiswa::findOrFail($realId);
        return view('pages.lab.mahasiswa.show', compact('mahasiswa'));
    }

    public function create()
    {
        return view('pages.lab.mahasiswa.create');
    }

    public function edit($id)
    {
        $realId    = decryptId($id);
        $mahasiswa = Mahasiswa::findOrFail($realId);
        return view('pages.lab.mahasiswa.edit', compact('mahasiswa'));
    }

    public function destroy($id)
    {
        try {
            $realId = decryptId($id);
            Mahasiswa::destroy($realId);
            return jsonSuccess('Data Mahasiswa berhasil dihapus.', route('lab.mahasiswa.index'));
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }
}
