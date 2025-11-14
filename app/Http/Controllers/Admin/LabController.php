<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\LabRequest;
use App\Models\Lab;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class LabController extends Controller
{
    public function __construct()
    {
        // $this->middleware(['permission:view labs'], ['only' => ['index', 'show', 'data']]);
        // $this->middleware(['permission:edit labs'], ['only' => [ 'edit', 'update']]);
        // $this->middleware(['permission:create labs'], ['only' => ['create', 'store']]);
        // $this->middleware(['permission:delete labs'], ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('pages.admin.labs.index');
    }

    /**
     * Process datatables ajax request.
     */
    public function data(Request $request)
    {
        $labs = Lab::select('*');

        return DataTables::of($labs)
            ->addIndexColumn()
            ->addColumn('action', function ($lab) {
                $encryptedId = encryptId($lab->lab_id);
                return '
                    <div class="d-flex align-items-center">
                        <a class="btn btn-sm btn-icon btn-outline-primary me-1" href="' . route('labs.edit', $encryptedId) . '" title="Edit">
                            <i class="bx bx-edit"></i>
                        </a>
                        <div class="dropdown">
                            <button type="button" class="btn btn-sm btn-icon btn-outline-secondary" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="' . route('labs.show', $encryptedId) . '">
                                    <i class="bx bx-show me-1"></i> View
                                </a>
                                <a href="javascript:void(0)" class="dropdown-item text-danger" onclick="confirmDelete(\'' . route('labs.destroy', $encryptedId) . '\')">
                                    <i class="bx bx-trash me-1"></i> Delete
                                </a>
                            </div>
                        </div>
                    </div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.admin.labs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LabRequest $request)
    {
        \DB::beginTransaction();
        try {
            $lab = Lab::create($request->validated());

            // Handle lab media uploads if any
            if ($request->hasFile('media_files')) {
                $mediaFiles = $request->file('media_files');
                $mediaTitles = $request->input('media_titles', []);
                $mediaDescriptions = $request->input('media_descriptions', []);

                foreach ($mediaFiles as $index => $file) {
                    if ($file->isValid()) {
                        // Get media title and description if provided, otherwise use default values
                        $title = isset($mediaTitles[$index]) ? $mediaTitles[$index] : 'Gambar ' . ($index + 1);
                        $keterangan = isset($mediaDescriptions[$index]) ? $mediaDescriptions[$index] : 'Deskripsi gambar';

                        // Add media to the lab using the HasMedia trait
                        $media = $lab->addMedia($file, 'lab_images');

                        // Create a LabMedia record to store title and description
                        $lab->labMedia()->create([
                            'media_id' => $media->id,
                            'judul' => $title,
                            'keterangan' => $keterangan,
                        ]);
                    }
                }
            }

            \DB::commit();

            return redirect()->route('labs.index')
                ->with('success', 'Lab berhasil dibuat.');
        } catch (\Exception $e) {
            \DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal membuat lab: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $realId = decryptId($id); // Fungsi helper sekarang akan otomatis abort(404) jika gagal

        $lab = Lab::findOrFail($realId);
        return view('pages.admin.labs.show', compact('lab'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $realId = decryptId($id); // Fungsi helper sekarang akan otomatis abort(404) jika gagal

        $lab = Lab::findOrFail($realId);
        return view('pages.admin.labs.edit', compact('lab'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LabRequest $request, $id)
    {
        $realId = decryptId($id); // Fungsi helper sekarang akan otomatis abort(404) jika gagal

        $lab = Lab::findOrFail($realId);

        \DB::beginTransaction();
        try {
            $lab->update($request->validated());

            // Handle lab media uploads if any
            if ($request->hasFile('media_files')) {
                $mediaFiles = $request->file('media_files');
                $mediaTitles = $request->input('media_titles', []);
                $mediaDescriptions = $request->input('media_descriptions', []);

                foreach ($mediaFiles as $index => $file) {
                    if ($file->isValid()) {
                        // Get media title and description if provided, otherwise use default values
                        $title = isset($mediaTitles[$index]) ? $mediaTitles[$index] : 'Gambar ' . ($index + 1);
                        $keterangan = isset($mediaDescriptions[$index]) ? $mediaDescriptions[$index] : 'Deskripsi gambar';

                        // Add media to the lab using the HasMedia trait
                        $media = $lab->addMedia($file, 'lab_images');

                        // Create a LabMedia record to store title and description
                        $lab->labMedia()->create([
                            'media_id' => $media->id,
                            'judul' => $title,
                            'keterangan' => $keterangan,
                        ]);
                    }
                }
            }

            \DB::commit();

            return redirect()->route('labs.index')
                ->with('success', 'Lab berhasil diperbarui.');
        } catch (\Exception $e) {
            \DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal memperbarui lab: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $realId = decryptId($id); // Fungsi helper sekarang akan otomatis abort(404) jika gagal

        $lab = Lab::findOrFail($realId);
        $lab->delete();

        return redirect()->route('labs.index')
            ->with('success', 'Lab deleted successfully.');
    }
}
