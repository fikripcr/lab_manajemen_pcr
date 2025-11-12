<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\PengumumanRequest;
use App\Models\Pengumuman;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;

class PengumumanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $pengumumen = Pengumuman::with('penulis')->where('jenis', 'pengumuman');

            return DataTables::of($pengumumen)
                ->addIndexColumn()
                ->editColumn('judul', function ($pengumuman) {
                    return '<strong>' . e($pengumuman->judul) . '</strong>';
                })
                ->editColumn('is_published', function ($pengumuman) {
                    $status = $pengumuman->is_published ? 'Published' : 'Draft';
                    $class = $pengumuman->is_published ? 'bg-label-success' : 'bg-label-warning';
                    return '<span class="badge ' . $class . '">' . $status . '</span>';
                })
                ->editColumn('created_at', function ($pengumuman) {
                    return $pengumuman->created_at ? $pengumuman->created_at->format('d M Y') : '-';
                })
                ->addColumn('action', function ($pengumuman) {
                    $encryptedId = encryptId($pengumuman->id);
                    return '
                        <div class="d-flex">
                            <a href="' . route('pengumuman.show', $encryptedId) . '" class="text-info dropdown-item me-1" title="View">
                                <i class="bx bx-show"></i>
                            </a>
                            <a href="' . route('pengumuman.edit', $encryptedId) . '" class="text-primary dropdown-item me-1" title="Edit">
                                <i class="bx bx-edit"></i>
                            </a>
                            <form action="' . route('pengumuman.destroy', $encryptedId) . '" method="POST" class="d-inline">
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                <button type="submit" class="text-danger dropdown-item" title="Delete" onclick="return confirm(\'Are you sure?\')">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </form>
                        </div>';
                })
                ->rawColumns(['judul', 'is_published', 'action'])
                ->make(true);
        }

        return view('pages.admin.pengumuman.index', ['type' => 'pengumuman']);
    }

    /**
     * Display a listing of berita.
     */
    public function beritaIndex(Request $request)
    {
        if ($request->ajax()) {
            $berita = Pengumuman::with('penulis')->where('jenis', 'berita');

            return DataTables::of($berita)
                ->addIndexColumn()
                ->editColumn('judul', function ($berita) {
                    return '<strong>' . e($berita->judul) . '</strong>';
                })
                ->editColumn('is_published', function ($berita) {
                    $status = $berita->is_published ? 'Published' : 'Draft';
                    $class = $berita->is_published ? 'bg-label-success' : 'bg-label-warning';
                    return '<span class="badge ' . $class . '">' . $status . '</span>';
                })
                ->editColumn('created_at', function ($berita) {
                    return $berita->created_at ? $berita->created_at->format('d M Y') : '-';
                })
                ->addColumn('action', function ($berita) {
                    $encryptedId = encryptId($berita->id);
                    return '
                        <div class="d-flex">
                            <a href="' . route('berita.show', $encryptedId) . '" class="text-info dropdown-item me-1" title="View">
                                <i class="bx bx-show"></i>
                            </a>
                            <a href="' . route('berita.edit', $encryptedId) . '" class="text-primary dropdown-item me-1" title="Edit">
                                <i class="bx bx-edit"></i>
                            </a>
                            <form action="' . route('berita.destroy', $encryptedId) . '" method="POST" class="d-inline">
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                <button type="submit" class="text-danger dropdown-item" title="Delete" onclick="return confirm(\'Are you sure?\')">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </form>
                        </div>';
                })
                ->rawColumns(['judul', 'is_published', 'action'])
                ->make(true);
        }

        return view('pages.admin.pengumuman.index', ['type' => 'berita']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($type = 'pengumuman')
    {
        $penulisOptions = User::all();
        return view('pages.admin.pengumuman.create', compact('type', 'penulisOptions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PengumumanRequest $request)
    {
        $validated = $request->validated();

        $isPublished = $validated['is_published'] ?? false;

        $pengumuman = Pengumuman::create([
            'judul' => $validated['judul'],
            'isi' => $validated['isi'],
            'jenis' => $validated['jenis'],
            'penulis_id' => Auth::id(),
            'is_published' => $isPublished,
            'published_at' => $isPublished ? now() : null,
        ]);

        // Handle media uploads if present
        if ($request->hasFile('cover_image')) {
            $pengumuman->addMedia($request->file('cover_image'), 'info_cover');
        }

        if ($request->hasFile('attachments')) {
            $pengumuman->addMultipleMedia($request->file('attachments'), 'info_attachment');
        }

        $redirectRoute = $pengumuman->jenis === 'pengumuman' ? 'pengumuman.index' : 'berita.index';

        return redirect()->route($redirectRoute)
            ->with('success', ucfirst($pengumuman->jenis) . ' created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $realId = decryptId($id);
        if (!$realId) {
            abort(404);
        }

        $pengumuman = Pengumuman::findOrFail($realId);
        return view('pages.admin.pengumuman.show', compact('pengumuman'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $realId = decryptId($id);
        if (!$realId) {
            abort(404);
        }

        $pengumuman = Pengumuman::findOrFail($realId);
        $penulisOptions = User::all();
        $type = $pengumuman->jenis;
        return view('pages.admin.pengumuman.edit', compact('pengumuman', 'type', 'penulisOptions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PengumumanRequest $request, $id)
    {
        $realId = decryptId($id);
        if (!$realId) {
            abort(404);
        }

        $pengumuman = Pengumuman::findOrFail($realId);
        $validated = $request->validated();

        $isPublished = $validated['is_published'] ?? false;

        $pengumuman->update([
            'judul' => $validated['judul'],
            'isi' => $validated['isi'],
            'is_published' => $isPublished,
            'published_at' => $isPublished ? now() : $pengumuman->published_at, // Keep original published_at if not changing status
        ]);

        // Handle media uploads if present
        if ($request->hasFile('cover_image')) {
            // Remove existing cover image if any
            $pengumuman->clearMediaCollection('info_cover');
            // Add new cover image
            $pengumuman->addMedia($request->file('cover_image'), 'info_cover');
        }

        if ($request->hasFile('attachments')) {
            // Add new attachments
            $pengumuman->addMultipleMedia($request->file('attachments'), 'info_attachment');
        }

        $redirectRoute = $pengumuman->jenis === 'pengumuman' ? 'pengumuman.index' : 'berita.index';

        return redirect()->route($redirectRoute)
            ->with('success', ucfirst($pengumuman->jenis) . ' updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $realId = decryptId($id);
        if (!$realId) {
            abort(404);
        }

        $pengumuman = Pengumuman::findOrFail($realId);
        $jenis = $pengumuman->jenis;
        $pengumuman->delete();

        $redirectRoute = $jenis === 'pengumuman' ? 'pengumuman.index' : 'berita.index';

        return redirect()->route($redirectRoute)
            ->with('success', ucfirst($jenis) . ' deleted successfully.');
    }

    /**
     * Process datatables ajax request.
     */
    public function data(Request $request)
    {
        // Determine the type based on the route name
        $routeName = $request->route()->getName();
        $type = $routeName === 'berita.data' ? 'berita' : 'pengumuman';

        $data = Pengumuman::with('penulis')->where('jenis', $type);

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('judul', function ($item) {
                return '<strong>' . e($item->judul) . '</strong>';
            })
            ->editColumn('is_published', function ($item) {
                $status = $item->is_published ? 'Published' : 'Draft';
                $class = $item->is_published ? 'bg-label-success' : 'bg-label-warning';
                return '<span class="badge ' . $class . '">' . $status . '</span>';
            })
            ->editColumn('created_at', function ($item) {
                return $item->created_at ? $item->created_at->format('d M Y') : '-';
            })
            ->addColumn('action', function ($item) {
                $encryptedId = encryptId($item->id);
                // Use the appropriate route based on jenis
                $routePrefix = $item->jenis === 'berita' ? 'berita' : 'pengumuman';

                return '
                    <div class="d-flex">
                        <a href="' . route($routePrefix . '.show', $encryptedId) . '" class="text-info dropdown-item me-1" title="View">
                            <i class="bx bx-show"></i>
                        </a>
                        <a href="' . route($routePrefix . '.edit', $encryptedId) . '" class="text-primary dropdown-item me-1" title="Edit">
                            <i class="bx bx-edit"></i>
                        </a>
                        <form action="' . route($routePrefix . '.destroy', $encryptedId) . '" method="POST" class="d-inline">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="submit" class="text-danger dropdown-item" title="Delete" onclick="return confirm(\'Are you sure?\')">
                                <i class="bx bx-trash"></i>
                            </button>
                        </form>
                    </div>';
            })
            ->rawColumns(['judul', 'is_published', 'action'])
            ->make(true);
    }
}
