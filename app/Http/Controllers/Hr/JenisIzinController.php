<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
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

    public function store(Request $request)
    {
        $request->validate([
            'nama'            => 'required|string|max:50',
            'kategori'        => 'nullable|string|max:10',
            'max_hari'        => 'nullable|integer',
            'pemilihan_waktu' => 'nullable|string|max:20',
        ]);

        JenisIzin::create([
            'nama'            => $request->nama,
            'kategori'        => $request->kategori,
            'max_hari'        => $request->max_hari,
            'pemilihan_waktu' => $request->pemilihan_waktu,
            'is_active'       => 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Jenis Izin berhasil ditambahkan.',
        ]);
    }

    public function edit(JenisIzin $jenis_izin)
    {
        return view('pages.hr.jenis-izin.edit', compact('jenis_izin'));
    }

    public function update(Request $request, JenisIzin $jenis_izin)
    {
        $request->validate([
            'nama'            => 'required|string|max:50',
            'kategori'        => 'nullable|string|max:10',
            'max_hari'        => 'nullable|integer',
            'pemilihan_waktu' => 'nullable|string|max:20',
            'is_active'       => 'required|boolean',
        ]);

        $jenis_izin->update($request->only([
            'nama', 'kategori', 'max_hari', 'pemilihan_waktu', 'is_active',
        ]));

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
