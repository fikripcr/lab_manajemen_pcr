<?php
namespace App\Http\Controllers\Cbt;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cbt\StoreJadwalRequest;
use App\Models\Cbt\JadwalUjian;
use App\Models\Cbt\PaketUjian;
use App\Services\Cbt\JadwalUjianService;
use Exception;
use Illuminate\Http\Request;

class JadwalUjianController extends Controller
{
    protected $JadwalUjianService;

    public function __construct(JadwalUjianService $JadwalUjianService)
    {
        $this->JadwalUjianService = $JadwalUjianService;
    }

    public function index()
    {
        return view('pages.cbt.jadwal.index');
    }

    public function paginate(Request $request)
    {
        $query = JadwalUjian::with('paket');
        return datatables()->of($query)
            ->addIndexColumn()
            ->editColumn('waktu_mulai', fn($j) => formatTanggalIndo($j->waktu_mulai))
            ->editColumn('waktu_selesai', fn($j) => formatTanggalIndo($j->waktu_selesai))
            ->editColumn('token_ujian', function ($j) {
                $btnClass = $j->is_token_aktif ? 'badge bg-success' : 'badge bg-secondary';
                return '<span class="' . $btnClass . '">' . ($j->token_ujian ?? '-') . '</span>';
            })
            ->addColumn('action', function ($j) {
                return view('pages.cbt.jadwal._actions', compact('j'));
            })
            ->rawColumns(['token_ujian', 'action'])
            ->make(true);
    }

    public function create()
    {
        $paket = PaketUjian::all();
        return view('pages.cbt.jadwal.create', compact('paket'));
    }

    public function store(StoreJadwalRequest $request)
    {
        try {
            $this->JadwalUjianService->store($request->validated());
            return jsonSuccess('Jadwal ujian berhasil dibuat.', route('cbt.jadwal.index'));
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function generateToken(JadwalUjian $jadwal)
    {
        try {
            $this->JadwalUjianService->generateToken($jadwal);
            return jsonSuccess('Token baru berhasil digenerate: ' . $jadwal->token_ujian);
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function toggleToken(JadwalUjian $jadwal)
    {
        try {
            $this->JadwalUjianService->toggleToken($jadwal);
            return jsonSuccess('Status token berhasil diubah.');
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function destroy(JadwalUjian $jadwal)
    {
        try {
            $this->JadwalUjianService->delete($jadwal);
            return jsonSuccess('Jadwal ujian berhasil dihapus.');
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }
}
