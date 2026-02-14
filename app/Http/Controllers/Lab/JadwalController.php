<?php
namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lab\JadwalRequest;
use App\Models\Lab\Lab;
use App\Models\Lab\MataKuliah;
use App\Models\Lab\Semester;
use App\Models\User;
use App\Services\Lab\JadwalService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class JadwalController extends Controller
{
    protected $JadwalService;

    public function __construct(JadwalService $JadwalService)
    {
        $this->JadwalService = $JadwalService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('pages.lab.jadwal.index');
    }

    /**
     * Process datatables ajax request.
     */
    public function paginate(Request $request)
    {
        // Use Service Query
        $jadwals = $this->JadwalService->getFilteredQuery($request->all());

        return DataTables::of($jadwals)
            ->addIndexColumn()
        // DataTables Filter logic for Global Search
            ->filter(function ($query) use ($request) {
                if ($request->has('search') && $request->search['value'] != '') {
                    $searchValue = $request->search['value'];
                    $query->where(function ($q) use ($searchValue) {
                        $q->where('jadwal_kuliah.hari', 'like', '%' . $searchValue . '%')
                            ->orWhere('mata_kuliahs.kode_mk', 'like', '%' . $searchValue . '%')
                            ->orWhere('mata_kuliahs.nama_mk', 'like', '%' . $searchValue . '%')
                            ->orWhere('users.name', 'like', '%' . $searchValue . '%') // Dosen
                            ->orWhere('labs.name', 'like', '%' . $searchValue . '%')
                            ->orWhere('semesters.tahun_ajaran', 'like', '%' . $searchValue . '%');
                    });
                }
            })
            ->addColumn('tanggal', function ($jadwal) {
                return $jadwal->hari;
            })
            ->addColumn('waktu_mulai', function ($jadwal) {
                return date('H:i', strtotime($jadwal->jam_mulai));
            })
            ->addColumn('waktu_selesai', function ($jadwal) {
                return date('H:i', strtotime($jadwal->jam_selesai));
            })
            ->addColumn('mata_kuliah_nama', function ($jadwal) {
                if ($jadwal->mata_kuliah_id && $jadwal->mataKuliah) {
                    return '<span class="fw-medium">' . $jadwal->mataKuliah->kode_mk . '</span> - ' . $jadwal->mataKuliah->nama_mk;
                }
                return '-';
            })
            ->addColumn('dosen_nama', function ($jadwal) { // Use relationship if join aliases not available or if eager loaded
                return $jadwal->dosen ? $jadwal->dosen->name : ($jadwal->dosen_name ?? '-');
            })
            ->addColumn('ruang_nama', function ($jadwal) {
                return $jadwal->lab ? $jadwal->lab->name : ($jadwal->lab_name ?? '-');
            })
            ->addColumn('semester_nama_display', function ($jadwal) {
                if ($jadwal->semester_id) {
                    // Use model accessor or relationship data
                    $smtr = $jadwal->semester;
                    if ($smtr) {
                        return $smtr->tahun_ajaran . ' - ' . ($smtr->semester == 1 ? 'Ganjil' : 'Genap');
                    }
                }
                return '-';
            })
            ->addColumn('action', function ($jadwal) {
                // Ensure ID is encrypted
                $encryptedId = encryptId($jadwal->jadwal_kuliah_id);
                return view('components.tabler.datatables-actions', [
                    'editUrl'     => route('lab.jadwal.edit', $encryptedId),
                    'viewUrl'     => route('lab.jadwal.show', $encryptedId),
                    'deleteUrl'   => route('lab.jadwal.destroy', $encryptedId),
                    'customLinks' => [
                        [
                            'url'   => route('lab.jadwal.assignments.index', $encryptedId),
                            'icon'  => 'bx bx-desktop',
                            'title' => 'Atur PC',
                            'class' => 'btn-outline-info',
                        ],
                    ],
                ])->render();
            })
            ->rawColumns(['mata_kuliah_nama', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $semesters   = Semester::all();
        $mataKuliahs = MataKuliah::all();
        $dosens      = User::whereHas('roles', function ($query) {
            $query->where('name', 'dosen');
        })->get();
        $labs = Lab::all();

        return view('pages.lab.jadwal.create', compact('semesters', 'mataKuliahs', 'dosens', 'labs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(JadwalRequest $request)
    {
        try {
            $this->JadwalService->createJadwal($request->validated());

            return jsonSuccess('Jadwal berhasil dibuat.', route('lab.jadwal.index'));
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $realId = decryptId($id);

        $jadwal = $this->JadwalService->getJadwalById($realId);
        if (! $jadwal) {
            abort(404);
        }

        return view('pages.lab.jadwal.show', compact('jadwal'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $realId = decryptId($id);

        $jadwal = $this->JadwalService->getJadwalById($realId); // Use Service
        if (! $jadwal) {
            abort(404);
        }

        $semesters   = Semester::all();
        $mataKuliahs = MataKuliah::all();
        $dosens      = User::whereHas('roles', function ($query) {
            $query->where('name', 'dosen');
        })->get();
        $labs = Lab::all();

        return view('pages.lab.jadwal.edit', compact('jadwal', 'semesters', 'mataKuliahs', 'dosens', 'labs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(JadwalRequest $request, $id)
    {
        $realId = decryptId($id);

        try {
            $this->JadwalService->updateJadwal($realId, $request->validated());

            return jsonSuccess('Jadwal berhasil diperbarui.', route('lab.jadwal.index'));
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $realId = decryptId($id);
            $this->JadwalService->deleteJadwal($realId);

            return jsonSuccess('Jadwal berhasil dihapus.', route('lab.jadwal.index'));

        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    /**
     * Show import form
     */
    public function showImport()
    {
        return view('pages.lab.jadwal.import');
    }

    /**
     * Import jadwal from Excel file
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);
        try {
            $this->JadwalService->importJadwal($request->file('file'));

            return jsonSuccess('Jadwal berhasil diimpor.', route('lab.jadwal.index'));
        } catch (\Exception $e) {
            return jsonError('Gagal mengimpor jadwal: ' . $e->getMessage(), 500);
        }
    }
}
