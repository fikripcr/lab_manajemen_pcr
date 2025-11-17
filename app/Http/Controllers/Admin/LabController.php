<?php

namespace App\Http\Controllers\Admin;

use App\Models\Lab;
use App\Models\LabMedia;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\LabRequest;

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
    public function paginate(Request $request)
    {
        $labs = Lab::select('*')->whereNull('deleted_at');

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

            // Handle image uploads using Spatie Media Library
            if ($request->hasFile('lab_images')) {
                foreach ($request->file('lab_images') as $image) {
                    if ($image->isValid()) {
                        $lab->addMedia($image)
                           ->withCustomProperties(['uploaded_by' => auth()->id()])
                           ->toMediaCollection('lab_images');
                    }
                }
            }

            // Handle attachment uploads using Spatie Media Library
            if ($request->hasFile('lab_attachments')) {
                foreach ($request->file('lab_attachments') as $attachment) {
                    if ($attachment->isValid()) {
                        $lab->addMedia($attachment)
                           ->withCustomProperties(['uploaded_by' => auth()->id()])
                           ->toMediaCollection('lab_attachments');
                    }
                }
            }

            \DB::commit();

            return redirect()->route('labs.index')->with('success', 'Lab berhasil ditambahkan.');
        } catch (\Exception $e) {
            \DB::rollback();
            \Log::error('Error creating lab: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal membuat lab: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $realId = decryptId($id);

        $lab = Lab::findOrFail($realId);
        return view('pages.admin.labs.show', compact('lab'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $realId = decryptId($id);

        $lab = Lab::findOrFail($realId);
        return view('pages.admin.labs.edit', compact('lab'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LabRequest $request, $id)
    {
        $realId = decryptId($id);

        $lab = Lab::findOrFail($realId);

        \DB::beginTransaction();
        try {
            $lab->update($request->validated());

            // Handle image uploads using Spatie Media Library
            if ($request->hasFile('lab_images')) {
                foreach ($request->file('lab_images') as $image) {
                    if ($image->isValid()) {
                        $lab->addMedia($image)
                           ->withCustomProperties(['uploaded_by' => auth()->id()])
                           ->toMediaCollection('lab_images');
                    }
                }
            }

            // Handle attachment uploads using Spatie Media Library
            if ($request->hasFile('lab_attachments')) {
                foreach ($request->file('lab_attachments') as $attachment) {
                    if ($attachment->isValid()) {
                        $lab->addMedia($attachment)
                           ->withCustomProperties(['uploaded_by' => auth()->id()])
                           ->toMediaCollection('lab_attachments');
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
        $realId = decryptId($id);

        $lab = Lab::findOrFail($realId);
        $lab->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Lab berhasil dihapus.'
            ]);
        }

        return redirect()->route('labs.index')
            ->with('success', 'Lab deleted successfully.');
    }

}
