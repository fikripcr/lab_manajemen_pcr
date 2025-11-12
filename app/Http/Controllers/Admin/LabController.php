<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\LabRequest;
use App\Models\Lab;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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
            ->paginate(10);

        return view('pages.admin.labs.index', compact('labs'));
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
