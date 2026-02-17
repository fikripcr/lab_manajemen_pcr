<?php
namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\EventRequest;
use App\Models\Event\Event;
use App\Models\User;
use App\Services\Event\EventService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class EventController extends Controller
{
    public function __construct(
        protected EventService $eventService
    ) {}

    public function index()
    {
        $pageTitle = 'Manajemen Kegiatan';
        return view('pages.event.kegiatans.index', compact('pageTitle'));
    }

    public function paginate(Request $request)
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
        $pageTitle = 'Tambah Event';
        $users     = User::all();
        $event     = new Event();
        return view('pages.event.kegiatans.create-edit', compact('pageTitle', 'users', 'event'));
    }

    public function store(EventRequest $request)
    {
        try {
            $event = $this->eventService->store($request->validated());
            return response()->json([
                'success'  => true,
                'message'  => 'Kegiatan berhasil disimpan',
                'redirect' => route('event.events.index'),
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
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
            return response()->json([
                'success'  => true,
                'message'  => 'Kegiatan berhasil diperbarui',
                'redirect' => route('event.events.index'),
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy(Event $event)
    {
        try {
            $this->eventService->destroy($event);
            return response()->json(['success' => true, 'message' => 'Kegiatan berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
