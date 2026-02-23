<?php
namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\EventTamuRegistrationRequest;
use App\Http\Requests\Event\EventTamuRequest;
use App\Models\Event\Event;
use App\Models\Event\EventTamu;
use App\Services\Event\EventTamuService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class EventTamuController extends Controller
{
    public function __construct(protected EventTamuService $EventTamuService)
    {}

    public function index()
    {
        $pageTitle = 'Buku Tamu / Peserta Kegiatan';
        return view('pages.event.tamus.index', compact('pageTitle'));
    }

    public function paginate(Request $request)
    {
        $query = EventTamu::query()->with(['event']);
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('foto_preview', function ($row) {
                if ($row->photo_url) {
                    return '<img src="' . $row->photo_url . '" class="avatar avatar-sm" />';
                }
                return '<span class="avatar avatar-sm bg-secondary text-white">?</span>';
            })
            ->addColumn('action', function ($row) {
                return view('components.tabler.datatables-actions', [
                    'editUrl'   => route('Kegiatan.tamus.edit', $row->encrypted_eventtamu_id),
                    'editModal' => true,
                    'deleteUrl' => route('Kegiatan.tamus.destroy', $row->encrypted_eventtamu_id),
                ])->render();
            })
            ->rawColumns(['foto_preview', 'action'])
            ->make(true);
    }

    public function create()
    {
        $Kegiatans = Event::orderBy('tanggal_mulai', 'desc')->get();
        $tamu      = new EventTamu();
        return view('pages.event.tamus.create-edit-ajax', compact('Kegiatans', 'tamu'));
    }

    public function store(EventTamuRequest $request)
    {
        try {
            $this->EventTamuService->store($request->validated());
            return jsonSuccess('Data tamu berhasil disimpan');
        } catch (\Exception $e) {
            logError($e);
            return jsonError('Gagal menyimpan data tamu: ' . $e->getMessage());
        }
    }

    public function edit(EventTamu $tamu)
    {
        $Kegiatans = Event::orderBy('tanggal_mulai', 'desc')->get();
        return view('pages.event.tamus.create-edit-ajax', compact('Kegiatans', 'tamu'));
    }

    public function update(EventTamuRequest $request, EventTamu $tamu)
    {
        try {
            $this->EventTamuService->update($tamu, $request->validated());
            return jsonSuccess('Data tamu berhasil diperbarui');
        } catch (\Exception $e) {
            logError($e);
            return jsonError('Gagal memperbarui data tamu: ' . $e->getMessage());
        }
    }

    public function destroy(EventTamu $tamu)
    {
        try {
            $this->EventTamuService->destroy($tamu);
            return jsonSuccess('Data tamu berhasil dihapus');
        } catch (\Exception $e) {
            logError($e);
            return jsonError('Gagal menghapus data tamu: ' . $e->getMessage());
        }
    }

    // ─── Public Buku Tamu — Permanent hashid URL ─────────────────

    public function attendanceForm(string $hashid)
    {
        $eventId = decryptId($hashid, false);
        $event   = Event::findOrFail($eventId);

        $sukses = session('attendance_sukses');
        return view('pages.event.tamus.registration', compact('event', 'hashid', 'sukses'));
    }

    public function attendanceStore(EventTamuRegistrationRequest $request, string $hashid)
    {
        $eventId = decryptId($hashid, false);
        $event   = Event::findOrFail($eventId);

        try {
            $data                 = $request->validated();
            $data['event_id']     = $event->event_id;
            $data['waktu_datang'] = now();

            $this->EventTamuService->storeFromPublic($data);

            return redirect()->route('attendance.form', $hashid)
                ->with('attendance_sukses', true);
        } catch (\Exception $e) {
            logError($e);
            return back()->withInput()->withErrors(['error' => 'Gagal menyimpan: ' . $e->getMessage()]);
        }
    }

    // ─── Legacy ──────────────────────────────────────────────────
    public function registration($hashid)
    {
        $kegiatan = Event::findOrFailByHashid($hashid);
        return view('pages.event.kegiatans.registration', compact('kegiatan'));
    }

    public function storeRegistration(EventTamuRegistrationRequest $request, $hashid)
    {
        $kegiatan  = Event::findOrFailByHashid($hashid);
        $validated = $request->validated();

        $validated['event_id']     = $kegiatan->event_id;
        $validated['waktu_datang'] = now();

        try {
            $this->EventTamuService->storeFromPublic($validated);
            return jsonSuccess('Terima kasih, data Anda telah berhasil disimpan.');
        } catch (\Exception $e) {
            logError($e);
            return jsonError('Gagal menyimpan pendaftaran: ' . $e->getMessage());
        }
    }

    // ─── Helper ──────────────────────────────────────────────────

    private function getRegistrationStatus(Event $event): string
    {
        // Check session sukses
        if (session('sukses')) {
            return 'sukses';
        }

        $now     = Carbon::now();
        $mulai   = $event->tanggal_mulai ? Carbon::parse($event->tanggal_mulai)->startOfDay() : null;
        $selesai = $event->tanggal_selesai ? Carbon::parse($event->tanggal_selesai)->endOfDay()->addDay() : null;

        if ($mulai && $now->lt($mulai)) {
            return 'belum';
        }

        if ($selesai && $now->gt($selesai)) {
            return 'tutup';
        }

        return 'aktif';
    }
}
