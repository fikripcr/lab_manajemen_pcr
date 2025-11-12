<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\InventoryRequest;
use App\Models\Inventaris;
use App\Models\Lab;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $inventories = Inventaris::with('lab')
            ->when($search, function ($query, $search) {
                return $query->where('nama_alat', 'like', "%{$search}%")
                    ->orWhere('jenis_alat', 'like', "%{$search}%")
                    ->orWhereHas('lab', function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            })
            ->paginate(10);

        return view('pages.admin.inventories.index', compact('inventories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $labs = Lab::all();
        return view('pages.admin.inventories.create', compact('labs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(InventoryRequest $request)
    {
        Inventaris::create($request->validated());

        return redirect()->route('inventories.index')
            ->with('success', 'Inventaris created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Inventaris $inventory)
    {
        return view('pages.admin.inventories.show', compact('inventory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inventaris $inventory)
    {
        $labs = Lab::all();
        return view('pages.admin.inventories.edit', compact('inventory', 'labs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(InventoryRequest $request, Inventaris $inventory)
    {
        $inventory->update($request->validated());

        return redirect()->route('inventories.index')
            ->with('success', 'Inventaris updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inventaris $inventory)
    {
        $inventory->delete();

        return redirect()->route('inventories.index')
            ->with('success', 'Inventaris deleted successfully.');
    }
}