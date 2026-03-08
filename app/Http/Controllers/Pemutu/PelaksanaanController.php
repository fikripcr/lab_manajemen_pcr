<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\PemantauanRequest;
use App\Models\Event\Rapat;
use App\Services\Pemutu\PelaksanaanService;
use Yajra\DataTables\Facades\DataTables;

class PelaksanaanController extends Controller
{
    public function __construct(
        protected PelaksanaanService $PelaksanaanService,
    ) {}

    /**
     * Display list of Pemantauan meetings.
     */
    public function pemantauanIndex()
    {
        return view('pages.pemutu.pelaksanaan.pemantauan.index');
    }

    /**
     * AJAX Data for Pemantauan DataTable.
     */
    public function pemantauanData()
    {
        $query = $this->PelaksanaanService->getPemantauanQuery();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('tgl_info', function ($row) {
                return '<div>
                    <div class="fw-bold">' . ($row->tgl_rapat ? $row->tgl_rapat->format('d M Y') : '-') . '</div>
                    <div class="small text-muted">' . ($row->waktu_mulai ? $row->waktu_mulai->format('H:i') : '') . ' - ' . ($row->waktu_selesai ? $row->waktu_selesai->format('H:i') : '') . '</div>
                </div>';
            })
            ->addColumn('indikator_count', function ($row) {
                $count = $row->entitas()->where('model', 'IndikatorOrgUnit')->count();
                return '<span class="badge bg-blue-lt">' . $count . ' Indikator</span>';
            })
            ->addColumn('action', function ($row) {
                $editUrl   = route('pemutu.pelaksanaan.pemantauan.edit', $row->encrypted_rapat_id);
                $detailUrl = route('Kegiatan.rapat.show', $row->encrypted_rapat_id);

                return '<div class="btn-group">
                    <a href="' . $detailUrl . '" class="btn btn-sm btn-info" title="Detail Rapat"><i class="ti ti-eye"></i></a>
                    <a href="#" class="btn btn-sm btn-primary ajax-modal-btn"
                        data-modal-size="modal-lg"
                        data-modal-title="Edit Jadwal Pemantauan"
                        data-url="' . $editUrl . '">
                        <i class="ti ti-pencil"></i>
                    </a>
                </div>';
            })
            ->rawColumns(['tgl_info', 'indikator_count', 'action'])
            ->make(true);
    }

    /**
     * Show modal form to create a new Pemantauan meeting.
     */
    public function pemantauanCreate()
    {
        $users = $this->PelaksanaanService->getUsersForSelect();

        return view('pages.pemutu.pelaksanaan.pemantauan.form', compact('users'));
    }

    /**
     * Store new Pemantauan meeting.
     */
    public function pemantauanStore(PemantauanRequest $request)
    {
        $this->PelaksanaanService->createPemantauan($request->validated());

        return jsonSuccess('Jadwal pemantauan berhasil dibuat.');
    }

    /**
     * Show modal form to edit a Pemantauan meeting.
     */
    public function pemantauanEdit(Rapat $rapat)
    {
        $users = $this->PelaksanaanService->getUsersForSelect();

        return view('pages.pemutu.pelaksanaan.pemantauan.form', compact('rapat', 'users'));
    }

    /**
     * Update an existing Pemantauan meeting.
     */
    public function pemantauanUpdate(PemantauanRequest $request, Rapat $rapat)
    {
        $this->PelaksanaanService->updatePemantauan($rapat, $request->validated());

        return jsonSuccess('Jadwal pemantauan berhasil diperbarui.');
    }
}
