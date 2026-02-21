<?php
namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\EventRequest;
use App\Models\Event\Event;
use App\Models\User;
use App\Services\Event\EventService;
use Yajra\DataTables\DataTables;

class EventController extends Controller
{
    public function __construct(protected EventService $eventService)
    {}

    public function index()
    {
        $pageTitle = 'Manajemen Kegiatan';
        return view('pages.event.kegiatans.index', compact('pageTitle'));
    }

    public function paginate()
    {
        $query = Event::query()->with(['pic']);
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('tanggal_info', function ($row) {
                $mulai = formatTanggalIndo($row->tanggal_mulai);
                if ($row->tanggal_selesai && $row->tanggal_selesai != $row->tanggal_mulai) {
                    return $mulai . ' - ' . formatTanggalIndo($row->tanggal_selesai);
                }
                return $mulai;
            })
            ->addColumn('action', function ($row) {
                return view('components.tabler.datatables-actions', [
                    'editUrl'   => route('Kegiatan.Kegiatans.edit', $row->hashid),
                    'editModal' => false,
                    'viewUrl'   => route('Kegiatan.Kegiatans.show', $row->hashid),
                    'deleteUrl' => route('Kegiatan.Kegiatans.destroy', $row->hashid),
                ])->render();
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        $pageTitle = 'Tambah Kegiatan';
        $users     = User::all();
        $Kegiatan  = new Event();
        return view('pages.event.kegiatans.create-edit', compact('pageTitle', 'users', 'Kegiatan'));
    }

    public function store(EventRequest $request)
    {
        try {
            $event = $this->eventService->store($request->validated());
            return jsonSuccess('Kegiatan berhasil disimpan', route('Kegiatan.Kegiatans.index'));
        } catch (\Exception $e) {
            logError($e);
            return jsonError('Gagal menyimpan kegiatan: ' . $e->getMessage());
        }
    }

    public function show(Event $Kegiatan)
    {
        $pageTitle = 'Detail Kegiatan';
        $Kegiatan->load(['pic', 'tamus', 'teams.memberable']);
        return view('pages.event.kegiatans.show', compact('pageTitle', 'Kegiatan'));
    }

    public function edit(Event $Kegiatan)
    {
        $pageTitle = 'Edit Kegiatan';
        $users     = User::all();
        return view('pages.event.kegiatans.create-edit', compact('pageTitle', 'users', 'Kegiatan'));
    }

    public function update(EventRequest $request, Event $event)
    {
        try {
            $this->eventService->update($event, $request->validated());
            return jsonSuccess('Kegiatan berhasil diperbarui', route('Kegiatan.Kegiatans.index'));
        } catch (\Exception $e) {
            logError($e);
            return jsonError('Gagal memperbarui kegiatan: ' . $e->getMessage());
        }
    }

    public function destroy(Event $event)
    {
        try {
            $this->eventService->destroy($event);
            return jsonSuccess('Kegiatan berhasil dihapus');
        } catch (\Exception $e) {
            logError($e);
            return jsonError('Gagal menghapus kegiatan: ' . $e->getMessage());
        }
    }
}
