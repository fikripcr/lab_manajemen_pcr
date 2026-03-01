<?php
namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lab\LabRequest;
use App\Models\Lab\Lab; // Still needed for type hinting or specific direct queries if any (e.g., DataTables if not via Service)
use App\Services\Lab\LabService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class LabController extends Controller
{
    public function __construct(protected LabService $labService)
    {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.lab.labs.index');
    }

    /**
     * Process datatables ajax request.
     */
    public function data(Request $request)
    {
        // Use Service Query
        $labs = $this->labService->getFilteredQuery($request->all());

        return DataTables::of($labs)
            ->addIndexColumn()
            ->addColumn('action', function ($lab) {
                return view('components.tabler.datatables-actions', [
                    'editUrl'       => route('lab.labs.edit', $lab->encrypted_lab_id),
                    'editModal'     => true,
                    'editModalSize' => 'modal-xl',
                    'viewUrl'       => route('lab.labs.show', $lab->encrypted_lab_id),
                    'deleteUrl'     => route('lab.labs.destroy', $lab->encrypted_lab_id),
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
        $lab = new Lab();
        return view('pages.lab.labs.create-edit-ajax', compact('lab'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LabRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('lab_images')) {
            $data['lab_images'] = $request->file('lab_images');
        }
        if ($request->hasFile('lab_attachments')) {
            $data['lab_attachments'] = $request->file('lab_attachments');
        }

        $this->labService->createLab($data);
        return jsonSuccess('Lab berhasil ditambahkan.', route('lab.labs.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Lab $lab)
    {
        $lab->load(['labTeams.user', 'labInventaris.inventaris', 'media']);
        return view('pages.lab.labs.show', compact('lab'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lab $lab)
    {
        return view('pages.lab.labs.create-edit-ajax', compact('lab'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LabRequest $request, Lab $lab)
    {
        $data = $request->validated();
        if ($request->hasFile('lab_images')) {
            $data['lab_images'] = $request->file('lab_images');
        }
        if ($request->hasFile('lab_attachments')) {
            $data['lab_attachments'] = $request->file('lab_attachments');
        }

        $this->labService->updateLab($lab, $data);
        return jsonSuccess('Lab berhasil diperbarui.', route('lab.labs.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lab $lab)
    {
        $this->labService->deleteLab($lab);
        return jsonSuccess('Lab berhasil dihapus.', route('lab.labs.index'));
    }
}
