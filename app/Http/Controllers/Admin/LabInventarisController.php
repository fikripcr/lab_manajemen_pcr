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
    public function index($labId)
    {
        $lab = Lab::findOrFail(decryptId($labId));
        return view('pages.admin.labs.inventaris.index', compact('lab'));
    }

    public function data(Request $request, $labId)
    {
        $lab = Lab::findOrFail(decryptId($labId));
        $labInventaris = $lab->labInventaris()
            ->with(['inventaris', 'lab']);

        return \Yajra\DataTables\DataTables::of($labInventaris)
            ->addIndexColumn()
            ->editColumn('kode_inventaris', function ($item) {
                return '<code>' . $item->kode_inventaris . '</code>';
            })
            ->editColumn('no_series', function ($item) {
                return $item->no_series ?: '-';
            })
            ->editColumn('tanggal_penempatan', function ($item) {
                return formatTanggalIndo($item->tanggal_penempatan);
            })
            ->editColumn('tanggal_penghapusan', function ($item) {
                return $item->tanggal_penghapusan ? formatTanggalIndo($item->tanggal_penghapusan) : '-';
            })
            ->editColumn('status', function ($item) {
                $statusClass = '';
                switch ($item->status) {
                    case 'active':
                        $statusClass = 'bg-label-success';
                        $statusText = 'Active';
                        break;
                    case 'moved':
                        $statusClass = 'bg-label-warning';
                        $statusText = 'Moved';
                        break;
                    case 'inactive':
                        $statusClass = 'bg-label-secondary';
                        $statusText = 'Inactive';
                        break;
                    default:
                        $statusClass = 'bg-label-secondary';
                        $statusText = ucfirst($item->status);
                }

                return '<span class="badge ' . $statusClass . '">' . $statusText . '</span>';
            })
            ->addColumn('nama_alat', function ($item) {
                return $item->inventaris->nama_alat;
            })
            ->addColumn('jenis_alat', function ($item) {
                return $item->inventaris->jenis_alat;
            })
            ->addColumn('action', function ($item) {
                $encryptedId = encryptId($item->id);
                $encryptedLabId = encryptId($item->lab_id);
                
                return '
                    <div class="d-flex align-items-center">
                        <a href="' . route('labs.inventaris.edit', [$encryptedLabId, $encryptedId]) . '" class="btn btn-sm btn-icon btn-outline-primary me-1" title="Edit">
                            <i class="bx bx-edit"></i>
                        </a>
                        <a href="javascript:void(0)" class="btn btn-sm btn-icon btn-outline-danger" title="Delete" onclick="confirmDelete(\'' . route('labs.inventaris.destroy', [$encryptedLabId, $encryptedId]) . '\')">
                            <i class="bx bx-trash"></i>
                        </a>
                    </div>';
            })
            ->rawColumns(['kode_inventaris', 'status', 'action'])
            ->make(true);
    }

    public function create($labId)
    {
        $realId = decryptId($labId);
        $lab = Lab::findOrFail($realId);
        $inventarisList = Inventaris::whereDoesntHave('labInventaris', function($query) use ($realId) {
                $query->where('lab_id', $realId);
            })
            ->get();

        return view('pages.admin.labs.inventaris.create', compact('lab', 'inventarisList'));
    }

    public function store(Request $request, $labId)
    {
        $request->validate([
            'inventaris_id' => 'required|exists:inventaris,inventaris_id',
            'no_series' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string|max:1000',
            'tanggal_penempatan' => 'nullable|date',
            'status' => 'nullable|in:active,moved,inactive',
        ]);

        $lab = Lab::findOrFail(decryptId($labId));
        $inventaris = Inventaris::findOrFail($request->inventaris_id);

        DB::beginTransaction();
        try {
            $kodeInventaris = LabInventaris::generateKodeInventaris($lab->lab_id, $inventaris->inventaris_id);

            $labInventaris = LabInventaris::create([
                'inventaris_id' => $inventaris->inventaris_id,
                'lab_id' => $lab->lab_id,
                'kode_inventaris' => $kodeInventaris,
                'no_series' => $request->no_series,
                'tanggal_penempatan' => $request->tanggal_penempatan ?? now(),
                'keterangan' => $request->keterangan,
                'status' => $request->status ?? 'active',
            ]);

            DB::commit();

            return redirect()->route('labs.inventaris.index', $lab->encrypted_lab_id)
                ->with('success', 'Inventaris berhasil ditambahkan ke lab.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal menambahkan inventaris ke lab: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit($labId, $id)
    {
        $lab = Lab::findOrFail(decryptId($labId));
        $labInventaris = LabInventaris::findOrFail(decryptId($id));
        $inventarisList = Inventaris::all();

        return view('pages.admin.labs.inventaris.edit', compact('lab', 'labInventaris', 'inventarisList'));
    }

    public function update(Request $request, $labId, $id)
    {
        $request->validate([
            'inventaris_id' => 'required|exists:inventaris,inventaris_id',
            'no_series' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string|max:1000',
            'tanggal_penempatan' => 'nullable|date',
            'tanggal_penghapusan' => 'nullable|date',
            'status' => 'nullable|in:active,moved,inactive',
        ]);

        $labInventaris = LabInventaris::findOrFail(decryptId($id));

        DB::beginTransaction();
        try {
            $labInventaris->update([
                'inventaris_id' => $request->inventaris_id,
                'no_series' => $request->no_series,
                'tanggal_penempatan' => $request->tanggal_penempatan,
                'tanggal_penghapusan' => $request->tanggal_penghapusan,
                'keterangan' => $request->keterangan,
                'status' => $request->status,
            ]);

            DB::commit();

            return redirect()->route('labs.inventaris.index', $labInventaris->encrypted_lab_id)
                ->with('success', 'Data inventaris lab berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal memperbarui data inventaris lab: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function getInventaris(Request $request, $labId)
    {
        $search = $request->get('search');
        $realLabId = decryptId($labId);

        // Exclude inventaris already assigned to this lab
        $inventaris = Inventaris::select('inventaris_id', 'nama_alat', 'jenis_alat')
            ->whereDoesntHave('labInventaris', function($query) use ($realLabId) {
                $query->where('lab_id', $realLabId);
            })
            ->when($search, function($query, $search) {
                return $query->where('nama_alat', 'LIKE', "%{$search}%")
                             ->orWhere('jenis_alat', 'LIKE', "%{$search}%");
            })
            ->limit(5)
            ->get()
            ->map(function($item) {
                return [
                    'id' => encryptId($item->inventaris_id),
                    'text' => $item->nama_alat . ' (' . $item->jenis_alat . ')'
                ];
            });

        return response()->json([
            'results' => $inventaris
        ]);
    }

    public function destroy($labId, $id)
    {
        $labInventaris = LabInventaris::findOrFail(decryptId($id));

        try {
            $labInventaris->delete();
            return response()->json([
                'success' => true,
                'message' => 'Inventaris berhasil dihapus dari lab.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus inventaris: ' . $e->getMessage()
            ], 500);
        }
    }
}