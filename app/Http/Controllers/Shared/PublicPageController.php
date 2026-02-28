<?php
namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shared\PublicPageRequest;
use App\Models\Shared\PublicPage;
use App\Services\Shared\PublicPageService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PublicPageController extends Controller
{
    public function __construct(protected PublicPageService $pageService)
    {}

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

    public function show(PublicPage $publicPage)
    {
        return view('pages.shared.public-page.show', ['page' => $publicPage]);
    }

    public function create()
    {
        return view('pages.shared.public-page.create-edit', ['page' => new PublicPage()]);
    }

    public function store(PublicPageRequest $request)
    {
        $this->pageService->createPage($request->validated());
        return redirect()->route('shared.public-page.index')->with('success', 'Halaman berhasil dibuat.');
    }

    public function edit(PublicPage $publicPage)
    {
        return view('pages.shared.public-page.create-edit', ['page' => $publicPage]);
    }

    public function update(PublicPageRequest $request, PublicPage $publicPage)
    {
        $this->pageService->updatePage($publicPage, $request->validated());
        return redirect()->route('shared.public-page.index')->with('success', 'Halaman berhasil diperbarui.');
    }

    public function destroy(PublicPage $publicPage)
    {
        $this->pageService->deletePage($publicPage);
        return jsonSuccess('Halaman berhasil dihapus.');
    }
}
