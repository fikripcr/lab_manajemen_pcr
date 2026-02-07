<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LabRequest;
use App\Models\Lab; // Still needed for type hinting or specific direct queries if any (e.g., DataTables if not via Service)
use App\Services\Admin\LabService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class LabController extends Controller
{
    protected $labService;

    public function __construct(LabService $labService)
    {
        $this->labService = $labService;
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
        // Use Service Query
        $labs = $this->labService->getFilteredQuery($request->all());

        return DataTables::of($labs)
            ->addIndexColumn()
            ->addColumn('action', function ($lab) {
                return view('components.tabler.datatables-actions', [
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
        // Validated data
        $data = $request->validated();

        // Add files to data array for Service to handle
        if ($request->hasFile('lab_images')) {
            $data['lab_images'] = $request->file('lab_images');
        }
        if ($request->hasFile('lab_attachments')) {
            $data['lab_attachments'] = $request->file('lab_attachments');
        }

        try {
            $this->labService->createLab($data);
            return jsonSuccess('Lab berhasil ditambahkan.', route('labs.index'));
        } catch (\Exception $e) {
            \Log::error('Error creation lab: ' . $e->getMessage());
            return jsonError('Gagal membuat lab: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $realId = decryptId($id);

        $lab = $this->labService->getLabById($realId); // Uses Service
        if (! $lab) {
            abort(404);
        }

        return view('pages.admin.labs.show', compact('lab'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $realId = decryptId($id);

        $lab = $this->labService->getLabById($realId);
        if (! $lab) {
            abort(404);
        }

        return view('pages.admin.labs.edit', compact('lab'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LabRequest $request, $id)
    {
        $realId = decryptId($id);
        $data   = $request->validated();

        // Add files to data array
        if ($request->hasFile('lab_images')) {
            $data['lab_images'] = $request->file('lab_images');
        }
        if ($request->hasFile('lab_attachments')) {
            $data['lab_attachments'] = $request->file('lab_attachments');
        }

        try {
            $this->labService->updateLab($realId, $data);

            return jsonSuccess('Lab berhasil diperbarui.', route('labs.index'));
        } catch (\Exception $e) {
            return jsonError('Gagal memperbarui lab: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $realId = decryptId($id);
            $this->labService->deleteLab($realId);

            return jsonSuccess('Lab berhasil dihapus.', route('labs.index'));

        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }
}
