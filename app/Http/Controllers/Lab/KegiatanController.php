<?php
namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lab\KegiatanRequest;
use App\Models\Lab\Kegiatan;
use Yajra\DataTables\DataTables;

class KegiatanController extends Controller
{
    public function __construct(protected KegiatanService $kegiatanService)
    {}

    public function index()
    {
        return view('pages.lab.kegiatan.index');
    }

    public function data(Request $request)
    {
        $query = $this->kegiatanService->getFilteredQuery($request->all());

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
                    'viewUrl' => route('lab.kegiatan.show', $row->encrypted_kegiatan_id),
                ])->render();
            })
            ->rawColumns(['status', 'waktu', 'action'])
            ->make(true);
    }

    public function create()
    {
        $labs     = Lab::all();
        $kegiatan = new Kegiatan();
        return view('pages.lab.kegiatan.create-edit-ajax', compact('labs', 'kegiatan'));
    }

    public function store(KegiatanRequest $request)
    {
        try {
            $data           = $request->except('dokumentasi_path');
            $data['lab_id'] = decryptId($request->lab_id);

            if ($request->hasFile('dokumentasi_path')) {
                $data['dokumentasi_path'] = $request->file('dokumentasi_path')->store('kegiatan-docs', 'public');
            }

            $this->kegiatanService->createBooking($data);
            return jsonSuccess('Booking berhasil diajukan', route('lab.kegiatan.index'));
        } catch (\Exception $e) {
            logError($e);
            return jsonError('Gagal melakukan booking: ' . $e->getMessage());
        }
    }

    public function show(Kegiatan $kegiatan)
    {
        $kegiatan->load(['lab', 'penyelenggara', 'approvals']);
        return view('pages.lab.kegiatan.show', compact('kegiatan'));
    }

    public function updateStatus(Request $request, Kegiatan $kegiatan)
    {
        // Admin only functionality usually
        $request->validate([
            'status'  => 'required|in:approved,rejected',
            'catatan' => 'nullable|string',
        ]);

        try {
            $this->kegiatanService->updateStatus($kegiatan, $request->status, $request->catatan);
            return jsonSuccess('Status updated');
        } catch (\Exception $e) {
            logError($e);
            return jsonError('Gagal memperbarui status: ' . $e->getMessage());
        }
    }
}
