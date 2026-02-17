<?php
namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\Shared\Personil;
use DataTables;
use Illuminate\Http\Request;

class PersonilController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Personil::with(['unitKerja'])->select('*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('shared.personil.show', $row->personil_id) . '" class="edit btn btn-info btn-sm">View</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('pages.shared.personil.index');
    }

    public function show($id)
    {
        $personil = Personil::with(['unitKerja'])->findOrFail($id);
        return view('pages.shared.personil.show', compact('personil'));
    }
}
