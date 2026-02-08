<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Models\Hr\Pegawai;
use App\Services\Hr\PegawaiService;
use Illuminate\Http\Request;

class KeluargaController extends Controller
{
    protected $pegawaiService;

    public function __construct(PegawaiService $pegawaiService)
    {
        $this->pegawaiService = $pegawaiService;
    }

    public function create(Pegawai $pegawai)
    {
        return view('pages.hr.pegawai.keluarga.create', compact('pegawai'));
    }

    public function store(Request $request, Pegawai $pegawai)
    {
        $data = $request->validate([
            'nama'          => 'required|string|max:100',
            'hubungan'      => 'required|string|max:50',
            'jenis_kelamin' => 'required|in:L,P',
            'tgl_lahir'     => 'nullable|date',
            'alamat'        => 'nullable|string',
            'telp'          => 'nullable|string|max:20',
        ]);

        try {
            $this->pegawaiService->requestAddition($pegawai, \App\Models\Hr\Keluarga::class, $data);
            return jsonSuccess('Data Keluarga berhasil diajukan. Menunggu persetujuan admin.', route('hr.pegawai.show', $pegawai->pegawai_id));
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }
    public function data()
    {
        $query = \App\Models\Hr\Keluarga::with('pegawai')->select('hr_keluarga.*');

        return \Yajra\DataTables\Facades\DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('pegawai_nama', function ($row) {
                return $row->pegawai->nama ?? '-';
            })
            ->editColumn('tgl_lahir', function ($row) {
                return $row->tgl_lahir ? \Carbon\Carbon::parse($row->tgl_lahir)->format('d-m-Y') : '-';
            })
            ->addColumn('action', function ($row) {
                // Actions can be added here if needed, or kept read-only for now
                return '';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
