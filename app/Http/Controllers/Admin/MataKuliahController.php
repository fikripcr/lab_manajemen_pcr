<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class MataKuliahController extends Controller
{
    public function __construct()
    {
        // $this->middleware(['permission:manage-mata-kuliah']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('pages.admin.mata-kuliah.index');
    }

    /**
     * Process datatables ajax request.
     */
    public function data(Request $request)
    {
        $mataKuliahs = MataKuliah::select('*');

        return DataTables::of($mataKuliahs)
            ->addIndexColumn()
            ->addColumn('action', function ($mk) {
                return '
                    <div class="d-flex">
                        <a href="' . route('mata-kuliah.show', $mk->id) . '" class="btn btn-info btn-sm me-1" title="View">
                            <i class="bx bx-show"></i>
                        </a>
                        <a href="' . route('mata-kuliah.edit', $mk->id) . '" class="btn btn-primary btn-sm me-1" title="Edit">
                            <i class="bx bx-edit"></i>
                        </a>
                        <form action="' . route('mata-kuliah.destroy', $mk->id) . '" method="POST" class="d-inline">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger btn-sm" title="Delete" onclick="return confirmDelete(this.form.action, \'Hapus Mata Kuliah?\', \'Apakah Anda yakin ingin menghapus mata kuliah ini?\')">
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
        return view('pages.admin.mata-kuliah.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_mk' => 'required|string|max:20|unique:mata_kuliahs,kode_mk',
            'nama_mk' => 'required|string|max:255',
            'sks' => 'required|integer|min:1|max:6',
        ]);

        MataKuliah::create($request->all());

        return redirect()->route('mata-kuliah.index')
            ->with('success', 'Mata Kuliah created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $mataKuliah = MataKuliah::findOrFail($id);
        return view('pages.admin.mata-kuliah.show', compact('mataKuliah'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $mataKuliah = MataKuliah::findOrFail($id);
        return view('pages.admin.mata-kuliah.edit', compact('mataKuliah'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $mataKuliah = MataKuliah::findOrFail($id);

        $request->validate([
            'kode_mk' => 'required|string|max:20|unique:mata_kuliahs,kode_mk,' . $mataKuliah->id,
            'nama_mk' => 'required|string|max:255',
            'sks' => 'required|integer|min:1|max:6',
        ]);

        $mataKuliah->update($request->all());

        return redirect()->route('mata-kuliah.index')
            ->with('success', 'Mata Kuliah updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $mataKuliah = MataKuliah::findOrFail($id);

        // Check if mata kuliah is used in any schedule
        if ($mataKuliah->jadwals->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete mata kuliah that is associated with schedules.');
        }

        // Check if mata kuliah is used in any software request
        if ($mataKuliah->requestSoftwares->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete mata kuliah that is associated with software requests.');
        }

        $mataKuliah->delete();

        return redirect()->route('mata-kuliah.index')
            ->with('success', 'Mata Kuliah deleted successfully.');
    }
}
