<?php
namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shared\PublicPageRequest;
use App\Models\Shared\PublicPage;
use App\Services\Shared\PublicPageService;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PublicPageController extends Controller
{
    protected $pageService;

    public function __construct(PublicPageService $pageService)
    {
        $this->pageService = $pageService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = $this->pageService->getFilteredQuery($request->all());
            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('is_published', function ($row) {
                    return $row->is_published
                        ? '<span class="badge bg-success-lt">Published</span>'
                        : '<span class="badge bg-orange-lt">Draft</span>';
                })
                ->editColumn('updated_at', function ($row) {
                    return $row->updated_at->format('d M Y H:i');
                })
                ->addColumn('action', function ($row) {
                    $viewBtn   = '<a href="' . route('shared.public-page.show', $row->hashid) . '" class="btn btn-icon btn-ghost-info" title="Lihat"><i class="ti ti-eye"></i></a>';
                    $editBtn   = '<a href="' . route('shared.public-page.edit', $row->hashid) . '" class="btn btn-icon btn-ghost-primary" title="Edit"><i class="ti ti-pencil"></i></a>';
                    $deleteBtn = '<button type="button" class="btn btn-icon btn-ghost-danger ajax-delete" data-url="' . route('shared.public-page.destroy', $row->hashid) . '" data-title="Hapus Halaman?" title="Hapus"><i class="ti ti-trash"></i></button>';

                    return '<div class="btn-group btn-group-sm" role="group">' . $viewBtn . $editBtn . $deleteBtn . '</div>';
                })
                ->rawColumns(['is_published', 'action'])
                ->make(true);
        }

        return view('pages.shared.public-page.index');
    }

    public function show(PublicPage $public_page)
    {
        return view('pages.shared.public-page.show', ['page' => $public_page]);
    }

    public function create()
    {
        return view('pages.shared.public-page.create-edit', ['page' => new PublicPage()]);
    }

    public function store(PublicPageRequest $request)
    {
        try {
            $this->pageService->createPage($request->validated());
            return redirect()->route('shared.public-page.index')->with('success', 'Halaman berhasil dibuat.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function edit(PublicPage $public_page)
    {
        return view('pages.shared.public-page.create-edit', ['page' => $public_page]);
    }

    public function update(PublicPageRequest $request, PublicPage $public_page)
    {
        try {
            $this->pageService->updatePage($public_page, $request->validated());
            return redirect()->route('shared.public-page.index')->with('success', 'Halaman berhasil diperbarui.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function destroy(PublicPage $public_page)
    {
        try {
            $this->pageService->deletePage($public_page);
            return jsonSuccess('Halaman berhasil dihapus.');
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }
}
