<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\JabatanStrukturalRequest;
use App\Models\Hr\JabatanStruktural;
use App\Services\Hr\JabatanStrukturalService;
use Yajra\DataTables\Facades\DataTables;

class JabatanStrukturalController extends Controller
{
    protected $service;

    public function __construct(JabatanStrukturalService $service)
    {
        $this->service = $service;
    }
    public function index()
    {
        return view('pages.hr.jabatan-struktural.index');
    }

    public function data()
    {
        $query = JabatanStruktural::query()->with('parent')->latest();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('parent', function ($row) {
                return $row->parent ? $row->parent->nama : '-';
            })
            ->editColumn('is_active', function ($row) {
                return $row->is_active ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Non-Aktif</span>';
            })
            ->addColumn('action', function ($row) {
                return view('components.tabler.datatables-actions', [
                    'editUrl'   => route('hr.jabatan-struktural.edit', ['jabatan_struktural' => $row->hashid]),
                    'editModal' => true,
                    'deleteUrl' => route('hr.jabatan-struktural.destroy', ['jabatan_struktural' => $row->hashid]),
                ])->render();
            })
            ->rawColumns(['is_active', 'action'])
            ->make(true);
    }

    public function create()
    {
        $parents = JabatanStruktural::where('is_active', true)->pluck('nama', 'jabatan_struktural_id');
        return view('pages.hr.jabatan-struktural.create', compact('parents'));
    }

    public function store(JabatanStrukturalRequest $request)
    {
        try {
            $this->service->create($request->validated());
            return jsonSuccess('Jabatan Struktural created successfully.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function edit(JabatanStruktural $jabatan_struktural)
    {
        $jabatanStruktural = $jabatan_struktural;
        $parents           = JabatanStruktural::where('is_active', true)
            ->where('jabstruktural_id', '!=', $jabatan_struktural->jabstruktural_id)
            ->pluck('jabstruktural', 'jabstruktural_id');
        return view('pages.hr.jabatan-struktural.edit', compact('jabatanStruktural', 'parents'));
    }

    public function update(JabatanStrukturalRequest $request, JabatanStruktural $jabatan_struktural)
    {
        try {
            $this->service->update($jabatan_struktural, $request->validated());
            return jsonSuccess('Jabatan Struktural updated successfully.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function destroy(JabatanStruktural $jabatan_struktural)
    {
        try {
            $jabatan_struktural->delete();
            return jsonSuccess('Jabatan Struktural deleted successfully.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }
}
