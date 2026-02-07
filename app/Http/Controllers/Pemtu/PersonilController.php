<?php
namespace App\Http\Controllers\Pemtu;

use App\Http\Controllers\Controller;
use App\Imports\Pemtu\PersonilImport;
use App\Models\Pemtu\OrgUnit;
use App\Models\Pemtu\Personil;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class PersonilController extends Controller
{
    public function index()
    {
        return view('pages.pemtu.personils.index');
    }

    public function paginate()
    {
        $data = Personil::with(['orgUnit', 'user']);

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('org_unit_id', function ($row) {
                return $row->orgUnit ? $row->orgUnit->name : '-';
            })
            ->editColumn('user_id', function ($row) {
                return $row->user ? '<span class="badge bg-success-lt">Linked</span>' : '<span class="badge bg-secondary-lt">Unlinked</span>';
            })
            ->addColumn('action', function ($row) {
                return view('components.tabler.datatables-actions', [
                    'editUrl'   => route('pemtu.personils.edit', $row->personil_id),
                    'editModal' => true,
                    'deleteUrl' => route('pemtu.personils.destroy', $row->personil_id),
                ])->render();
            })
            ->rawColumns(['user_id', 'action'])
            ->make(true);
    }

    public function create()
    {
        $units = OrgUnit::orderBy('name')->get();
        return view('pages.pemtu.personils.create', compact('units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'        => 'required|string|max:100',
            'email'       => 'nullable|email|max:100',
            'org_unit_id' => 'nullable|exists:org_unit,orgunit_id',
            'jenis'       => 'nullable|string|max:20',
        ]);

        $data = $request->all();

        // Auto-link user by email
        if (! empty($data['email'])) {
            $user = User::where('email', $data['email'])->first();
            if ($user && empty($data['user_id'])) {
                $data['user_id'] = $user->id;
            }
        }

        Personil::create($data);

        return response()->json([
            'message' => 'Personil created successfully.',
        ]);
    }

    public function edit($id)
    {
        $personil = Personil::findOrFail($id);
        $units    = OrgUnit::orderBy('name')->get();
        return view('pages.pemtu.personils.edit', compact('personil', 'units'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama'        => 'required|string|max:100',
            'email'       => 'nullable|email|max:100',
            'org_unit_id' => 'nullable|exists:org_unit,orgunit_id',
            'jenis'       => 'nullable|string|max:20',
        ]);

        $personil = Personil::findOrFail($id);
        $data     = $request->all();

        // Auto-link user by email if changed and not manually set
        if (! empty($data['email']) && $data['email'] !== $personil->email) {
            $user = User::where('email', $data['email'])->first();
            if ($user) {
                $data['user_id'] = $user->id;
            }
        }

        $personil->update($data);

        return response()->json([
            'message' => 'Personil updated successfully.',
        ]);
    }

    public function destroy($id)
    {
        $personil = Personil::findOrFail($id);
        $personil->delete();

        return response()->json([
            'success' => true,
            'message' => 'Personil deleted successfully.',
        ]);
    }

    public function import(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('pages.pemtu.personils.import');
        }

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new PersonilImport, $request->file('file'));

        return response()->json([
            'message'  => 'Personils imported successfully.',
            'redirect' => route('pemtu.personils.index'),
        ]);
    }
}
