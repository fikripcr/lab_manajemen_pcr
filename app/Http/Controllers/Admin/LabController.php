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
                    <div class="d-flex">
                        <a href="' . route('labs.show', $encryptedId) . '" class="btn btn-info btn-sm me-1" title="View">
                            <i class="bx bx-show"></i>
                        </a>
                        <a href="' . route('labs.edit', $encryptedId) . '" class="btn btn-primary btn-sm me-1" title="Edit">
                            <i class="bx bx-edit"></i>
                        </a>
                        <form action="' . route('labs.destroy', $encryptedId) . '" method="POST" class="d-inline">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger btn-sm" title="Delete" onclick="return confirm(\'Are you sure?\')">
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
    public function show(string $id)
    {
        $realId = decryptId($id);
        if (!$realId) {
            abort(404);
        }

        $lab = Lab::findOrFail($realId);
        return view('pages.admin.labs.show', compact('lab'));
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

        $lab = Lab::findOrFail($realId);
        return view('pages.admin.labs.edit', compact('lab'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LabRequest $request, $id)
    {
        $realId = decryptId($id);
        if (!$realId) {
            abort(404);
        }

        $lab = Lab::findOrFail($realId);
        $lab->update($request->validated());

        return redirect()->route('labs.index')
            ->with('success', 'Lab updated successfully.');
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

        $lab = Lab::findOrFail($realId);
        $lab->delete();

        return redirect()->route('labs.index')
            ->with('success', 'Lab deleted successfully.');
    }
}
