<?php
namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Models\Lab\Kegiatan;
use App\Models\Lab\Lab;
use App\Services\Lab\LogPenggunaanLabService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class LogPenggunaanLabController extends Controller
{
    protected $service;

    public function __construct(LogPenggunaanLabService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return view('pages.lab.log-lab.index');
    }

    public function data(Request $request)
    {
        $query = $this->service->getFilteredQuery($request->all());

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
        return view('pages.lab.log-lab.create', compact('activeKegiatans', 'labs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_peserta' => 'required|string',
            'lab_id'       => 'required_without:kegiatan_id',
            'kegiatan_id'  => 'nullable', // If selected, lab_id can be inferred but let's require logic
            'nomor_pc'     => 'nullable|integer',
            'kondisi'      => 'required|string',
        ]);

        try {
            $data = $request->all();

            if ($request->filled('kegiatan_id')) {
                $data['kegiatan_id'] = decryptId($request->kegiatan_id);
                // Auto fill lab_id from kegiatan if not provided?
                $kegiatan       = Kegiatan::find($data['kegiatan_id']);
                $data['lab_id'] = $kegiatan->lab_id; // Priority to event's lab
            } elseif ($request->filled('lab_id')) {
                $data['lab_id'] = decryptId($request->lab_id);
            } else {
                return jsonError('Pilih Kegiatan atau Lab', 422);
            }

            $this->service->createLog($data);
            return jsonSuccess('Log berhasil disimpan', route('lab.log-lab.index'));
        } catch (\Exception $e) {
            return jsonError('Gagal: ' . $e->getMessage(), 500);
        }
    }
}
