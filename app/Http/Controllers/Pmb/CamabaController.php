<?php
namespace App\Http\Controllers\Pmb;

use App\Http\Controllers\Controller;
use App\Models\Pmb\Camaba;
use App\Models\Pmb\Pendaftaran;
use App\Services\Pmb\CamabaService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CamabaController extends Controller
{
    public function __construct(
        protected CamabaService $camabaService
    ) {}

    /**
     * Display list of Camaba (for admin management)
     */
    public function index()
    {
        return view('pages.pmb.camaba.index');
    }

    /**
     * Paginate camaba data
     */
    public function paginate(Request $request)
    {
        $query = Camaba::with(['user']);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('nama', function ($row) {
                return $row->user->name ?? '-';
            })
            ->addColumn('email', function ($row) {
                return $row->user->email ?? '-';
            })
            ->addColumn('action', function ($row) {
                return view('components.tabler.datatables-actions', [
                    'editUrl'   => route('pmb.camaba.edit', $row->encrypted_camaba_id),
                    'editModal' => true,
                    'viewUrl'   => route('pmb.camaba.show', $row->encrypted_camaba_id),
                    'deleteUrl' => route('pmb.camaba.destroy', $row->encrypted_camaba_id),
                ])->render();
            })
            ->editColumn('nik', function ($row) {
                return $row->nik ?? '-';
            })
            ->editColumn('no_hp', function ($row) {
                return $row->no_hp ?? '-';
            })
            ->rawColumns(['action', 'nama', 'email'])
            ->make(true);
    }

    /**
     * Show camaba details
     */
    public function show(Camaba $camaba)
    {
        $camaba->load(['user', 'pendaftaran' => function ($q) {
            $q->with(['jalur', 'periode', 'pilihanProdi.orgUnit', 'orgUnitDiterima', 'dokumenUpload.jenisDokumen', 'pembayaran']);
        }]);
        
        return view('pages.pmb.camaba.show', compact('camaba'));
    }

    /**
     * Show edit form
     */
    public function edit(Camaba $camaba)
    {
        return view('pages.pmb.camaba.create-edit-ajax', compact('camaba'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('pages.pmb.camaba.create-edit-ajax');
    }

    /**
     * Store camaba
     */
    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|unique:pmb_camaba,nik',
            'no_hp' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat_lengkap' => 'required',
            'asal_sekolah' => 'required',
        ]);

        try {
            Camaba::create($request->all());
            return jsonSuccess('Data Camaba berhasil ditambahkan.', route('pmb.camaba.index'));
        } catch (\Exception $e) {
            logError($e);
            return jsonError('Gagal menambahkan data camaba: ' . $e->getMessage());
        }
    }

    /**
     * Update camaba
     */
    public function update(Request $request, Camaba $camaba)
    {
        $request->validate([
            'nik' => 'required|unique:pmb_camaba,nik,' . $camaba->camaba_id . ',camaba_id',
            'no_hp' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat_lengkap' => 'required',
            'asal_sekolah' => 'required',
        ]);

        try {
            $camaba->update($request->all());
            return jsonSuccess('Data Camaba berhasil diperbarui.', route('pmb.camaba.index'));
        } catch (\Exception $e) {
            logError($e);
            return jsonError('Gagal memperbarui data camaba: ' . $e->getMessage());
        }
    }

    /**
     * Delete camaba
     */
    public function destroy(Camaba $camaba)
    {
        try {
            $camaba->delete();
            return jsonSuccess('Data Camaba berhasil dihapus.');
        } catch (\Exception $e) {
            logError($e);
            return jsonError('Gagal menghapus data camaba: ' . $e->getMessage());
        }
    }
}
