<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lab;
use App\Models\Inventaris;
use App\Models\LabInventaris;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LabInventarisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $labId)
    {
        $lab = Lab::findOrFail($labId);
        $labInventaris = $lab->labInventaris()->with(['inventaris', 'lab'])->paginate(10);
        
        return view('pages.admin.labs.inventaris.index', compact('lab', 'labInventaris'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($labId)
    {
        $lab = Lab::findOrFail($labId);
        $inventarisOptions = Inventaris::whereDoesntHave('labInventaris', function($query) use ($labId) {
                $query->where('lab_id', $labId)
                      ->where('status', 'active');
            })
            ->get();
        
        return view('pages.admin.labs.inventaris.create', compact('lab', 'inventarisOptions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $labId)
    {
        $request->validate([
            'inventaris_id' => 'required|exists:inventaris,inventaris_id',
            'no_series' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $lab = Lab::findOrFail($labId);
        $inventaris = Inventaris::findOrFail($request->inventaris_id);

        DB::beginTransaction();
        try {
            // Check if this inventory is already assigned to this lab
            $existingAssignment = LabInventaris::where('inventaris_id', $inventaris->inventaris_id)
                                                ->where('lab_id', $lab->lab_id)
                                                ->where('status', 'active')
                                                ->first();

            if ($existingAssignment) {
                return redirect()->back()
                    ->with('error', 'Inventaris ini sudah aktif di lab ini.')
                    ->withInput();
            }

            $kodeInventaris = LabInventaris::generateKodeInventaris($lab->lab_id, $inventaris->inventaris_id);
            
            $labInventaris = LabInventaris::create([
                'inventaris_id' => $inventaris->inventaris_id,
                'lab_id' => $lab->lab_id,
                'kode_inventaris' => $kodeInventaris,
                'no_series' => $request->no_series,
                'keterangan' => $request->keterangan,
                'tanggal_penempatan' => now(),
                'status' => 'active',
            ]);

            DB::commit();

            return redirect()->route('labs.inventaris.index', $lab->lab_id)
                ->with('success', 'Inventaris berhasil ditambahkan ke lab.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal menambahkan inventaris: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($labId, $id)
    {
        $labInventaris = LabInventaris::findOrFail($id);
        
        if ($labInventaris->lab_id != $labId) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        DB::beginTransaction();
        try {
            // Update status to 'inactive' instead of deleting to keep history
            $labInventaris->update([
                'status' => 'inactive',
                'tanggal_penghapusan' => now()
            ]);

            DB::commit();

            return redirect()->route('labs.inventaris.index', $labId)
                ->with('success', 'Inventaris berhasil dihapus dari lab (riwayat tetap disimpan).');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal menghapus inventaris: ' . $e->getMessage());
        }
    }
}