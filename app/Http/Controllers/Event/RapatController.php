<?php
namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\RapatRequest;
use App\Models\Event\Rapat;
use App\Models\User;
use App\Services\Event\RapatService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class RapatController extends Controller
{
    public function __construct(
        protected RapatService $service
    ) {}

    public function index()
    {
        $pageTitle = 'Rapat Tinjauan Manajemen';
        return view('pages.event.rapat.index', compact('pageTitle'));
    }

    public function paginate(Request $request)
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
                $tgl     = formatTanggalIndo($row->tgl_rapat);
                $mulai   = $row->waktu_mulai?->format('H:i') ?? '--:--';
                $selesai = $row->waktu_selesai?->format('H:i') ?? '--:--';

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
        $pageTitle = 'Tambah Rapat';
        $users     = User::all();
        return view('pages.event.rapat.create', compact('pageTitle', 'users'));
    }

    public function store(RapatRequest $request)
    {
        try {
            $data = $request->validated();

            // Combine date and time to ensure correct database storage
            $date                  = $data['tgl_rapat'];
            $data['waktu_mulai']   = \Carbon\Carbon::parse("$date " . $data['waktu_mulai']);
            $data['waktu_selesai'] = \Carbon\Carbon::parse("$date " . $data['waktu_selesai']);

            $this->service->store($data);
            return jsonSuccess('Data berhasil disimpan', route('Kegiatan.rapat.index'));
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
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
        $users     = User::all();
        return view('pages.event.rapat.edit', compact('rapat', 'pageTitle', 'users'));
    }

    public function update(RapatRequest $request, Rapat $rapat)
    {
        try {
            $data = $request->validated();

            // Combine date and time to ensure correct database storage
            $date                  = $data['tgl_rapat'];
            $data['waktu_mulai']   = \Carbon\Carbon::parse("$date " . $data['waktu_mulai']);
            $data['waktu_selesai'] = \Carbon\Carbon::parse("$date " . $data['waktu_selesai']);

            $this->service->update($rapat, $data);
            return jsonSuccess('Data berhasil diperbarui', route('Kegiatan.rapat.index'));
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Rapat $rapat)
    {
        try {
            $this->service->destroy($rapat);
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateAttendance(Request $request, Rapat $rapat)
    {
        $request->validate([
            'attendance'               => 'required|array',
            'attendance.*.status'      => 'nullable|in:hadir,izin,sakit,alpa',
            'attendance.*.waktu_hadir' => 'nullable',
        ]);

        try {
            DB::beginTransaction();
            foreach ($request->attendance as $pesertaId => $data) {
                $peserta = $rapat->pesertas()->where('rapatpeserta_id', $pesertaId)->first();
                if ($peserta) {
                    $updateData = ['status' => $data['status']];

                    if ($data['status'] == 'hadir') {
                        if (! empty($data['waktu_hadir'])) {
                            $time                      = $data['waktu_hadir'];
                            $date                      = $rapat->tgl_rapat->format('Y-m-d');
                            $dateTime                  = \Carbon\Carbon::parse("$date $time");
                            $updateData['waktu_hadir'] = $dateTime;
                        }
                    } else {
                        $updateData['waktu_hadir'] = null;
                    }

                    $peserta->update($updateData);
                }
            }
            DB::commit();

            return redirect()->to(route('Kegiatan.rapat.show', $rapat) . '#tabs-info')->with('success', 'Absensi berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->to(route('Kegiatan.rapat.show', $rapat) . '#tabs-info')->with('error', 'Gagal memperbarui absensi: ' . $e->getMessage());
        }
    }

    public function updateAgenda(Request $request, Rapat $rapat)
    {
        $request->validate([
            'agendas'       => 'required|array',
            'agendas.*.isi' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();
            foreach ($request->agendas as $agendaId => $data) {
                $agenda = $rapat->agendas()->where('rapatagenda_id', $agendaId)->first();
                if ($agenda) {
                    $agenda->update(['isi' => $data['isi'] ?? '']);
                }
            }
            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return jsonSuccess('Agenda berhasil diperbarui secara otomatis.');
            }

            return back()->with('success', 'Agenda berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui agenda: ' . $e->getMessage(),
                ], 500);
            }
            return back()->with('error', 'Gagal memperbarui agenda: ' . $e->getMessage());
        }
    }

    public function storeAgenda(Request $request, Rapat $rapat)
    {
        $request->validate([
            'judul_agenda' => 'required|string|max:255',
        ]);

        try {
            $lastSeq = $rapat->agendas()->max('seq') ?? 0;

            $this->service->addAgenda($rapat, [
                'judul_agenda' => $request->judul_agenda,
                'isi'          => '',
                'seq'          => $lastSeq + 1,
            ]);

            return redirect()->to(route('Kegiatan.rapat.show', $rapat) . '#tabs-agenda')->with('success', 'Agenda baru berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->to(route('Kegiatan.rapat.show', $rapat) . '#tabs-agenda')->with('error', 'Gagal menambah agenda: ' . $e->getMessage());
        }
    }

    public function generatePdf(Rapat $rapat)
    {
        $rapat->load(['pesertas.user', 'agendas', 'ketua_user', 'notulen_user', 'entitas']);

        $pdf = Pdf::loadView('pages.event.rapat.pdf', compact('rapat'));
        return $pdf->download('Hasil_Rapat_' . $rapat->judul_kegiatan . '.pdf');
    }

    public function updateOfficials(Request $request, Rapat $rapat)
    {
        $request->validate([
            'ketua_user_id'   => 'required|exists:users,id',
            'notulen_user_id' => 'required|required|exists:users,id',
        ]);

        try {
            $rapat->update([
                'ketua_user_id'   => $request->ketua_user_id,
                'notulen_user_id' => $request->notulen_user_id,
            ]);

            return redirect()->to(route('Kegiatan.rapat.show', $rapat) . '#tabs-info')->with('success', 'Pejabat rapat berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->to(route('Kegiatan.rapat.show', $rapat) . '#tabs-info')->with('error', 'Gagal memperbarui pejabat rapat: ' . $e->getMessage());
        }
    }
    public function storeParticipants(Request $request, Rapat $rapat)
    {
        $request->validate([
            'user_ids'   => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'jabatan'    => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();
            foreach ($request->user_ids as $userId) {
                $exists = $rapat->pesertas()->where('user_id', $userId)->exists();
                if (! $exists) {
                    $this->service->addPeserta($rapat, [
                        'user_id' => $userId,
                        'jabatan' => $request->jabatan ?? 'Peserta',
                    ]);
                }
            }
            DB::commit();

            return redirect()->to(route('Kegiatan.rapat.show', $rapat) . '#tabs-info')->with('success', 'Peserta berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->to(route('Kegiatan.rapat.show', $rapat) . '#tabs-info')->with('error', 'Gagal menambah peserta: ' . $e->getMessage());
        }
    }
}
