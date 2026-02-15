<?php
namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Models\Lab\Kegiatan;
use App\Models\Lab\Lab;
use App\Services\Lab\KegiatanService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class KegiatanController extends Controller
{
    protected $service;

    public function __construct(KegiatanService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return view('pages.lab.kegiatan.index');
    }

    public function data(Request $request)
    {
        $query = $this->service->getFilteredQuery($request->all());

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('lab_nama', function ($row) {
                return $row->lab->name ?? '-';
            })
            ->addColumn('waktu', function ($row) {
                return $row->tanggal->format('d M Y') . '<br>' .
                $row->jam_mulai->format('H:i') . ' - ' . $row->jam_selesai->format('H:i');
            })
            ->editColumn('status', function ($row) {
                $badges = [
                    'pending'   => 'warning',
                    'approved'  => 'success',
                    'rejected'  => 'danger',
                    'completed' => 'info',
                ];
                $color = $badges[$row->status] ?? 'secondary';
                return "<span class='badge bg-{$color}'>" . ucfirst($row->status) . "</span>";
            })
            ->addColumn('action', function ($row) {
                return view('components.tabler.datatables-actions', [
                    'viewUrl' => route('lab.kegiatan.show', encryptId($row->kegiatan_id)),
                ])->render();
            })
            ->rawColumns(['status', 'waktu', 'action'])
            ->make(true);
    }

    public function create()
    {
        $labs = Lab::all();
        return view('pages.lab.kegiatan.create', compact('labs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'lab_id'           => 'required',
            'nama_kegiatan'    => 'required|string',
            'deskripsi'        => 'required|string',
            'tanggal'          => 'required|date|after_or_equal:today',
            'jam_mulai'        => 'required',
            'jam_selesai'      => 'required|after:jam_mulai',
            'dokumentasi_path' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048', // Surat Permohonan
        ]);

        try {
            $data           = $request->except('dokumentasi_path');
            $data['lab_id'] = decryptId($request->lab_id);

            if ($request->hasFile('dokumentasi_path')) {
                $data['dokumentasi_path'] = $request->file('dokumentasi_path')->store('kegiatan-docs', 'public');
            }

            $this->service->createBooking($data);
            return jsonSuccess('Booking berhasil diajukan', route('lab.kegiatan.index'));
        } catch (\Exception $e) {
            return jsonError('Gagal: ' . $e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        $kegiatan = Kegiatan::with(['lab', 'penyelenggara', 'approvals'])->findOrFail(decryptId($id));
        return view('pages.lab.kegiatan.show', compact('kegiatan'));
    }

    public function updateStatus(Request $request, $id)
    {
        // Admin only functionality usually
        $request->validate([
            'status'  => 'required|in:approved,rejected',
            'catatan' => 'nullable|string',
        ]);

        try {
            $this->service->updateStatus(decryptId($id), $request->status, $request->catatan);
            return jsonSuccess('Status updated');
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }
}
