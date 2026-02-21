<?php
namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\Shared\Mahasiswa;
use DataTables;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Mahasiswa::with('prodi');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('prodi_nama', function ($row) {
                    return $row->prodi->nama_prodi ?? '-';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('mahasiswa.show', $row->encrypted_mahasiswa_id) . '" class="edit btn btn-info btn-sm">View</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('pages.shared.mahasiswa.index');
    }

    public function show(Mahasiswa $mahasiswa)
    {
        return view('pages.shared.mahasiswa.show', compact('mahasiswa'));
    }
}
