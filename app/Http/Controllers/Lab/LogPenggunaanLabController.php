<?php
namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lab\LogPenggunaanLabRequest;
use App\Models\Lab\Kegiatan;
use Yajra\DataTables\DataTables;

class LogPenggunaanLabController extends Controller
{
    public function __construct(protected LogPenggunaanLabService $logPenggunaanLabService)
    {}

    public function index()
    {
        return view('pages.lab.log-lab.index');
    }

    public function data(Request $request)
    {
        $query = $this->logPenggunaanLabService->getFilteredQuery($request->all());

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('kegiatan', function ($row) {
                return $row->kegiatan ? $row->kegiatan->nama_kegiatan : '-';
            })
            ->addColumn('lab_nama', function ($row) {
                return $row->lab->name ?? '-';
            })
            ->addColumn('waktu', function ($row) {
                return $row->waktu_isi->format('d M Y H:i');
            })
            ->addColumn('peserta', function ($row) {
                return $row->nama_peserta . ($row->npm_peserta ? " ({$row->npm_peserta})" : '');
            })
            ->editColumn('kondisi', function ($row) {
                $color = $row->kondisi == 'Baik' ? 'success' : 'danger';
                return "<span class='badge bg-{$color}'>{$row->kondisi}</span>";
            })
            ->rawColumns(['kondisi'])
            ->make(true);
    }

    public function create()
    {
        // Get active kegiatans (today)
        $today           = now()->format('Y-m-d');
        $activeKegiatans = Kegiatan::whereDate('tanggal', $today)
            ->where('status', 'approved')
            ->get();

        $labs = Lab::all();
        $log  = new \App\Models\Lab\LogPenggunaanLab();
        return view('pages.lab.log-lab.create-edit-ajax', compact('activeKegiatans', 'labs', 'log'));
    }

    public function store(LogPenggunaanLabRequest $request)
    {
        try {
            $data = $request->all();

            if ($request->filled('kegiatan_id')) {
                $data['kegiatan_id'] = decryptId($request->kegiatan_id);
                $kegiatan            = Kegiatan::findOrFail($data['kegiatan_id']);
                $data['lab_id']      = $kegiatan->lab_id;
            } elseif ($request->filled('lab_id')) {
                $data['lab_id'] = decryptId($request->lab_id);
            } else {
                return jsonError('Pilih Kegiatan atau Lab');
            }

            $this->logPenggunaanLabService->createLog($data);
            return jsonSuccess('Log berhasil disimpan', route('lab.log-lab.index'));
        } catch (\Exception $e) {
            logError($e);
            return jsonError('Gagal menyimpan log: ' . $e->getMessage());
        }
    }
}
