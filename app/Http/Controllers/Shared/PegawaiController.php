<?php
namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\Shared\Pegawai;
use DataTables;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    public function index(Request $request)
    {
        return view('pages.shared.pegawai.index');
    }

    public function data(Request $request)
    {
        $data = Pegawai::with(['unitKerja', 'unitKerja.parent'])->select('*');
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<a href="' . route('shared.pegawai.show', $row->pegawai_id) . '" class="edit btn btn-info btn-sm">View</a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function show(Pegawai $pegawai)
    {
        $pegawai->load(['unitKerja', 'jabatanStruktural']);
        return view('pages.shared.pegawai.show', compact('pegawai'));
    }
}
