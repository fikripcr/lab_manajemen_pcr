<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\JenisIzinStoreRequest;
use App\Models\Hr\JenisIzin;
use App\Services\Hr\JenisIzinService;
use Yajra\DataTables\Facades\DataTables;

class JenisIzinController extends Controller
{
    public function __construct(protected JenisIzinService $jenisIzinService)
    {}

    public function index()
    {
        return view('pages.hr.jenis-izin.index');
    }

    public function data()
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
                return view('pages.hr.jenis_izin._action', compact('row'))->render();
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function create()
    {
        $jenis_izin = new JenisIzin();
        return view('pages.hr.jenis-izin.create-edit-ajax', compact('jenis_izin'));
    }

    public function store(JenisIzinStoreRequest $request)
    {
        $this->jenisIzinService->store($request->validated());
        return jsonSuccess('Jenis izin berhasil dibuat.');
    }

    public function edit(JenisIzin $jenisIzin)
    {
        return view('pages.hr.jenis-izin.create-edit-ajax', compact('jenisIzin'));
    }

    public function update(JenisIzinStoreRequest $request, JenisIzin $jenisIzin)
    {
        $this->jenisIzinService->update($jenisIzin, $request->validated());
        return jsonSuccess('Jenis Izin berhasil diperbarui.');
    }

    public function destroy(JenisIzin $jenisIzin)
    {
        $this->jenisIzinService->delete($jenisIzin);
        return jsonSuccess('Jenis Izin berhasil dihapus.');
    }
}
