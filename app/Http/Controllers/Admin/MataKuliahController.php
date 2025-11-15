<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MataKuliahRequest;
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
        $mataKuliahs = MataKuliah::select('*')->whereNull('deleted_at');

        // Apply filters if present
        if ($request->filled('sks')) {
            $mataKuliahs->where('sks', $request->sks);
        }

        return DataTables::of($mataKuliahs)
            ->addIndexColumn()
            ->filter(function ($query) use ($request) {
                // Global search functionality
                if ($request->has('search') && $request->search['value'] != '') {
                    $searchValue = $request->search['value'];
                    $query->where(function($q) use ($searchValue) {
                        $q->where('kode_mk', 'like', '%' . $searchValue . '%')
                          ->orWhere('nama_mk', 'like', '%' . $searchValue . '%');
                    });
                }
            })
            ->addColumn('action', function ($mk) {
                $encryptedId = encryptId($mk->mata_kuliah_id);
                return '
                    <div class="d-flex align-items-center">
                        <a class="text-success me-2" href="' . route('mata-kuliah.edit', $encryptedId) . '" title="Edit">
                            <i class="bx bx-edit"></i>
                        </a>
                        <div class="dropdown">
                            <button type="button" class="btn btn-sm btn-icon btn-outline-secondary" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="' . route('mata-kuliah.show', $encryptedId) . '">
                                    <i class="bx bx-show me-1"></i> View
                                </a>
                                <form action="' . route('mata-kuliah.destroy', $encryptedId) . '" method="POST" class="d-inline">
                                    ' . csrf_field() . '
                                    ' . method_field('DELETE') . '
                                    <button type="submit" class="dropdown-item text-danger" title="Delete" onclick="return confirmDelete(this.form.action, \'Hapus Mata Kuliah?\', \'Apakah Anda yakin ingin menghapus mata kuliah ini?\')">
                                        <i class="bx bx-trash me-1"></i> Delete
                                    </button>
                                </form>
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
        return view('pages.admin.mata-kuliah.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MataKuliahRequest $request)
    {
        \DB::beginTransaction();
        try {
            MataKuliah::create($request->validated());

            \DB::commit();

            return redirect()->route('mata-kuliah.index')
                ->with('success', 'Mata Kuliah berhasil dibuat.');
        } catch (\Exception $e) {
            \DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal membuat mata kuliah: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $realId = decryptId($id);
        $mataKuliah = MataKuliah::findOrFail($realId);
        return view('pages.admin.mata-kuliah.show', compact('mataKuliah'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $realId = decryptId($id);
        $mataKuliah = MataKuliah::findOrFail($realId);
        return view('pages.admin.mata-kuliah.edit', compact('mataKuliah'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MataKuliahRequest $request, $id)
    {
        $realId = decryptId($id);
        $mataKuliah = MataKuliah::findOrFail($realId);

        \DB::beginTransaction();
        try {
            $mataKuliah->update($request->validated());

            \DB::commit();

            return redirect()->route('mata-kuliah.index')
                ->with('success', 'Mata Kuliah berhasil diperbarui.');
        } catch (\Exception $e) {
            \DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal memperbarui mata kuliah: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $realId = decryptId($id);
        $mataKuliah = MataKuliah::findOrFail($realId);

        // Check if mata kuliah is used in any schedule
        if ($mataKuliah->jadwals->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete mata kuliah that is associated with schedules.');
        }

        // Check if mata kuliah is used in any software request
        if ($mataKuliah->requestSoftwares->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete mata kuliah that is associated with software requests.');
        }

        $mataKuliah->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Mata Kuliah berhasil dihapus.'
            ]);
        }

        return redirect()->route('mata-kuliah.index')
            ->with('success', 'Mata Kuliah deleted successfully.');
    }

}
