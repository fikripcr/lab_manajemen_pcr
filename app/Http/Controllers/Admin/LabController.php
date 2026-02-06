<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LabRequest;
use App\Models\Lab;
use Illuminate\Http\Request;
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
    public function paginate(Request $request)
    {
        $labs = Lab::query();

        return DataTables::of($labs)
            ->addIndexColumn()
            ->addColumn('action', function ($lab) {
                return view('components.sys.datatables-actions', [
                    'editUrl'   => route('labs.edit', $lab->encrypted_lab_id),
                    'editModal' => false,
                    'viewUrl'   => route('labs.show', $lab->encrypted_lab_id),
                    'deleteUrl' => route('labs.destroy', $lab->encrypted_lab_id),
                ])->render();
            })
            ->editColumn('description', function ($lab) {
                $description = strip_tags($lab->description);
                return strlen($description) > 50 ? substr($description, 0, 50) . '...' : $description;
            })
            ->rawColumns(['action', 'description'])
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
        // $lab->lab_id = encryptId($lab->lab_id);
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
                'message' => 'Lab berhasil dihapus.',
            ]);
        }

        return redirect()->route('labs.index')
            ->with('success', 'Lab deleted successfully.');
    }

}
