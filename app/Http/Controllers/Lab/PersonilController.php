<?php
namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Models\Lab\Personil;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PersonilController extends Controller
{
    public function index()
    {
        return view('pages.lab.personil.index');
    }

    public function paginate(Request $request)
    {
        $query = Personil::query();

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('created_at', function ($row) {
                return formatTanggalIndo($row->created_at);
            })
            ->addColumn('action', function ($row) {
                $encryptedId = encryptId($row->personil_id);
                return view('components.tabler.datatables-actions', [
                    'editUrl'   => route('lab.personil.edit', $encryptedId),
                    'viewUrl'   => route('lab.personil.show', $encryptedId),
                    'deleteUrl' => route('lab.personil.destroy', $encryptedId),
                ])->render();
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function show($id)
    {
        $realId   = decryptId($id);
        $personil = Personil::findOrFail($realId);
        return view('pages.lab.personil.show', compact('personil'));
    }

    public function create()
    {
        return view('pages.lab.personil.create');
    }

    public function edit($id)
    {
        $realId   = decryptId($id);
        $personil = Personil::findOrFail($realId);
        return view('pages.lab.personil.edit', compact('personil'));
    }

    public function destroy($id)
    {
        try {
            $realId = decryptId($id);
            Personil::destroy($realId);
            return jsonSuccess('Data Personil berhasil dihapus.', route('lab.personil.index'));
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }
}
