<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\JenisIzinStoreRequest;
use App\Models\Hr\JenisIzin;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class JenisIzinController extends Controller
{
    public function index()
    {
        return view('pages.hr.jenis-izin.index');
    }

    public function data(Request $request)
    {
        $query = JenisIzin::query();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('status', function ($row) {
                $badge = $row->is_active ? 'bg-green-lt' : 'bg-red-lt';
                $text  = $row->is_active ? 'Aktif' : 'Nonaktif';
                return '<span class="badge ' . $badge . '">' . $text . '</span>';
            })
            ->addColumn('action', function ($row) {
                return '
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-icon btn-primary ajax-modal-btn"
                            data-url="' . route('hr.jenis-izin.edit', $row->hashid) . '"
                            data-modal-title="Edit Jenis Izin">
                            <i class="ti ti-pencil"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-icon btn-danger btn-delete"
                            data-url="' . route('hr.jenis-izin.destroy', $row->hashid) . '">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>
                ';
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function create()
    {
        return view('pages.hr.jenis-izin.create');
    }

    public function store(JenisIzinStoreRequest $request)
    {
        $validated = $request->validated();

        try {
            JenisIzin::create($validated);
            return response()->json(['success' => true, 'message' => 'Jenis izin berhasil dibuat.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function edit(JenisIzin $jenis_izin)
    {
        return view('pages.hr.jenis-izin.edit', compact('jenis_izin'));
    }

    public function update(JenisIzinStoreRequest $request, JenisIzin $jenis_izin)
    {
        $validated = $request->validated();

        $jenis_izin->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Jenis Izin berhasil diperbarui.',
        ]);
    }

    public function destroy(JenisIzin $jenis_izin)
    {
        $jenis_izin->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jenis Izin berhasil dihapus.',
        ]);
    }
}
