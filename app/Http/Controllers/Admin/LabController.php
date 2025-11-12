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
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $labs = Lab::when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->paginate(20);

        return view('pages.admin.labs.index', compact('labs'));
    }
    
    /**
     * Process datatables ajax request.
     */
    public function dataTable(Request $request)
    {
        $labs = Lab::select('*');
        
        return DataTables::of($labs)
            ->addIndexColumn()
            ->addColumn('action', function ($lab) {
                return '
                    <div class="d-flex">
                        <a href="' . route('labs.show', $lab) . '" class="text-info dropdown-item me-1" title="View">
                            <i class="bx bx-show"></i>
                        </a>
                        <a href="' . route('labs.edit', $lab) . '" class="text-primary dropdown-item me-1" title="Edit">
                            <i class="bx bx-edit"></i>
                        </a>
                        <form action="' . route('labs.destroy', $lab) . '" method="POST" class="d-inline">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="submit" class="text-danger dropdown-item" title="Delete" onclick="return confirm(\'Are you sure?\')">
                                <i class="bx bx-trash"></i>
                            </button>
                        </form>
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
        Lab::create($request->validated());

        return redirect()->route('labs.index')
            ->with('success', 'Lab created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Lab $lab)
    {
        return view('pages.admin.labs.show', compact('lab'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lab $lab)
    {
        return view('pages.admin.labs.edit', compact('lab'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LabRequest $request, Lab $lab)
    {
        $lab->update($request->validated());

        return redirect()->route('labs.index')
            ->with('success', 'Lab updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lab $lab)
    {
        $lab->delete();

        return redirect()->route('labs.index')
            ->with('success', 'Lab deleted successfully.');
    }
}
