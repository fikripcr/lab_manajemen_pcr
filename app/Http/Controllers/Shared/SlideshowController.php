<?php
namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shared\SlideshowRequest;
use App\Models\Shared\Slideshow;
use App\Services\Shared\SlideshowService;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SlideshowController extends Controller
{
    protected $SlideshowService;

    public function __construct(SlideshowService $SlideshowService)
    {
        $this->SlideshowService = $SlideshowService;
    }

    public function index()
    {
        $slideshows = Slideshow::orderBy('seq')->get();
        return view('pages.shared.slideshow.index', compact('slideshows'));
    }

    public function reorder(Request $request)
    {
        $order = $request->input('order');
        if ($order && is_array($order)) {
            foreach ($order as $index => $hashid) {
                $id = decryptIdIfEncrypted($hashid, false);
                if ($id) {
                    Slideshow::where('id', $id)->update(['seq' => $index + 1]);
                }
            }
            return jsonSuccess('Urutan slideshow berhasil diperbarui.');
        }
        return jsonError('Data urutan tidak valid.');
    }

    public function paginate(Request $request)
    {
        $query = $this->SlideshowService->getFilteredQuery($request->all());
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
                    'editUrl'   => route('shared.slideshow.edit', $row->hashid),
                    'editModal' => true,
                    'deleteUrl' => route('shared.slideshow.destroy', $row->hashid),
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
            $this->SlideshowService->createSlideshow($request->validated());
            return jsonSuccess('Slideshow berhasil ditambahkan.');
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function edit($id)
    {
        $id        = decryptIdIfEncrypted($id);
        $slideshow = Slideshow::findOrFail($id);
        return view('pages.shared.slideshow.create-edit-ajax', compact('slideshow'));
    }

    public function update(SlideshowRequest $request, $id)
    {
        $id = decryptIdIfEncrypted($id);
        try {
            $this->SlideshowService->updateSlideshow($id, $request->validated());
            return jsonSuccess('Slideshow berhasil diperbarui.');
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        $id = decryptIdIfEncrypted($id);
        try {
            $this->SlideshowService->deleteSlideshow($id);
            return jsonSuccess('Slideshow berhasil dihapus.');
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }
}
