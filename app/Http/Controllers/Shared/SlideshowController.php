<?php
namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shared\ReorderRequest;
use App\Http\Requests\Shared\SlideshowRequest;
use App\Models\Shared\Slideshow;
use App\Services\Shared\SlideshowService;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SlideshowController extends Controller
{
    public function __construct(protected SlideshowService $slideshowService)
    {}

    public function index()
    {
        $slideshows = Slideshow::orderBy('seq')->get();
        return view('pages.shared.slideshow.index', compact('slideshows'));
    }

    public function reorder(ReorderRequest $request)
    {
        try {
            $order = $request->validated()['order'] ?? [];
            if ($order) {
                $this->slideshowService->reorderSlideshows($order);
                return jsonSuccess('Urutan slideshow berhasil diperbarui.');
            }
            return jsonError('Data urutan tidak valid.');
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal memperbarui urutan slideshow: ' . $e->getMessage());
        }
    }

    public function paginate(Request $request)
    {
        $query = $this->slideshowService->getFilteredQuery($request->all());
        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('image_url', function ($row) {
                return '<img src="' . $row->image_url . '" class="img-fluid rounded shadow-sm" style="max-height: 50px;" />';
            })
            ->editColumn('is_active', function ($row) {
                return $row->is_active
                    ? '<span class="badge bg-success text-white">Aktif</span>'
                    : '<span class="badge bg-secondary text-white">Draft</span>';
            })
            ->addColumn('action', function ($row) {
                return view('components.tabler.datatables-actions', [
                    'editUrl'   => route('shared.slideshow.edit', $row->encrypted_slideshow_id),
                    'editModal' => true,
                    'deleteUrl' => route('shared.slideshow.destroy', $row->encrypted_slideshow_id),
                ])->render();
            })
            ->rawColumns(['image_url', 'is_active', 'action'])
            ->make(true);
    }

    public function create()
    {
        return view('pages.shared.slideshow.create-edit-ajax', ['slideshow' => new Slideshow()]);
    }

    public function store(SlideshowRequest $request)
    {
        try {
            $this->slideshowService->createSlideshow($request->validated());
            return jsonSuccess('Slideshow berhasil ditambahkan.', route('shared.slideshow.index'));
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal menambahkan slideshow: ' . $e->getMessage());
        }
    }

    public function edit(Slideshow $slideshow)
    {
        return view('pages.shared.slideshow.create-edit-ajax', compact('slideshow'));
    }

    public function update(SlideshowRequest $request, Slideshow $slideshow)
    {
        try {
            $this->slideshowService->updateSlideshow($slideshow, $request->validated());
            return jsonSuccess('Slideshow berhasil diperbarui.', route('shared.slideshow.index'));
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal memperbarui slideshow: ' . $e->getMessage());
        }
    }

    public function destroy(Slideshow $slideshow)
    {
        try {
            $this->slideshowService->deleteSlideshow($slideshow);
            return jsonSuccess('Slideshow berhasil dihapus.', route('shared.slideshow.index'));
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal menghapus slideshow: ' . $e->getMessage());
        }
    }
}
