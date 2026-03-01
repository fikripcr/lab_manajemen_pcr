<?php
namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\RapatAttendanceRequest;
use App\Http\Requests\Event\RapatBulkAgendaRequest;
use App\Http\Requests\Event\RapatBulkPesertaRequest;
use App\Http\Requests\Event\RapatOfficialsRequest;
use App\Http\Requests\Event\RapatRequest;
use App\Http\Requests\Event\RapatStoreAgendaRequest;
use App\Models\Event\Rapat;
use App\Models\Event\RapatPeserta;
use App\Models\User;
use App\Services\Event\RapatService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class RapatController extends Controller
{
    public function __construct(protected RapatService $RapatService)
    {}

    public function index()
    {
        $pageTitle = 'Rapat Tinjauan Manajemen';
        return view('pages.event.rapat.index', compact('pageTitle'));
    }

    public function data()
    {
        $query = Rapat::query()->with(['ketua_user', 'notulen_user']);
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('rapat_info', function ($row) {
                $html  = '<div class="font-weight-medium">' . e($row->judul_kegiatan) . '</div>';
                $html .= '<div class="text-muted small">' . e($row->jenis_rapat) . ' &bull; ' . e($row->tempat_rapat) . '</div>';
                return $html;
            })
            ->addColumn('waktu_info', function ($row) {
                $tgl      = formatTanggalIndo($row->tgl_rapat);
                $mulai    = $row->waktu_mulai?->format('H:i') ?? '--:--';
                $selesai  = $row->waktu_selesai?->format('H:i') ?? '--:--';
                $duration = '';
                if ($row->waktu_mulai && $row->waktu_selesai) {
                    $diff     = $row->waktu_mulai->diffInMinutes($row->waktu_selesai);
                    $hours    = floor($diff / 60);
                    $mins     = $diff % 60;
                    $duration = ' (';
                    if ($hours > 0) {
                        $duration .= $hours . ' jam ';
                    }

                    if ($mins > 0) {
                        $duration .= $mins . ' mnt';
                    }

                    $duration .= ')';
                }
                return '<div>' . $tgl . '</div>
                        <div class="text-muted small">' . $mulai . ' - ' . $selesai . '<span class="text-purple">' . $duration . '</span></div>';
            })
            ->addColumn('pejabat_info', function ($row) {
                $html  = '<div class="small"><strong>Ketua:</strong> ' . ($row->ketua_user->name ?? '<span class="text-danger fst-italic">N/A</span>') . '</div>';
                $html .= '<div class="small"><strong>Notulen:</strong> ' . ($row->notulen_user->name ?? '<span class="text-danger fst-italic">N/A</span>') . '</div>';
                return $html;
            })
            ->addColumn('action', function ($row) {
                return view('components.tabler.datatables-actions', [
                    'editUrl'   => route('Kegiatan.rapat.edit', $row->hashid),
                    'editModal' => false,
                    'viewUrl'   => route('Kegiatan.rapat.show', $row->hashid),
                    'deleteUrl' => route('Kegiatan.rapat.destroy', $row->hashid),
                ])->render();
            })
            ->rawColumns(['rapat_info', 'waktu_info', 'pejabat_info', 'action'])
            ->make(true);
    }

    public function create()
    {
        $pageTitle        = 'Jadwalkan Rapat';
        $rapat            = new Rapat();
        $users            = User::with('pegawai.latestDataDiri')->get();
        $defaultDate      = now()->format('Y-m-d');
        $defaultStartTime = now()->format('H:i');
        $defaultEndTime   = now()->addHours(2)->format('H:i');

        return view('pages.event.rapat.create-edit-ajax', compact('pageTitle', 'users', 'rapat', 'defaultDate', 'defaultStartTime', 'defaultEndTime'));
    }

    public function store(RapatRequest $request)
    {
        $data                   = $request->validated();
        $data['author_user_id'] = auth()->id();

        $rapat = $this->RapatService->store($data);

        if ($request->has('agendas') && is_array($request->agendas)) {
            foreach ($request->agendas as $index => $agendaItem) {
                if (! empty($agendaItem['judul_agenda'])) {
                    $this->RapatService->addAgenda($rapat, [
                        'judul_agenda' => $agendaItem['judul_agenda'],
                        'isi'          => $agendaItem['isi'] ?? '',
                        'seq'          => $index,
                    ]);
                }
            }
        }

        if ($request->has('participants') && is_array($request->participants)) {
            $this->RapatService->inviteParticipants(
                $rapat,
                $request->participants,
                $request->input('jabatan_peserta', 'Peserta')
            );
        }

        return jsonSuccess('Data berhasil disimpan', route('Kegiatan.rapat.index'));
    }

    public function show(Rapat $rapat): View
    {
        $rapat->load(['entitas', 'pesertas.user', 'agendas', 'ketua_user', 'notulen_user', 'author_user']);
        $pageTitle = 'Detail Rapat';
        return view('pages.event.rapat.show', compact('rapat', 'pageTitle'));
    }

    public function edit(Rapat $rapat)
    {
        $pageTitle = 'Edit Rapat';
        $users     = User::with('pegawai.latestDataDiri')->get();
        $rapat->load(['agendas', 'pesertas.user']);
        return view('pages.event.rapat.create-edit-ajax', compact('rapat', 'pageTitle', 'users'));
    }

    public function update(RapatRequest $request, Rapat $rapat)
    {
        $this->RapatService->update($rapat, $request->validated());

        return jsonSuccess('Data berhasil diperbarui', route('Kegiatan.rapat.index'));
    }

    public function destroy(Rapat $rapat)
    {
        $this->RapatService->destroy($rapat);
        return jsonSuccess('Data berhasil dihapus');
    }

    public function resendInvitation(RapatPeserta $peserta)
    {
        $success = $this->RapatService->resendInvitation($peserta);
        return $success
            ? jsonSuccess('Undangan email berhasil dikirim ulang.')
            : jsonError('Gagal mengirim ulang undangan email.');
    }

    public function toggleAttendance(RapatPeserta $peserta)
    {
        $updated = $this->RapatService->toggleAttendance($peserta);
        return response()->json([
            'success'     => true,
            'status'      => $updated->status,
            'waktu_hadir' => $updated->waktu_hadir?->format('H:i'),
            'updated_at'  => $updated->updated_at?->format('H:i d/m'),
        ]);
    }

    public function updateAttendance(RapatAttendanceRequest $request, Rapat $rapat)
    {
        $this->RapatService->updateAttendance($rapat, $request->validated()['attendance']);

        return redirect()->to(route('Kegiatan.rapat.show', $rapat) . '#tabs-info')->with('success', 'Absensi berhasil diperbarui.');
    }

    public function updateAgenda(RapatBulkAgendaRequest $request, Rapat $rapat)
    {
        $this->RapatService->updateAgendas($rapat, $request->agendas);

        if ($request->ajax() || $request->wantsJson()) {
            return jsonSuccess('Agenda berhasil diperbarui secara otomatis.');
        }
        return back()->with('success', 'Agenda berhasil diperbarui.');
    }

    public function storeAgenda(RapatStoreAgendaRequest $request, Rapat $rapat)
    {

        $lastSeq = $rapat->agendas()->max('seq') ?? 0;
        $this->RapatService->addAgenda($rapat, [
            'judul_agenda' => $request->judul_agenda,
            'isi'          => '',
            'seq'          => $lastSeq + 1,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return jsonSuccess('Agenda baru berhasil ditambahkan.');
        }
        return redirect()->to(route('Kegiatan.rapat.show', $rapat) . '#tabs-agenda')->with('success', 'Agenda baru berhasil ditambahkan.');
    }

    public function generatePdf(Rapat $rapat)
    {
        $rapat->load(['pesertas.user', 'agendas', 'ketua_user', 'notulen_user', 'entitas']);
        $pdf = Pdf::loadView('pages.event.rapat.pdf', compact('rapat'));
        return $pdf->download('Hasil_Rapat_' . $rapat->judul_kegiatan . '.pdf');
    }

    public function editOfficials(Rapat $rapat)
    {
        $users = User::with('pegawai.latestDataDiri')->get();
        return view('pages.event.rapat.set_officials_form', compact('rapat', 'users'));
    }

    public function updateOfficials(RapatOfficialsRequest $request, Rapat $rapat)
    {
        $rapat->update($request->validated());
        return redirect()->to(route('Kegiatan.rapat.show', $rapat) . '#tabs-info')->with('success', 'Pejabat rapat berhasil diperbarui.');
    }

    public function createParticipants(Rapat $rapat)
    {
        $users = User::with('pegawai.latestDataDiri')->get();
        return view('pages.event.rapat.add_participants_form', compact('rapat', 'users'));
    }

    public function storeParticipants(RapatBulkPesertaRequest $request, Rapat $rapat)
    {
        $this->RapatService->bulkAddPeserta($rapat, $request->validated());
        return redirect()->to(route('Kegiatan.rapat.show', $rapat) . '#tabs-info')->with('success', 'Peserta berhasil ditambahkan.');
    }
}
