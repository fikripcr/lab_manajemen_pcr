<?php
namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lab\PengumumanRequest;
use App\Models\User;
use App\Services\Lab\PengumumanService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PengumumanController extends Controller
{
    protected $pengumumanService;

    public function __construct(PengumumanService $pengumumanService)
    {
        $this->pengumumanService = $pengumumanService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('pages.lab.pengumuman.index', ['type' => 'pengumuman']);
    }

    /**
     * Display a listing of berita.
     */
    public function beritaIndex(Request $request)
    {
        return view('pages.lab.pengumuman.index', ['type' => 'berita']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($type = 'pengumuman')
    {
        $penulisOptions = User::all();
        return view('pages.lab.pengumuman.create', compact('type', 'penulisOptions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PengumumanRequest $request)
    {
        try {
            // Prepare data including files
            $data = $request->validated();
            if ($request->hasFile('cover')) {
                $data['cover'] = $request->file('cover');
            }
            if ($request->hasFile('attachments')) {
                $data['attachments'] = $request->file('attachments');
            }

            $pengumuman = $this->pengumumanService->createPengumuman($data);

            $redirectRoute = $pengumuman->jenis === 'pengumuman' ? 'pengumuman.index' : 'berita.index';

            return jsonSuccess(ucfirst($pengumuman->jenis) . ' created successfully.', route($redirectRoute));
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $realId = decryptId($id);

        $pengumuman = $this->pengumumanService->getPengumumanById($realId); // Uses Service
        if (! $pengumuman) {
            abort(404);
        }

        return view('pages.lab.pengumuman.show', compact('pengumuman'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $realId = decryptId($id);

        $pengumuman = $this->pengumumanService->getPengumumanById($realId);
        if (! $pengumuman) {
            abort(404);
        }

        $penulisOptions = User::all();
        $type           = $pengumuman->jenis;

        return view('pages.lab.pengumuman.edit', compact('pengumuman', 'type', 'penulisOptions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PengumumanRequest $request, $id)
    {
        $realId = decryptId($id);

        try {
            $data = $request->validated();

            if ($request->hasFile('cover')) {
                $data['cover'] = $request->file('cover');
            }
            if ($request->hasFile('attachments')) {
                $data['attachments'] = $request->file('attachments');
            }

            $this->pengumumanService->updatePengumuman($realId, $data);

            // Fetch updated model to determine redirect route (or store type in hidden field, but fetch is safer)
            $pengumuman    = $this->pengumumanService->getPengumumanById($realId);
            $redirectRoute = $pengumuman->jenis === 'pengumuman' ? 'pengumuman.index' : 'berita.index';

            return jsonSuccess(ucfirst($pengumuman->jenis) . ' updated successfully.', route($redirectRoute));
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $realId = decryptId($id);
            // Get type before deleting for redirect
            $pengumuman = $this->pengumumanService->getPengumumanById($realId);
            if (! $pengumuman) {
                abort(404);
            }

            $jenis = $pengumuman->jenis;

            $this->pengumumanService->deletePengumuman($realId);

            $redirectRoute = $jenis === 'pengumuman' ? 'pengumuman.index' : 'berita.index';
            return jsonSuccess(ucfirst($jenis) . ' deleted successfully.', route($redirectRoute));

        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    /**
     * Process datatables ajax request.
     */
    public function paginate(Request $request)
    {
        // Determine the type based on the route name
        $routeName = $request->route()->getName();
        // Route naming convention check: assumes 'lab.berita.data' for berita
        // If route('lab.pengumuman.data', it's pengumuman.
        // Or pass type as parameter?
        // Existing code checked route name.
        $type = ($routeName === 'berita.data' || $request->type === 'berita') ? 'berita' : 'pengumuman';

        // Use Service Query
        $query = $this->pengumumanService->getFilteredQuery($type);

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('judul', function ($item) {
                $encryptedId = encryptId($item->pengumuman_id);
                $routePrefix = $item->jenis === 'berita' ? 'berita' : 'pengumuman';
                return '<a href="' . route($routePrefix . '.show', $encryptedId) . '" class="fw-medium">' . e($item->judul) . '</a>';
            })
            ->addColumn('cover', function ($item) {
                // Use accessor if available, or empty check
                $url = $item->cover_small_url ?? '';
                if (! $url) {
                    return '';
                }

                return '<img src="' . $url . '" class="rounded" style="width: 40px; height: 40px; object-fit: cover;">';
            })
            ->addColumn('author', function ($item) {
                return $item->penulis ? $item->penulis->name : '-';
            })
            ->editColumn('is_published', function ($item) {
                $status = $item->is_published ? 'Published' : 'Draft';
                $class  = $item->is_published ? 'bg-label-success' : 'bg-label-warning';
                return '<span class="badge ' . $class . '">' . $status . '</span>';
            })
            ->editColumn('created_at', function ($item) {
                return formatTanggalIndo($item->created_at);
            })
            ->addColumn('action', function ($item) {
                $encryptedId = encryptId($item->pengumuman_id);
                $routePrefix = $item->jenis === 'berita' ? 'berita' : 'pengumuman';

                return view('components.tabler.datatables-actions', [
                    'editUrl'   => route($routePrefix . '.edit', $encryptedId),
                    'editModal' => false,
                    'viewUrl'   => route($routePrefix . '.show', $encryptedId),
                    'deleteUrl' => route($routePrefix . '.destroy', $encryptedId),
                ])->render();
            })
            ->rawColumns(['judul', 'is_published', 'cover', 'action'])
            ->make(true);
    }
}
