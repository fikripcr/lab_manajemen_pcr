<?php

namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\PemantauanRequest;
use App\Models\Event\Rapat;
use App\Services\Pemutu\IndikatorService;
use App\Services\Pemutu\PemantauanService;
use App\Services\Pemutu\PeriodeSpmiService;
use Yajra\DataTables\Facades\DataTables;

class PelaksanaanController extends Controller
{
    public function __construct(
        protected IndikatorService $indikatorService,
        protected PemantauanService $pemantauanService,
        protected PeriodeSpmiService $periodeSpmiService,
    ) {}

    /**
     * Display list of Pemantauan meetings.
     */
    public function pemantauanIndex()
    {
        $siklus = $this->periodeSpmiService->getSiklusData();

        // Active Kelompok (Akademik / Non Akademik) from session
        $activeKelompok = session('pemutu_active_kelompok', 'akademik');
        $periode = $siklus[$activeKelompok] ?? null;

        return view('pages.pemutu.pemantauan.index', compact('siklus', 'periode'));
    }

    /**
     * AJAX Data for Pemantauan DataTable.
     */
    public function pemantauanData()
    {
        $query = $this->pemantauanService->getPemantauanQuery();

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('no', function ($row) {
                return '<div class="text-center font-monospace text-muted">'.($row->DT_RowIndex ?? '-').'</div>';
            })
            ->addColumn('tgl_info', function ($row) {
                return '<div>
                    <div class="fw-bold"><i class="ti ti-calendar me-1 text-muted"></i>'.($row->tgl_rapat ? $row->tgl_rapat->format('d M Y') : '-').'</div>
                    <div class="small text-muted"><i class="ti ti-clock me-1 text-muted"></i>'.($row->waktu_mulai ? $row->waktu_mulai->format('H:i') : '').' - '.($row->waktu_selesai ? $row->waktu_selesai->format('H:i') : '').'</div>
                </div>';
            })
            ->editColumn('judul_kegiatan', function ($row) {
                $ketua = $row->ketua_user?->name ?? '-';
                return '<div>
                    <div class="fw-bold">'.$row->judul_kegiatan.'</div>
                    <div class="small text-muted d-flex align-items-center gap-1">
                        <i class="ti ti-user-circle"></i> Ketua: '.$ketua.'
                    </div>
                </div>';
            })
            ->addColumn('indikator_count', function ($row) {
                // Support both short name and full class name for backward compatibility
                $count = $row->entitas()
                    ->whereIn('model', ['IndikatorOrgUnit', \App\Models\Pemutu\IndikatorOrgUnit::class])
                    ->count();

                $pesertaCount = $row->pesertas()->count();

                return '<div class="d-flex flex-column gap-1 align-items-center">
                    <span class="badge bg-blue-lt px-2">'.$count.' Indikator</span>
                    <span class="small text-muted" style="font-size: 10px;"><i class="ti ti-users me-1"></i>'.$pesertaCount.' Peserta</span>
                </div>';
            })
            ->addColumn('action', function ($row) {
                $editUrl = route('pemutu.pemantauan.edit', $row->encrypted_rapat_id);
                $detailUrl = route('Kegiatan.rapat.show', $row->encrypted_rapat_id);

                return '<div class="btn-group">
                    <a href="'.$detailUrl.'" class="btn btn-sm btn-info" title="Detail Rapat"><i class="ti ti-eye"></i></a>
                    <a href="#" class="btn btn-sm btn-primary ajax-modal-btn"
                        data-modal-size="modal-xl"
                        data-modal-title="Edit Jadwal Pemantauan"
                        data-url="'.$editUrl.'">
                        <i class="ti ti-pencil"></i>
                    </a>
                </div>';
            })
            ->rawColumns(['no', 'tgl_info', 'judul_kegiatan', 'indikator_count', 'action'])
            ->make(true);
    }

    /**
     * Show modal form to create a new Pemantauan meeting.
     */
    public function pemantauanCreate()
    {
        $users = \App\Models\User::with('pegawai.latestDataDiri')->get();

        return view('pages.pemutu.pemantauan.form', compact('users'));
    }

    /**
     * Store new Pemantauan meeting.
     */
    public function pemantauanStore(PemantauanRequest $request)
    {
        $this->pemantauanService->savePemantauan($request->validated());

        return jsonSuccess('Jadwal pemantauan berhasil dibuat.', url()->previous());
    }

    /**
     * Show modal form to edit a Pemantauan meeting.
     */
    public function pemantauanEdit(Rapat $rapat)
    {
        $users = \App\Models\User::with('pegawai.latestDataDiri')->get();

        return view('pages.pemutu.pemantauan.form', compact('rapat', 'users'));
    }

    /**
     * Update an existing Pemantauan meeting.
     */
    public function pemantauanUpdate(PemantauanRequest $request, Rapat $rapat)
    {
        $this->pemantauanService->updatePemantauan($rapat, $request->validated());

        return jsonSuccess('Jadwal pemantauan berhasil diperbarui.', url()->previous());
    }
}
