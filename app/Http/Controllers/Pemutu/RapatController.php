<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\RapatRequest;
use App\Models\Pemutu\Rapat;
use App\Models\User;
use App\Services\Pemutu\RapatService;
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
        return view('pages.pemutu.rapat.index', compact('pageTitle'));
    }

    public function paginate(Request $request)
    {
        $query = Rapat::query()->with(['ketuaUser', 'notulenUser']);
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('tgl_rapat_formatted', function ($row) {
                return $row->tgl_rapat; // Format if needed
            })
            ->addColumn('action', function ($row) {
                return view('components.tabler.datatables-actions', [
                    'editUrl'   => route('pemutu.rapat.edit', $row->hashid),
                    'editModal' => false,
                    'viewUrl'   => route('pemutu.rapat.show', $row->hashid),
                    'deleteUrl' => route('pemutu.rapat.destroy', $row->hashid),
                ])->render();
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        $pageTitle = 'Tambah Rapat';
        $users     = User::all();
        return view('pages.pemutu.rapat.create', compact('pageTitle', 'users'));
    }

    public function store(RapatRequest $request)
    {
        try {
            $this->service->store($request->validated());
            return response()->json([
                'success'  => true,
                'message'  => 'Data berhasil disimpan',
                'redirect' => route('pemutu.rapat.index'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(Rapat $rapat): View
    {
        $rapat->load(['entitas', 'pesertas.user', 'agendas', 'ketuaUser', 'notulenUser', 'authorUser']);
        $pageTitle = 'Detail Rapat';
        return view('pages.pemutu.rapat.show', compact('rapat', 'pageTitle'));
    }

    public function edit(Rapat $rapat)
    {
        $pageTitle = 'Edit Rapat';
        $users     = User::all();
        return view('pages.pemutu.rapat.edit', compact('rapat', 'pageTitle', 'users'));
    }

    public function update(RapatRequest $request, Rapat $rapat)
    {
        try {
            $this->service->update($rapat, $request->validated());
            return response()->json([
                'success'  => true,
                'message'  => 'Data berhasil diperbarui',
                'redirect' => route('pemutu.rapat.index'),
            ]);
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
            'attendance.*.waktu_hadir' => 'nullable', // Can be string time or null
        ]);

        try {
            DB::beginTransaction();
            foreach ($request->attendance as $pesertaId => $data) {
                // If status is present, update it
                $peserta = $rapat->pesertas()->where('rapatpeserta_id', $pesertaId)->first();
                if ($peserta) {
                    $updateData = ['status' => $data['status']];

                    // Handle Waktu Hadir
                    // If status is hadir and waktu_hadir is provided, use it.
                    // If status is hadir and waktu_hadir is empty, use current time? Or keep null?
                    // Let's assume input type="time" or similar.
                    if ($data['status'] == 'hadir') {
                        if (! empty($data['waktu_hadir'])) {
                            // Combine rapat date with time or just save time part if column is timestamp?
                            // Migration said timestamp. So we need full date.
                            $time                      = $data['waktu_hadir'];
                            $date                      = $rapat->tgl_rapat->format('Y-m-d');
                            $dateTime                  = \Carbon\Carbon::parse("$date $time");
                            $updateData['waktu_hadir'] = $dateTime;
                        } else {
                            // If previously null and now hadir, maybe default to now()?
                            // Or leave it null
                        }
                    } else {
                        $updateData['waktu_hadir'] = null;
                    }

                    $peserta->update($updateData);
                }
            }
            DB::commit();

            return back()->with('success', 'Absensi berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui absensi: ' . $e->getMessage());
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
                    $agenda->update(['isi' => $data['isi']]);
                }
            }
            DB::commit();
            return back()->with('success', 'Agenda berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui agenda: ' . $e->getMessage());
        }
    }

    public function generatePdf(Rapat $rapat)
    {
        $rapat->load(['pesertas.user', 'agendas', 'ketuaUser', 'notulenUser', 'entitas']);

        $pdf = Pdf::loadView('pages.pemutu.rapat.pdf', compact('rapat'));
        return $pdf->download('Hasil_Rapat_' . $rapat->judul_kegiatan . '.pdf');
    }

    public function updateOfficials(Request $request, Rapat $rapat)
    {
        $request->validate([
            'ketua_user_id'   => 'required|exists:users,id',
            'notulen_user_id' => 'required|exists:users,id',
        ]);

        try {
            $rapat->update([
                'ketua_user_id'   => $request->ketua_user_id,
                'notulen_user_id' => $request->notulen_user_id,
            ]);

            return back()->with('success', 'Pejabat rapat berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui pejabat rapat: ' . $e->getMessage());
        }
    }
}
