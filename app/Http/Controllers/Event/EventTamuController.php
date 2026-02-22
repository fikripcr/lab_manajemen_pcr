<?php
namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\EventTamuRequest;
use App\Models\Event\Event;
use App\Models\Event\EventTamu;
use App\Services\Event\EventTamuService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class EventTamuController extends Controller
{
    public function __construct(protected EventTamuService $eventTamuService)
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
            ->​addColumn('action', function ($row) {
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
            $this->eventTamuService->store($request->validated());
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
            $this->eventTamuService->update($tamu, $request->validated());
            return jsonSuccess('Data tamu berhasil diperbarui');
        } catch (\Exception $e) {
            logError($e);
            return jsonError('Gagal memperbarui data tamu: ' . $e->getMessage());
        }
    }

    public function destroy(EventTamu $tamu)
    {
        try {
            $this->eventTamuService->destroy($tamu);
            return jsonSuccess('Data tamu berhasil dihapus');
        } catch (\Exception $e) {
            logError($e);
            return jsonError('Gagal menghapus data tamu: ' . $e->getMessage());
        }
    }

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
            $this->eventTamuService->storeFromPublic($validated);
            return jsonSuccess('Terima kasih, data Anda telah berhasil disimpan.');
        } catch (\Exception $e) {
            logError($e);
            return jsonError('Gagal menyimpan pendaftaran: ' . $e->getMessage());
        }
    }
}
